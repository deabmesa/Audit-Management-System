<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
    }

    public function checkOut(Request $request): RedirectResponse
    {
        $this->attendanceService->checkOut($request->user());

        return back()->with('status', 'Checked out successfully.');
    }
}
