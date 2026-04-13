<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Approval;
use App\Models\Finding;
use App\Models\WorkflowStep;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{
    public function __construct(private readonly WorkflowService $workflowService)
    {
    }

    public function designer()
    {
        return view('workflows.designer', ['steps' => WorkflowStep::orderBy('step_order')->get()]);
    }

    public function saveDesigner(Request $request)
    {
        $request->validate(['steps' => ['required', 'array']]);
        WorkflowStep::query()->delete();
        foreach ($request->steps as $idx => $step) {
            WorkflowStep::create([
                'name' => $step['name'],
                'required_role' => $step['required_role'],
                'step_order' => $idx + 1,
                'final_state' => $step['final_state'] ?? null,
            ]);
        }
        return back()->with('success', 'Workflow updated.');
    }

    public function approve(Request $request, Finding $finding)
    {
        $this->workflowService->transition($finding, 'approved', $request->remarks);
        return back()->with('success', 'Approved');
    }

    public function reject(Request $request, Finding $finding)
    {
        $this->workflowService->transition($finding, 'rejected', $request->remarks);
        return back()->with('success', 'Rejected');
    }

    public function timeline(Finding $finding)
    {
        $timeline = Approval::with('step')->where('finding_id', $finding->id)->latest()->get();
        return view('workflows.timeline', compact('finding', 'timeline'));
    }
}
