@extends('layouts.app')
@section('content')
<h4>Oracle Transaction Summary (Read-Only)</h4>
<form class="row g-2 mb-3">
    <div class="col"><input name="department" class="form-control" placeholder="Department" value="{{ request('department') }}"></div>
    <div class="col"><input name="category" class="form-control" placeholder="Category" value="{{ request('category') }}"></div>
    <div class="col"><button class="btn btn-secondary">Filter</button></div>
</form>
<table class="table table-sm"><tr><th>Date</th><th>Department</th><th>Category</th><th>Total Value</th></tr>
@foreach($reports as $row)
<tr><td>{{ $row->TRANSACTION_DATE }}</td><td>{{ $row->DEPARTMENT }}</td><td>{{ $row->CATEGORY }}</td><td>{{ $row->TOTAL_VALUE }}</td></tr>
@endforeach
</table>
{{ $reports->links() }}
@endsection
