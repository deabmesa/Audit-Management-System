<?php

namespace App\Http\Controllers;

use App\Models\AuditFinding;
use App\Models\AuditProgram;
use Illuminate\Http\Request;

class AuditManagementController extends Controller
{
    public function index()
    {
        return view('audit.index', [
            'programs' => AuditProgram::latest()->paginate(10),
            'findings' => AuditFinding::latest()->paginate(10),
        ]);
    }

    public function storeProgram(Request $request)
    {
        AuditProgram::create($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'scope' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'string'],
        ]) + ['owner_id' => auth()->id()]);

        return back()->with('status', 'Audit program created.');
    }
}
