@foreach($menus as $menu)
<tr>
  <td>{{ $menu->name }}</td>
  <td style="color:var(--t2);font-size:12px">{{ $menu->module??'-' }}</td>
  <td style="color:var(--t2);font-size:12px">{{ $menu->page??'-' }}</td>
  <td style="text-align:right">
    <div style="display:flex;gap:5px;justify-content:flex-end">
      <a href="{{ route('menus.create',['edit_id'=>$menu->id]) }}" class="btn btn-outline btn-sm"><i class="bi bi-pencil"></i></a>
      <form method="POST" action="{{ route('menus.destroy',$menu->id) }}" style="display:inline" onsubmit="return confirm('Delete?')">
        @csrf @method('DELETE')
        <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
      </form>
    </div>
  </td>
</tr>
@if($menu->childrenRecursive && $menu->childrenRecursive->count())
  @include('menus.partials.menu-row',['menus'=>$menu->childrenRecursive,'level'=>$level+1])
@endif
@endforeach
