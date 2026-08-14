@extends('layouts.app')

@section('content')
<div class="card" style="max-width:520px; margin:2rem auto;">
    <h2>Check-In Required</h2>
    <p>Complete check-in to access Staff Info and PAMS modules.</p>
    <form method="POST" action="{{ route('checkin.perform') }}">@csrf<button type="submit">Check-In Now</button></form>
</div>
@endsection
