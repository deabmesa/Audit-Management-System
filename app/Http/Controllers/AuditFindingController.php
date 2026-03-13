<?php

namespace App\Http\Controllers;

use App\Models\AuditEngagement;
use App\Models\AuditFinding;
use Illuminate\Http\Request;

class AuditFindingController extends Controller
{
    public function store(Request $request, AuditEngagement $audit)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'risk_rating' => 'required|in:Low,Medium,High',
            'root_cause' => 'required|string',
            'recommendation' => 'required|string',
            'management_response' => 'nullable|string',
        ]);

        $data['audit_engagement_id'] = $audit->id;
        AuditFinding::create($data);

        return back()->with('success', 'Finding logged.');
    }
}
