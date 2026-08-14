@extends('layouts.app')
@section('content')
<h4>Oracle Financial Report (Read-Only)</h4>
<form class="row g-2 mb-3">
    <div class="col"><input name="department" class="form-control" placeholder="Department" value="{{ request('department') }}"></div>
    <div class="col"><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"></div>
    <div class="col"><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"></div>
    <div class="col"><button class="btn btn-secondary">Filter</button></div>
</form>
<table class="table table-sm"><tr><th>Department</th><th>Date</th><th>Total Amount</th><th>Transactions</th></tr>
@foreach($reports as $row)
<tr><td>{{ $row->DEPARTMENT }}</td><td>{{ $row->REPORT_DATE }}</td><td>{{ $row->TOTAL_AMOUNT }}</td><td>{{ $row->TRANSACTION_COUNT }}</td></tr>
@endforeach
</table>
{{ $reports->links() }}
@endsection
