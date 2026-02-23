@extends('layouts.app')

@section('content')
<div class="card">
    <h1>Staff Info</h1>
    <a href="{{ route('staff-info.create') }}">Create Engagement</a>
    <table>
        <thead><tr><th>Title</th><th>Entity</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($engagements as $engagement)
            <tr>
                <td>{{ $engagement->title }}</td>
                <td>{{ $engagement->entity_name }}</td>
                <td>{{ $engagement->status }}</td>
                <td>
                    <a href="{{ route('staff-info.edit', $engagement) }}">Edit</a>
                    <form method="POST" action="{{ route('staff-info.destroy', $engagement) }}" style="display:inline">@csrf @method('DELETE')<button>Delete</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $engagements->links() }}
</div>
@endsection
