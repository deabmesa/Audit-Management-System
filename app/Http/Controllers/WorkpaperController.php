<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Audit;
use App\Models\Workpaper;
use Illuminate\Http\Request;

class WorkpaperController extends Controller
{
    public function index()
    {
        return view('pams.workpapers.index', [
            'workpapers' => Workpaper::with('audit')->paginate(15),
            'audits' => Audit::orderBy('title')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'audit_id' => ['required', 'exists:audits,id'],
            'procedure' => ['required', 'string'],
            'status' => ['required', 'in:Open,In Progress,Closed'],
            'evidence' => ['nullable', 'file', 'max:10240'],
        ]);

        $workpaper = Workpaper::create([
            'audit_id' => $validated['audit_id'],
            'procedure' => $validated['procedure'],
            'status' => $validated['status'],
            'prepared_by' => $request->user()->id,
            'evidence_path' => $request->file('evidence')?->store('workpapers', 'public'),
        ]);

        if ($workpaper->evidence_path) {
            Attachment::create([
                'attachable_type' => Workpaper::class,
                'attachable_id' => $workpaper->id,
                'path' => $workpaper->evidence_path,
                'uploaded_by' => $request->user()->id,
            ]);
        }

        return back()->with('status', 'Workpaper saved.');
    }
}
