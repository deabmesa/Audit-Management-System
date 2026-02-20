<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Notifications\NewAuditCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AuditController extends Controller
{
    public function index()
    {
        $audits = Audit::latest()->paginate(10);
        return view('audits.index', compact('audits'));
    }

    public function create()
    {
        return view('audits.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'business_unit' => 'required|string|max:255',
            'audit_owner' => 'required|string|max:255',
            'audit_date' => 'required|date',
            'status' => 'required|in:Planned,Ongoing,Completed',
        ]);

        $audit = Audit::create($validated);

        Notification::send(auth()->user(), new NewAuditCreatedNotification($audit));

        return redirect()->route('audits.show', $audit)->with('success', 'Audit created.');
    }

    public function show(Audit $audit)
    {
        $audit->load(['issues', 'attachments']);
        return view('audits.show', compact('audit'));
    }

    public function edit(Audit $audit)
    {
        return view('audits.edit', compact('audit'));
    }

    public function update(Request $request, Audit $audit)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'business_unit' => 'required|string|max:255',
            'audit_owner' => 'required|string|max:255',
            'audit_date' => 'required|date',
            'status' => 'required|in:Planned,Ongoing,Completed',
        ]);

        $audit->update($validated);
        return redirect()->route('audits.show', $audit)->with('success', 'Audit updated.');
    }

    public function destroy(Audit $audit)
    {
        $audit->delete();
        return redirect()->route('audits.index')->with('success', 'Audit deleted.');
    }
}
