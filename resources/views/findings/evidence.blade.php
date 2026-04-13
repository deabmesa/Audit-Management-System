@extends('layouts.app')
@section('content')
<h4>Evidence - {{ $finding->title }}</h4>
<form method="POST" action="{{ route('evidence.store',$finding) }}" enctype="multipart/form-data" class="card card-body mb-3">
@csrf
<input name="name" class="form-control mb-2" placeholder="Evidence name" required>
<input type="file" name="file" class="form-control mb-2" required>
<button class="btn btn-primary">Upload</button>
</form>
@foreach($evidenceItems as $evidence)
<div class="card mb-3"><div class="card-header">{{ $evidence->name }}</div><div class="card-body">
<ul class="mb-0">
@foreach($evidence->versions as $version)
<li>Version {{ $version->version }} · SHA256: <code>{{ $version->checksum_sha256 }}</code>
<a class="btn btn-sm btn-outline-secondary" href="{{ route('evidence.download',[$evidence,$version->version]) }}">Download</a></li>
@endforeach
</ul>
</div></div>
@endforeach
@endsection
