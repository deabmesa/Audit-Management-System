<?php

namespace App\Http\Controllers;

use App\Models\CheckInRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'real_branch' => ['required', 'string', 'max:100'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        CheckInRecord::create([
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'ip_branch' => $request->header('X-IP-Branch', 'Unknown'),
            'real_branch' => $data['real_branch'],
            'reason' => $data['reason'],
            'checked_in_at' => Carbon::now(),
        ]);

        return back()->with('status', 'Checked in successfully.');
    }

    public function checkout(CheckInRecord $checkInRecord)
    {
        $checkInRecord->update([
            'checked_out_at' => now(),
        ]);

        return back()->with('status', 'Checked out successfully.');
    }
}
