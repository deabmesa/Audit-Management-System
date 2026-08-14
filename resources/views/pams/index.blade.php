@extends('layouts.app')

@section('content')
<div class="card">
    <h1>PAMS Reports (Oracle Read-Only)</h1>
    <form method="GET" action="{{ route('pams.index') }}">
        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Search audit name">
        <select name="status">
            <option value="">All</option>
            <option value="OPEN" @selected(request('status')==='OPEN')>Open</option>
            <option value="CLOSED" @selected(request('status')==='CLOSED')>Closed</option>
        </select>
        <button type="submit">Filter</button>
        <a href="{{ route('pams.export.pdf') }}">Export PDF</a>
        <a href="{{ route('pams.export.excel') }}">Export Excel</a>
    </form>

    <table>
        <thead><tr><th>Audit Name</th><th>Status</th><th>Report Date</th></tr></thead>
        <tbody>
        @foreach($reports as $report)
            <tr>
                <td>{{ $report->AUDIT_NAME }}</td>
                <td>{{ $report->STATUS }}</td>
                <td>{{ $report->REPORT_DATE }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $reports->links() }}
</div>
@endsection
