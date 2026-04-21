@extends('layouts.app')

@section('content')
<div id="menu-builder-app"
     data-menu-items='@json($menuItems->values())'
     data-roles='@json($roles)'
     data-csrf='{{ csrf_token() }}'
     data-store-url='{{ route('admin.menu.store') }}'
     data-reorder-url='{{ route('admin.menu.reorder') }}'>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">RBAC Dynamic Menu Builder (Vue)</h4>
        <small class="text-muted">Drag & drop to reorder dynamic menu items.</small>
    </div>

    <div v-if="flashMessage" class="alert alert-success">@{{ flashMessage }}</div>
    <div v-if="flashError" class="alert alert-danger">@{{ flashError }}</div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">Menu Items</div>
                <div class="card-body">
                    <ul class="list-group" id="menu-sortable">
                        <li class="list-group-item"
                            v-for="item in menuItems"
                            :key="item.id"
                            :data-id="item.id"
                            draggable="true"
                            @dragstart="onDragStart(item.id)"
                            @dragover.prevent
                            @drop="onDrop(item.id)">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>@{{ item.title }}</strong>
                                <span class="badge" :class="item.is_active ? 'text-bg-success' : 'text-bg-secondary'">
                                    @{{ item.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input class="form-control" v-model="item.title" placeholder="Menu title">
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" v-model="item.route_name" placeholder="Route name (e.g. dashboard)">
                                </div>
                                <div class="col-12">
                                    <input class="form-control" v-model="item.url" placeholder="URL (optional)">
                                </div>
                                <div class="col-12">
                                    <label class="form-label mb-1">Allowed roles</label>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <label class="form-check-label" v-for="role in roles" :key="`${item.id}-${role}`">
                                            <input class="form-check-input me-1"
                                                   type="checkbox"
                                                   :checked="(item.roles || []).includes(role)"
                                                   @change="toggleRole(item, role)">
                                            @{{ role }}
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">Leave unchecked for all roles.</small>
                                </div>
                                <div class="col-12 d-flex gap-2">
                                    <label class="form-check-label me-auto">
                                        <input class="form-check-input me-1" type="checkbox" v-model="item.is_active"> Active
                                    </label>
                                    <button class="btn btn-sm btn-primary" @click="saveItem(item)">Save</button>
                                    <button class="btn btn-sm btn-outline-danger" @click="deleteItem(item)">Delete</button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">Create Menu Item</div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <input class="form-control" v-model="newItem.title" placeholder="Menu title">
                        </div>
                        <div class="col-12">
                            <input class="form-control" v-model="newItem.route_name" placeholder="Route name (optional)">
                        </div>
                        <div class="col-12">
                            <input class="form-control" v-model="newItem.url" placeholder="URL (optional)">
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-1">Allowed roles</label>
                            <div class="d-flex gap-3 flex-wrap">
                                <label class="form-check-label" v-for="role in roles" :key="`create-${role}`">
                                    <input class="form-check-input me-1"
                                           type="checkbox"
                                           :checked="newItem.roles.includes(role)"
                                           @change="toggleNewRole(role)">
                                    @{{ role }}
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-check-label">
                                <input class="form-check-input me-1" type="checkbox" v-model="newItem.is_active"> Active
                            </label>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success" @click="createItem">Create Menu Item</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
(() => {
    const root = document.getElementById('menu-builder-app');
    if (!root || !window.Vue) return;

    const { createApp } = Vue;

    createApp({
        data() {
            return {
                menuItems: JSON.parse(root.dataset.menuItems || '[]').map((item) => ({
                    ...item,
                    roles: item.roles || [],
                    is_active: Boolean(item.is_active),
                })),
                roles: JSON.parse(root.dataset.roles || '[]'),
                csrfToken: root.dataset.csrf,
                storeUrl: root.dataset.storeUrl,
                reorderUrl: root.dataset.reorderUrl,
                dragSourceId: null,
                flashMessage: '',
                flashError: '',
                newItem: {
                    title: '',
                    route_name: '',
                    url: '',
                    roles: [],
                    is_active: true,
                },
            };
        },
        methods: {
            async createItem() {
                this.flashError = '';
                if (!this.newItem.title.trim()) {
                    this.flashError = 'Title is required.';
                    return;
                }

                const payload = await this.request(this.storeUrl, 'POST', this.newItem);
                if (!payload) return;

                this.menuItems.push({
                    ...payload.item,
                    roles: payload.item.roles || [],
                    is_active: Boolean(payload.item.is_active),
                });

                this.newItem = { title: '', route_name: '', url: '', roles: [], is_active: true };
                this.flashMessage = payload.message || 'Menu item created.';
            },
            async saveItem(item) {
                const payload = await this.request(`/admin/menu/${item.id}`, 'PUT', item);
                if (!payload) return;
                Object.assign(item, {
                    ...payload.item,
                    roles: payload.item.roles || [],
                    is_active: Boolean(payload.item.is_active),
                });
                this.flashMessage = payload.message || 'Menu item updated.';
            },
            async deleteItem(item) {
                if (!confirm('Delete this menu item?')) return;

                const payload = await this.request(`/admin/menu/${item.id}`, 'DELETE');
                if (!payload) return;

                this.menuItems = this.menuItems.filter((menu) => menu.id !== item.id);
                this.flashMessage = payload.message || 'Menu item deleted.';
            },
            toggleRole(item, role) {
                item.roles = item.roles || [];
                if (item.roles.includes(role)) {
                    item.roles = item.roles.filter((r) => r !== role);
                } else {
                    item.roles.push(role);
                }
            },
            toggleNewRole(role) {
                if (this.newItem.roles.includes(role)) {
                    this.newItem.roles = this.newItem.roles.filter((r) => r !== role);
                } else {
                    this.newItem.roles.push(role);
                }
            },
            onDragStart(id) {
                this.dragSourceId = id;
            },
            async onDrop(targetId) {
                if (!this.dragSourceId || this.dragSourceId === targetId) return;

                const sourceIndex = this.menuItems.findIndex((item) => item.id === this.dragSourceId);
                const targetIndex = this.menuItems.findIndex((item) => item.id === targetId);
                if (sourceIndex < 0 || targetIndex < 0) return;

                const [moved] = this.menuItems.splice(sourceIndex, 1);
                this.menuItems.splice(targetIndex, 0, moved);

                const orderedIds = this.menuItems.map((item) => item.id);
                const payload = await this.request(this.reorderUrl, 'POST', { ordered_ids: orderedIds });
                if (payload) {
                    this.flashMessage = payload.message || 'Menu order updated.';
                }
            },
            async request(url, method, body = null) {
                this.flashError = '';
                const options = {
                    method,
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                };

                if (body !== null) {
                    options.body = JSON.stringify(body);
                }

                const response = await fetch(url, options);
                const payload = await response.json().catch(() => ({}));

                if (!response.ok) {
                    this.flashError = payload.message || 'Request failed.';
                    return null;
                }

                return payload;
            },
        },
    }).mount('#menu-builder-app');
})();
</script>
@endsection
