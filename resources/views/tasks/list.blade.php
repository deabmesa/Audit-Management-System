@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Task List</h1>
<table class="w-full bg-white rounded shadow"><thead><tr class="text-left border-b"><th class="p-2">Task</th><th class="p-2">Audit</th><th class="p-2">Assigned</th><th class="p-2">Status</th></tr></thead><tbody>
@foreach($tasks as $task)<tr class="border-b"><td class="p-2">{{ $task->title }}</td><td class="p-2">{{ $task->audit->title ?? '-' }}</td><td class="p-2">{{ $task->assignedTo->name ?? '-' }}</td><td class="p-2">{{ $task->status }}</td></tr>@endforeach
</tbody></table>
@endsection
