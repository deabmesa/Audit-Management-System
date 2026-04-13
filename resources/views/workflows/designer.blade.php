@extends('layouts.app')
@section('content')
<h4>Workflow Designer</h4>
<form method="POST" action="{{ route('workflow.save-designer') }}" class="card card-body">@csrf
<div id="stepsWrap">
@foreach($steps as $i => $step)
<div class="row g-2 mb-2">
<input type="hidden" name="steps[{{ $i }}][name]" value="{{ $step->name }}">
<div class="col"><input class="form-control" value="{{ $step->name }}" disabled></div>
<div class="col"><input class="form-control" name="steps[{{ $i }}][required_role]" value="{{ $step->required_role }}"></div>
<div class="col"><input class="form-control" name="steps[{{ $i }}][final_state]" value="{{ $step->final_state }}"></div>
</div>
@endforeach
</div>
<button class="btn btn-primary">Save Workflow</button>
</form>
@endsection
