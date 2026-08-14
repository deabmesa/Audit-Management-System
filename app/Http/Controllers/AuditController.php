<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuditRequest;
use App\Models\Audit;
use App\Models\AuditAssignment;
use App\Models\User;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $audits = Audit::query()
            ->when($request->department, fn ($q, $v) => $q->where('department', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->paginate(15);

        $auditors = User::whereHas('roles', fn ($q) => $q->where('name', 'Auditor'))->get();

        return view('audits.index', compact('audits', 'auditors'));
    }

    public function store(StoreAuditRequest $request)
    {
        Audit::create($request->validated() + ['created_by' => $request->user()->id]);

        return back()->with('status', 'Audit registered successfully.');
    }

    public function assign(Request $request, Audit $audit)
    {
        $request->validate(['user_id' => ['required', 'exists:users,id']]);

        AuditAssignment::updateOrCreate(
            ['audit_id' => $audit->id, 'user_id' => $request->user_id],
            ['assigned_by' => $request->user()->id, 'assigned_at' => now()]
        );

        return back()->with('status', 'Auditor assigned.');
    }
}
