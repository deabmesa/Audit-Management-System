@php($role = optional(auth()->user()->role)->name)
<aside class="w-80 bg-slate-900 text-white p-4">
    <div class="flex items-center gap-3 pb-5 border-b border-slate-700 mb-4">
        <img src="{{ auth()->user()->photo_path ? asset(auth()->user()->photo_path) : 'https://via.placeholder.com/48' }}" class="w-12 h-12 rounded-full" alt="Profile">
        <div>
            <p class="font-semibold">{{ auth()->user()->name }}</p>
            <p class="text-sm text-slate-300">{{ auth()->user()->staff_id }}</p>
        </div>
    </div>

    @php
        $menu = [
            ['label'=>'Develop Task','icon'=>'🗂️','children'=>[
                ['route'=>'tasks.list','label'=>'Task List'],['route'=>'tasks.assignment','label'=>'Task Assignment'],['route'=>'tasks.status','label'=>'Task Status']
            ]],
            ['label'=>'User','roles'=>['Admin'],'children'=>[['route'=>'user.create','label'=>'Create User'],['route'=>'user.manage','label'=>'Manage Users']]],
            ['label'=>'Administrator','roles'=>['Admin'],'children'=>[['route'=>'administrator.roles','label'=>'Role Management'],['route'=>'administrator.permissions','label'=>'Permission Management']]],
            ['label'=>'Dashboard','route'=>'dashboard'],
            ['label'=>'Setting','children'=>[['route'=>'settings.general','label'=>'General Settings'],['route'=>'settings.logs','label'=>'System Logs']]],
            ['label'=>'Pre-Audit Work','children'=>[['route'=>'preaudit.checklist','label'=>'Checklist'],['route'=>'preaudit.risk','label'=>'Risk Assessment']]],
            ['label'=>'Audit Fieldwork','children'=>[['route'=>'fieldwork.tasks','label'=>'Field Task'],['route'=>'fieldwork.evidence','label'=>'Evidence Upload']]],
            ['label'=>'Audit Report','children'=>[['route'=>'reports.draft','label'=>'Draft Reports'],['route'=>'reports.final','label'=>'Final Reports']]],
            ['label'=>'Management Report','children'=>[['route'=>'management.summary','label'=>'Summary Report'],['route'=>'management.kpi','label'=>'KPI Report']]],
            ['label'=>'Other','children'=>[['route'=>'other.notes','label'=>'Notes'],['route'=>'other.reminders','label'=>'Reminders']]],
            ['label'=>'Contact List','route'=>'contact.index'],
            ['label'=>'Change Password','route'=>'password.edit'],
        ];
    @endphp

    <nav class="space-y-1">
        @foreach($menu as $index => $item)
            @continue(isset($item['roles']) && !in_array($role, $item['roles']))
            @php($hasChildren = isset($item['children']))
            <div>
                @if($hasChildren)
                    <button data-submenu-toggle="submenu-{{ $index }}" class="w-full text-left px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs(collect($item['children'])->pluck('route')->toArray()) ? 'bg-slate-700' : '' }}">{{ $item['label'] }}</button>
                    <div id="submenu-{{ $index }}" class="ml-3 mt-1 space-y-1 {{ request()->routeIs(collect($item['children'])->pluck('route')->toArray()) ? '' : 'hidden' }}">
                        @foreach($item['children'] as $child)
                            <a href="{{ route($child['route']) }}" class="block px-3 py-2 rounded text-sm {{ request()->routeIs($child['route']) ? 'bg-cyan-600' : 'hover:bg-slate-700' }}">{{ $child['label'] }}</a>
                        @endforeach
                    </div>
                @else
                    <a href="{{ route($item['route']) }}" class="block px-3 py-2 rounded {{ request()->routeIs($item['route']) ? 'bg-cyan-600' : 'hover:bg-slate-700' }}">{{ $item['label'] }}</a>
                @endif
            </div>
        @endforeach
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="mt-6">@csrf<button class="w-full bg-red-600 px-3 py-2 rounded">Logout</button></form>
</aside>
