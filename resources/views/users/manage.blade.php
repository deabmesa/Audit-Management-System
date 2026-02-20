@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Manage Users</h1>
<ul class="bg-white rounded shadow p-4 space-y-2">@foreach($users as $user)<li>{{ $user->name }} ({{ $user->email }}) - {{ optional($user->role)->name }}</li>@endforeach</ul>
@endsection
