<?php

namespace App\Http\Controllers;

use App\Models\AuditEngagement;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffInfoController extends Controller
{
    public function __construct(private readonly AuditLogger $auditLogger)
    {
    }

    public function index(Request $request)
    {
        $engagements = AuditEngagement::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('staff-info.index', compact('engagements'));
    }

    public function create()
    {
        return view('staff-info.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'entity_name' => ['required', 'string', 'max:255'],
            'activity' => ['required', 'string'],
            'working_notes' => ['nullable', 'string'],
            'evidence' => ['nullable', 'file', 'max:10240'],
            'status' => ['required', 'in:open,in-progress,closed'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        if ($request->hasFile('evidence')) {
            $validated['evidence_path'] = $request->file('evidence')->store('evidence', 'public');
        }

        $validated['created_by'] = $request->user()->id;

        AuditEngagement::create($validated);

        $this->auditLogger->log('audit_engagement_created', ['user_id' => $request->user()->id]);

        return redirect()->route('staff-info.index')->with('status', 'Audit engagement created successfully.');
    }

    public function edit(AuditEngagement $staff_info)
    {
        return view('staff-info.edit', ['engagement' => $staff_info]);
    }

    public function update(Request $request, AuditEngagement $staff_info)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'entity_name' => ['required', 'string', 'max:255'],
            'activity' => ['required', 'string'],
            'working_notes' => ['nullable', 'string'],
            'status' => ['required', 'in:open,in-progress,closed'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $staff_info->update($validated);

        $this->auditLogger->log('audit_engagement_updated', ['id' => $staff_info->id]);

        return redirect()->route('staff-info.index')->with('status', 'Audit engagement updated successfully.');
    }

    public function destroy(AuditEngagement $staff_info)
    {
        if ($staff_info->evidence_path) {
            Storage::disk('public')->delete($staff_info->evidence_path);
        }

        $staff_info->delete();

        $this->auditLogger->log('audit_engagement_deleted', ['id' => $staff_info->id]);

        return redirect()->route('staff-info.index')->with('status', 'Audit engagement deleted successfully.');
    }
}
