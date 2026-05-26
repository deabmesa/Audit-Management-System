@extends('layouts.app')
@section('page-title','Dashboard')
@section('content')

<div class="ph">
  <div class="ph-info">
    <h1>KPI Dashboard</h1>
    <p>Audit Governance Overview &mdash; {{ now()->format('d M Y') }}</p>
  </div>
  <div class="ph-acts">
    <button class="btn btn-outline"><i class="bi bi-download"></i> Export</button>
    <button class="btn btn-primary"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
  </div>
</div>

{{-- KPI Stat Cards --}}
<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-ico ico-blue"><i class="bi bi-clipboard2-check"></i></div>
    <div>
      <div class="stat-val">{{ $totalAudits ?? 18 }}</div>
      <div class="stat-lbl">Total Audits</div>
      <div class="stat-sub up"><i class="bi bi-arrow-up-short"></i>12% vs last quarter</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico ico-green"><i class="bi bi-check-circle"></i></div>
    <div>
      <div class="stat-val">{{ $completed ?? 7 }}</div>
      <div class="stat-lbl">Completed</div>
      <div class="stat-sub up"><i class="bi bi-arrow-up-short"></i>5% vs last quarter</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico ico-amber"><i class="bi bi-clock-history"></i></div>
    <div>
      <div class="stat-val">{{ $inProgress ?? 9 }}</div>
      <div class="stat-lbl">In Progress</div>
      <div class="stat-sub dn"><i class="bi bi-arrow-down-short"></i>2% vs last quarter</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico ico-red"><i class="bi bi-exclamation-triangle"></i></div>
    <div>
      <div class="stat-val">{{ $openFindings ?? 14 }}</div>
      <div class="stat-lbl">Open Findings</div>
      <div class="stat-sub dn"><i class="bi bi-arrow-down-short"></i>8% resolved</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-ico ico-purple"><i class="bi bi-people"></i></div>
    <div>
      <div class="stat-val">{{ $staff ?? 15 }}</div>
      <div class="stat-lbl">Staff Involved</div>
      <div class="stat-sub up"><i class="bi bi-arrow-up-short"></i>3 new this quarter</div>
    </div>
  </div>
</div>

{{-- Charts row 1 --}}
<div style="display:grid;grid-template-columns:1.1fr 1fr 1fr;gap:14px;margin-bottom:14px">

  <div class="card">
    <div class="card-hd">
      <span class="card-title"><i class="bi bi-bar-chart" style="color:var(--c-blue)"></i>Quarter Observations</span>
      <span class="badge b-blue">Q{{ ceil(now()->month/3) }} {{ now()->year }}</span>
    </div>
    <div class="card-body"><canvas id="cObs" style="max-height:200px"></canvas></div>
  </div>

  <div class="card">
    <div class="card-hd">
      <span class="card-title"><i class="bi bi-pie-chart" style="color:var(--c-purple)"></i>Plan Status</span>
    </div>
    <div class="card-body" style="display:flex;align-items:center;justify-content:center">
      <canvas id="cPlan" style="max-height:200px;max-width:200px"></canvas>
    </div>
  </div>

  <div class="card">
    <div class="card-hd"><span class="card-title"><i class="bi bi-activity" style="color:var(--c-amber)"></i>KPI Indicators</span></div>
    <div class="card-body">
      @foreach([
        ['Weekly Audit Cycle','55','green'],
        ['Mgmt Response Time','70','amber'],
        ['Finding Resolution','82','green'],
        ['Fieldwork Completion','40','red'],
      ] as [$lbl,$pct,$col])
      <div style="margin-bottom:13px">
        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:5px">
          <span style="color:var(--t2)">{{ $lbl }}</span>
          <span style="font-weight:600;color:var(--t1)">{{ $pct }}%</span>
        </div>
        <div style="height:6px;background:#f1f5f9;border-radius:10px;overflow:hidden">
          <div style="height:100%;width:{{ $pct }}%;border-radius:10px;background:var(--c-{{ $col }})"></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

</div>

{{-- Charts row 2 --}}
<div style="display:grid;grid-template-columns:1.6fr 1fr;gap:14px">

  <div class="card">
    <div class="card-hd">
      <span class="card-title"><i class="bi bi-graph-up" style="color:var(--c-green)"></i>Time Spent in Audit (Hours)</span>
      <span class="badge b-slate">{{ now()->year }}</span>
    </div>
    <div class="card-body"><canvas id="cTime" style="max-height:210px"></canvas></div>
  </div>

  <div class="card">
    <div class="card-hd"><span class="card-title"><i class="bi bi-flag" style="color:var(--c-red)"></i>Recent Findings</span></div>
    <div>
      @foreach([
        ['Missing Internal Controls','High'],
        ['Segregation of Duties Issue','Medium'],
        ['Policy Gap Identified','Low'],
        ['Approval Process Bypass','High'],
        ['Documentation Incomplete','Medium'],
      ] as [$name,$risk])
      <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 18px;border-bottom:1px solid #f1f5f9">
        <span style="font-size:13px;color:var(--t1)">{{ $name }}</span>
        <span class="badge {{ $risk=='High'?'b-red':($risk=='Medium'?'b-amber':'b-green') }}">{{ $risk }}</span>
      </div>
      @endforeach
    </div>
  </div>

</div>

<script src="{{ asset('js/chart.min.js') }}"></script>
<script>
(function(){
  const base = {
    responsive:true, maintainAspectRatio:false,
    plugins:{legend:{display:false}},
    scales:{
      x:{grid:{display:false},ticks:{font:{size:11},color:'#96a8be'}},
      y:{grid:{color:'#f1f5f9'},ticks:{font:{size:11},color:'#96a8be'},beginAtZero:true}
    }
  };
  new Chart(document.getElementById('cObs'),{type:'bar',data:{
    labels:['Significant','Moderate','Low'],
    datasets:[{data:[10,14,17],backgroundColor:['#fee2e2','#fef3c7','#dcfce7'],borderColor:['#dc2626','#d97706','#16a34a'],borderWidth:2,borderRadius:6}]
  },options:{...base}});

  new Chart(document.getElementById('cPlan'),{type:'doughnut',data:{
    labels:['Completed','In Progress','Not Started'],
    datasets:[{data:[7,9,2],backgroundColor:['#16a34a','#f59e0b','#e2e8f4'],borderWidth:0,hoverOffset:4}]
  },options:{responsive:true,maintainAspectRatio:false,cutout:'65%',plugins:{legend:{position:'bottom',labels:{font:{size:11},boxWidth:10,padding:10}}}}});

  new Chart(document.getElementById('cTime'),{type:'bar',data:{
    labels:['Q1','Q2','Q3','Q4'],
    datasets:[
      {label:'Target',data:[80,62,77,81],backgroundColor:'#dbeafe',borderColor:'#2563eb',borderWidth:2,borderRadius:5},
      {label:'Actual', data:[77,68,65,84],backgroundColor:'#fef3c7',borderColor:'#d97706',borderWidth:2,borderRadius:5}
    ]
  },options:{...base,plugins:{legend:{display:true,position:'top',labels:{font:{size:11},boxWidth:10}}}}});
})();
</script>
@endsection
