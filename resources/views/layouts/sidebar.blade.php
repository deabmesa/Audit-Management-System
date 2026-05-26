@foreach($menus as $menu)

@php
    $hasChildren = $menu->children->count() > 0;
@endphp

<div x-data="{ open:false }">

    <div class="menu-item" @click="open = !open">

        <div class="menu-label">
            @if(!empty($menu->icon))
                <i class="bi {{ $menu->icon }}"></i>
            @endif

            <span class="menu-text">{{ $menu->name }}</span>
        </div>

        @if($hasChildren)
            <i class="bi bi-chevron-down"></i>
        @endif
    </div>

    @if($hasChildren)
        <div x-show="open" x-transition class="submenu">

            @foreach($menu->children as $child)

                <a href="{{ url($child->route ?? '#') }}"
                   class="submenu-item">

                    <span class="menu-text">{{ $child->name }}</span>
                </a>

                {{-- LEVEL 3 (nested submenu fix) --}}
                @if($child->children->count())
                    <div class="ml-4">

                        @foreach($child->children as $sub)

                            <a href="{{ url($sub->route ?? '#') }}"
                               class="submenu-item text-sm">

                                + {{ $sub->name }}
                            </a>

                        @endforeach

                    </div>
                @endif

            @endforeach

        </div>
    @endif

</div>

@endforeach