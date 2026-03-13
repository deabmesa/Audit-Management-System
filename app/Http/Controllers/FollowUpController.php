<?php

namespace App\Http\Controllers;

use App\Models\AuditFinding;
use App\Models\FollowUp;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    public function store(Request $request, AuditFinding $finding)
    {
        $data = $request->validate([
            'recommendation_status' => 'required|string|max:255',
            'due_date' => 'required|date',
            'follow_up_comments' => 'nullable|string',
            'status' => 'required|in:Open,Closed',
        ]);

        $data['audit_finding_id'] = $finding->id;
        FollowUp::create($data);

        return back()->with('success', 'Follow-up status recorded.');
    }
}
