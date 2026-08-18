<?php

namespace App\Http\Controllers;

use App\Models\AuditEngagement;
use App\Models\User;
use Illuminate\Http\Request;

class AuditEngagementController extends Controller
{
    public function index()
    {
        $audits = AuditEngagement::with('auditors')->latest()->paginate(10);
        return view('audits.index', compact('audits'));
    }

    public function create()
    {
        $auditors = User::where('role', User::ROLE_AUDITOR)->get();
        return view('audits.create', compact('auditors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'scope' => 'required|string',
            'risk_level' => 'required|in:Low,Medium,High',
            'planned_start_date' => 'required|date',
            'planned_end_date' => 'required|date|after_or_equal:planned_start_date',
            'status' => 'required|in:Planned,In Progress,Completed',
            'auditor_ids' => 'array',
        ]);

        $audit = AuditEngagement::create(array_merge($data, ['created_by' => auth()->id()]));
        $audit->auditors()->sync($data['auditor_ids'] ?? []);

        return redirect()->route('audits.index')->with('success', 'Audit engagement created.');
    }

    public function show(AuditEngagement $audit)
    {
        $audit->load(['auditors', 'fieldworkItems', 'findings.followUps']);
        return view('audits.show', compact('audit'));
    }
}
