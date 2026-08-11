@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Report Template Builder</h2>
    <form method="POST" action="{{ route('reports.templates.store') }}">
        @csrf
        <input name="name" placeholder="Template name" required>
        <select name="database_source" required>
            <option value="pgsql">Main PostgreSQL</option>
            <option value="external_pgsql">External PostgreSQL (Read Only)</option>
            <option value="external_oracle">External Oracle (Read Only)</option>
        </select>
        <textarea name="sql_query" required placeholder="SELECT ..."></textarea>
        <input name="output_columns[]" placeholder="Column 1" required>
        <button class="btn">Create Template</button>
    </form>

    <table>
        <thead><tr><th>Name</th><th>Source</th><th>Run</th></tr></thead>
        <tbody>
        @foreach($templates as $template)
            <tr>
                <td>{{ $template->name }}</td>
                <td>{{ $template->database_source }}</td>
                <td><a class="btn" href="{{ route('reports.templates.run', $template) }}">Run</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
