<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\AuditPlan;
use Illuminate\Http\Request;

class AuditPlanController extends Controller
{
    public function index()
    {
        return view('pams.plans.index', [
            'plans' => AuditPlan::with('audit')->paginate(15),
            'audits' => Audit::orderBy('title')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'audit_id' => ['required', 'exists:audits,id'],
            'objectives' => ['required', 'string'],
            'scope' => ['required', 'string'],
            'risk_assessment' => ['required', 'string'],
            'control_areas' => ['required', 'string'],
        ]);

        AuditPlan::create($validated);

        return back()->with('status', 'Audit plan created.');
    }
}
