<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use Illuminate\Http\Request;

class IssueApiController extends Controller
{
    public function index()
    {
        return Issue::with('audit')->paginate(20);
    }

    public function store(Request $request)
    {
        $issue = Issue::create($request->validate([
            'audit_id' => 'required|exists:audits,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'risk_level' => 'required|string|max:100',
            'recommendation' => 'required|string',
            'due_date' => 'required|date',
            'responsible_person' => 'required|string|max:255',
            'status' => 'required|in:Open,In Progress,Closed',
        ]));

        return response()->json($issue, 201);
    }

    public function show(Issue $issue)
    {
        return $issue->load('audit');
    }

    public function update(Request $request, Issue $issue)
    {
        $issue->update($request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'risk_level' => 'sometimes|required|string|max:100',
            'recommendation' => 'sometimes|required|string',
            'due_date' => 'sometimes|required|date',
            'responsible_person' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:Open,In Progress,Closed',
        ]));

        return $issue;
    }

    public function destroy(Issue $issue)
    {
        $issue->delete();
        return response()->noContent();
    }
}
