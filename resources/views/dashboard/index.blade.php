@extends('layouts.app')

@section('content')
<div class="row g-3 mb-4">
    @foreach($kpi as $label => $value)
    <div class="col-md-3">
        <div class="card shadow-sm"><div class="card-body">
            <h6 class="text-muted text-uppercase">{{ str_replace('_', ' ', $label) }}</h6>
            <h3>{{ $value }}</h3>
        </div></div>
    </div>
    @endforeach
</div>
<div class="card shadow-sm"><div class="card-body">
    <h5>Status distribution</h5>
    <canvas id="statusChart" height="90"></canvas>
</div></div>
@endsection

@push('scripts')
<script>
fetch("{{ route('dashboard.chart-data') }}").then(r=>r.json()).then(data=>{
  const ctx = document.getElementById('statusChart');
  new Chart(ctx, { type:'bar', data: { labels:Object.keys(data), datasets:[{label:'Findings', data:Object.values(data)}] } });
});
</script>
@endpush
