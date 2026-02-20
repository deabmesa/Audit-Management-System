<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\Issue;
use App\Notifications\IssueAssignedNotification;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function create(Audit $audit)
    {
        return view('issues.create', compact('audit'));
    }

    public function store(Request $request, Audit $audit)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'risk_level' => 'required|string|max:100',
            'recommendation' => 'required|string',
            'due_date' => 'required|date',
            'responsible_person' => 'required|string|max:255',
            'status' => 'required|in:Open,In Progress,Closed',
        ]);

        $issue = $audit->issues()->create($validated);
        auth()->user()?->notify(new IssueAssignedNotification($issue));

        return redirect()->route('audits.show', $audit)->with('success', 'Issue created.');
    }

    public function show(Issue $issue)
    {
        $issue->load(['audit', 'attachments']);
        return view('issues.show', compact('issue'));
    }

    public function edit(Issue $issue)
    {
        return view('issues.edit', compact('issue'));
    }

    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'risk_level' => 'required|string|max:100',
            'recommendation' => 'required|string',
            'due_date' => 'required|date',
            'responsible_person' => 'required|string|max:255',
            'status' => 'required|in:Open,In Progress,Closed',
        ]);

        $issue->update($validated);
        return redirect()->route('issues.show', $issue)->with('success', 'Issue updated.');
    }

    public function destroy(Issue $issue)
    {
        $audit = $issue->audit;
        $issue->delete();
        return redirect()->route('audits.show', $audit)->with('success', 'Issue deleted.');
    }
}
