<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\FieldPermission;
use App\Models\Finding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FindingController extends Controller
{
    public function index(Request $request)
    {
        $query = Finding::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        return view('findings.index', ['findings' => $query->latest()->paginate(15)]);
    }

    public function create()
    {
        return view('findings.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'severity' => ['required', 'in:low,medium,high,critical'],
        ]);
        $data['status'] = 'open';
        $data['current_step_order'] = 1;
        $data['created_by'] = Auth::id();
        $finding = Finding::create($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'created',
            'entity_type' => Finding::class,
            'entity_id' => $finding->id,
            'metadata' => $finding->toArray(),
        ]);

        return redirect()->route('findings.index')->with('success', 'Finding created.');
    }

    public function show(Finding $finding)
    {
        return view('findings.show', compact('finding'));
    }

    public function edit(Finding $finding)
    {
        return view('findings.edit', compact('finding'));
    }

    public function update(Request $request, Finding $finding)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'severity' => ['required', 'in:low,medium,high,critical'],
        ]);

        $canEditStatus = FieldPermission::where('model', 'findings')->where('field', 'status')
            ->where('role_name', Auth::user()->getRoleNames()->first())->where('can_edit', true)->exists();
        if ($canEditStatus && $request->filled('status')) {
            $data['status'] = $request->status;
        }

        $finding->update($data);
        return redirect()->route('findings.show', $finding)->with('success', 'Updated successfully.');
    }

    public function destroy(Finding $finding)
    {
        $finding->delete();
        return redirect()->route('findings.index')->with('success', 'Deleted.');
    }
}
