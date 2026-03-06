@extends('layouts.app')

@section('content')
    <div class="grid grid-2">
        <div class="card">
            <h2>Audit Program</h2>
            <form method="POST" action="{{ route('audit.programs.store') }}">
                @csrf
                <input name="title" placeholder="Program title" required style="width:100%;padding:10px;margin-bottom:8px;">
                <textarea name="scope" placeholder="Scope" required style="width:100%;padding:10px;margin-bottom:8px;"></textarea>
                <input type="date" name="start_date" required>
                <input type="date" name="end_date" required>
                <input name="status" value="Draft" required>
                <button class="btn" type="submit">Save Program</button>
            </form>
        </div>
        <div class="card">
            <h2>Audit Findings</h2>
            <p>Recommendation Tracking, Follow-up Monitoring, Audit Reports, and Evidence Upload are managed here with RBAC.</p>
            <ul>
                @foreach($findings as $finding)
                    <li>{{ $finding->title }} ({{ $finding->risk_level }}) - {{ $finding->status }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
