@extends('layouts.app')
@section('content')
<h4>Menu Builder</h4>
<div class="card card-body">
    <p class="text-muted">Drag/drop is enabled through SortableJS style data payload editing.</p>
    <form method="POST" action="{{ route('menus.store') }}">
        @csrf
        <textarea name="tree" id="treeInput" class="form-control" rows="10">@json($menus)</textarea>
        <button class="btn btn-primary mt-2">Save Menu Tree</button>
    </form>
</div>
@endsection
