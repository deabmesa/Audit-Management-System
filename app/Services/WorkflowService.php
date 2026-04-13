<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Approval;
use App\Models\Finding;
use App\Models\WorkflowStep;
use Illuminate\Support\Facades\Auth;

class WorkflowService
{
    public function transition(Finding $finding, string $action, ?string $remarks = null): void
    {
        $step = WorkflowStep::where('step_order', $finding->current_step_order)->firstOrFail();
        abort_unless(Auth::user()->hasRole($step->required_role), 403, 'Role not allowed for this step');

        Approval::create([
            'finding_id' => $finding->id,
            'workflow_step_id' => $step->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'remarks' => $remarks,
        ]);

        if ($action === 'approved') {
            $next = WorkflowStep::where('step_order', $step->step_order + 1)->first();
            $finding->update([
                'current_step_order' => $next?->step_order ?? $step->step_order,
                'status' => $next?->final_state ?? ($next ? 'review' : 'closed'),
            ]);
        } else {
            $finding->update(['status' => 'open']);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'workflow_'.$action,
            'entity_type' => Finding::class,
            'entity_id' => $finding->id,
            'metadata' => ['remarks' => $remarks],
        ]);
    }
}
