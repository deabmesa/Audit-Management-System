@extends('layouts.app')

@section('content')

<h4>Normal ​& WOFF Loan</h4>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data ?? [] as $row)
        <tr>
            <td>{{ json_encode($row) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection