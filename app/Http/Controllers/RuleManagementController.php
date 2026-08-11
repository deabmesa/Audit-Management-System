<?php

namespace App\Http\Controllers;

use App\Models\ReportPermission;
use Illuminate\Http\Request;

class RuleManagementController extends Controller
{
    public function index()
    {
        return view('reports.rules', [
            'permissions' => ReportPermission::latest()->paginate(15),
            'roles' => ['Admin', 'Auditor', 'Auditee', 'Manager'],
        ]);
    }

    public function store(Request $request)
    {
        ReportPermission::create($request->validate([
            'report_template_id' => ['required', 'integer'],
            'role_name' => ['nullable', 'in:Admin,Auditor,Auditee,Manager'],
            'user_id' => ['nullable', 'integer'],
            'can_view' => ['required', 'boolean'],
        ]));

        return back()->with('status', 'Rule mapping saved.');
    }
}
