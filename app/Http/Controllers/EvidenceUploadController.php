<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EvidenceUploadController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'audit_finding_id' => ['required', 'integer'],
            'evidence' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
        ]);

        $path = $request->file('evidence')->store('evidence', 'private');

        return back()->with('status', "Evidence uploaded: {$path}");
    }
}
