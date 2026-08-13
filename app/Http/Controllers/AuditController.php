<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
        $audits = Audit::with(['owner'])->latest()->paginate(15);
        return view('audits.index', compact('audits'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'planned_start_at' => 'required|date',
            'planned_end_at' => 'required|date|after_or_equal:planned_start_at',
            'owner_id' => 'required|exists:users,id',
        ]);

        Audit::create($validated + ['status' => 'planned']);

        return back()->with('status', 'Audit created.');
    }
}
