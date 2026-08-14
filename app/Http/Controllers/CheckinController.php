<?php

namespace App\Http\Controllers;

use App\Models\CheckinLog;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function __construct(private readonly AuditLogger $auditLogger)
    {
    }

    public function showForm()
    {
        return view('auth.checkin');
    }

    public function checkin(Request $request)
    {
        $user = $request->user();

        $activeSessionExists = CheckinLog::where('user_id', $user->id)
            ->where('is_active', true)
            ->exists();

        if ($activeSessionExists) {
            return back()->withErrors([
                'checkin' => 'Active session exists. Please check-out previous session first.',
            ]);
        }

        CheckinLog::create([
            'user_id' => $user->id,
            'username' => $user->email,
            'ip_address' => $request->ip(),
            'machine_name' => gethostbyaddr($request->ip()) ?: null,
            'checked_in_at' => now(),
            'session_id' => $request->session()->getId(),
            'is_active' => true,
        ]);

        session(['checked_in' => true]);

        $this->auditLogger->log('checkin', ['user_id' => $user->id]);

        return redirect()->route('dashboard');
    }

    public function checkout(Request $request)
    {
        $user = $request->user();

        CheckinLog::where('user_id', $user->id)
            ->where('session_id', $request->session()->getId())
            ->where('is_active', true)
            ->update([
                'checked_out_at' => now(),
                'is_active' => false,
            ]);

        session()->forget('checked_in');

        $this->auditLogger->log('checkout', ['user_id' => $user->id]);

        return back()->with('status', 'Checked out successfully.');
    }
}
