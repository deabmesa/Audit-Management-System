@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">RBAC Dynamic Menu Builder</h4>
    <small class="text-muted">Drag and drop rows to reorder menu items.</small>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Menu Items (Drag & Drop)</div>
            <div class="card-body">
                <ul class="list-group" id="menu-sortable">
                    @foreach($menuItems as $item)
                        <li class="list-group-item" data-id="{{ $item->id }}" draggable="true">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>{{ $item->title }}</strong>
                                <span class="badge {{ $item->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span>
                            </div>
                            <form method="POST" action="{{ route('admin.menu.update', $item) }}" class="row g-2">@csrf @method('PUT')
                                <div class="col-md-6">
                                    <input class="form-control" name="title" value="{{ $item->title }}" placeholder="Menu title" required>
                                </div>
                                <div class="col-md-6">
                                    <input class="form-control" name="route_name" value="{{ $item->route_name }}" placeholder="Route name (e.g. dashboard)">
                                </div>
                                <div class="col-12">
                                    <input class="form-control" name="url" value="{{ $item->url }}" placeholder="URL (optional)">
                                </div>
                                <div class="col-12">
                                    <label class="form-label mb-1">Allowed roles</label>
                                    <div class="d-flex gap-3">
                                        @foreach($roles as $role)
                                            <label class="form-check-label">
                                                <input class="form-check-input me-1" type="checkbox" name="roles[]" value="{{ $role }}" @checked(in_array($role, $item->roles ?? [], true))>
                                                {{ $role }}
                                            </label>
                                        @endforeach
                                    </div>
                                    <small class="text-muted d-block mt-1">Leave all unchecked to show for all roles.</small>
                                </div>
                                <div class="col-12 d-flex gap-2">
                                    <label class="form-check-label me-auto">
                                        <input class="form-check-input me-1" type="checkbox" name="is_active" value="1" @checked($item->is_active)> Active
                                    </label>
                                    <button class="btn btn-sm btn-primary">Save</button>
                            </form>
                                    <form method="POST" action="{{ route('admin.menu.destroy', $item) }}" onsubmit="return confirm('Delete this menu item?')">@csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Create Menu Item</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.menu.store') }}" class="row g-2">@csrf
                    <div class="col-12">
                        <input class="form-control" name="title" placeholder="Menu title" required>
                    </div>
                    <div class="col-12">
                        <input class="form-control" name="route_name" placeholder="Route name (optional)">
                    </div>
                    <div class="col-12">
                        <input class="form-control" name="url" placeholder="URL (optional)">
                    </div>
                    <div class="col-12">
                        <label class="form-label mb-1">Allowed roles</label>
                        <div class="d-flex gap-3 flex-wrap">
                            @foreach($roles as $role)
                                <label class="form-check-label">
                                    <input class="form-check-input me-1" type="checkbox" name="roles[]" value="{{ $role }}">{{ $role }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-check-label">
                            <input class="form-check-input me-1" type="checkbox" name="is_active" value="1" checked> Active
                        </label>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-success">Create Menu Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    const list = document.getElementById('menu-sortable');
    if (!list) return;

    let dragged = null;

    list.querySelectorAll('li').forEach((item) => {
        item.addEventListener('dragstart', () => {
            dragged = item;
            item.classList.add('opacity-50');
        });

        item.addEventListener('dragend', () => {
            item.classList.remove('opacity-50');
            dragged = null;
        });

        item.addEventListener('dragover', (event) => {
            event.preventDefault();
        });

        item.addEventListener('drop', (event) => {
            event.preventDefault();
            if (!dragged || dragged === item) return;

            const bounds = item.getBoundingClientRect();
            const offset = event.clientY - bounds.top;
            const midpoint = bounds.height / 2;

            if (offset > midpoint) {
                item.after(dragged);
            } else {
                item.before(dragged);
            }

            const orderedIds = Array.from(list.querySelectorAll('li')).map((node) => parseInt(node.dataset.id, 10));

            fetch('{{ route('admin.menu.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ ordered_ids: orderedIds }),
            });
        });
    });
})();
</script>
@endsection
