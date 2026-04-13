<h2>Audit Finding Report #{{ $finding->id }}</h2>
<p><strong>Title:</strong> {{ $finding->title }}</p>
<p><strong>Description:</strong> {{ $finding->description }}</p>
<p><strong>Status:</strong> {{ $finding->status }}</p>
<p><strong>Severity:</strong> {{ $finding->severity }}</p>
<h4>Evidence Versions</h4>
<ul>
@foreach($finding->evidence as $e)
    @foreach($e->versions as $v)
    <li>{{ $e->name }} v{{ $v->version }} - {{ $v->checksum_sha256 }}</li>
    @endforeach
@endforeach
</ul>
<h4>Approval History</h4>
<ul>
@foreach($finding->approvals as $approval)
<li>{{ $approval->created_at }} - {{ $approval->action }} ({{ $approval->remarks }})</li>
@endforeach
</ul>
<p><strong>SHA-256 Audit Hash:</strong> {{ $auditHash }}</p>
