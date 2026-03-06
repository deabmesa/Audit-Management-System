@extends('layouts.app')

@section('content')
<div class="card">
    <h2>{{ $template->name }}</h2>
    <table>
        <thead>
            <tr>
                @foreach(($template->output_columns ?? []) as $column)
                    <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($result as $row)
                <tr>
                    @foreach(($template->output_columns ?? []) as $column)
                        <td>{{ data_get((array) $row, $column) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $result->links() }}
</div>
@endsection
