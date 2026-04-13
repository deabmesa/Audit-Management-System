<?php

namespace App\Http\Controllers;

use App\Models\Evidence;
use App\Models\EvidenceVersion;
use App\Models\Finding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    public function index(Finding $finding)
    {
        return view('findings.evidence', ['finding' => $finding, 'evidenceItems' => $finding->evidence()->with('versions')->get()]);
    }

    public function store(Request $request, Finding $finding)
    {
        $data = $request->validate(['name' => 'required|string', 'file' => 'required|file|max:10240']);
        $evidence = Evidence::firstOrCreate(['finding_id' => $finding->id, 'name' => $data['name']]);
        $filePath = $request->file('file')->store('evidence');
        $checksum = hash_file('sha256', Storage::path($filePath));
        $version = (int) $evidence->versions()->max('version') + 1;
        EvidenceVersion::create([
            'evidence_id' => $evidence->id,
            'version' => $version,
            'file_path' => $filePath,
            'checksum_sha256' => $checksum,
            'uploaded_by' => Auth::id(),
        ]);
        return back()->with('success', 'Evidence uploaded.');
    }

    public function download(Evidence $evidence, int $version)
    {
        $record = $evidence->versions()->where('version', $version)->firstOrFail();
        return Storage::download($record->file_path);
    }

    public function compare(Evidence $evidence, int $left, int $right)
    {
        $leftV = $evidence->versions()->where('version', $left)->firstOrFail();
        $rightV = $evidence->versions()->where('version', $right)->firstOrFail();
        return response()->json([
            'left' => $leftV->checksum_sha256,
            'right' => $rightV->checksum_sha256,
            'same' => $leftV->checksum_sha256 === $rightV->checksum_sha256,
        ]);
    }
}
