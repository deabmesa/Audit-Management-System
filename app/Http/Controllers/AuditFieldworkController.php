<?php

namespace App\Http\Controllers;

use App\Models\AuditEngagement;
use App\Models\AuditFieldwork;
use Illuminate\Http\Request;

class AuditFieldworkController extends Controller
{
    public function store(Request $request, AuditEngagement $audit)
    {
        $data = $request->validate([
            'checklist_item' => 'required|string',
            'working_paper' => 'nullable|string',
            'audit_notes' => 'nullable|string',
            'status' => 'required|in:Pending,Completed',
            'evidence_file' => 'nullable|file|max:5120',
        ]);

        if ($request->hasFile('evidence_file')) {
            $data['evidence_path'] = $request->file('evidence_file')->store('evidence', 'public');
        }

        $data['audit_engagement_id'] = $audit->id;
        AuditFieldwork::create($data);

        return back()->with('success', 'Fieldwork item added.');
    }
}
