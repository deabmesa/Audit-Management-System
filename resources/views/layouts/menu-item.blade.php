@php
$hasChildren = $menu->childrenRecursive->count();

$canSee = auth()->user()->hasRole('Admin') 
          || !$menu->permission 
          || auth()->user()->hasPermission($menu->permission);
@endphp

@if($canSee || $hasChildren)

<li class="nav-item {{ $hasChildren ? 'has-treeview' : '' }}">

    <a href="{{ $menu->getRoute() }}" class="nav-link">

        <i class="{{ $menu->getIcon() }}"></i>

        <p>
            {{ $menu->name }}

            @if($hasChildren)
                <i class="right fas fa-angle-left"></i>
            @endif
        </p>

    </a>

    @if($hasChildren)

        <ul class="nav nav-treeview">

            @foreach($menu->childrenRecursive as $child)

                @php
                $childCanSee = auth()->user()->hasRole('Admin') 
                               || !$child->permission 
                               || auth()->user()->hasPermission($child->permission);
                @endphp

                @if($childCanSee)
                    @include('layouts.menu-item', ['menu' => $child])
                @endif

            @endforeach

        </ul>

    @endif

</li>

@endif