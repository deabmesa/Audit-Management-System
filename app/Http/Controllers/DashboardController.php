<?php

namespace App\Http\Controllers;

use App\Models\CheckInRecord;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'history' => CheckInRecord::query()
                ->latest('checked_in_at')
                ->paginate(10),
            'branches' => ['HQ', 'North Region', 'South Region', 'East Region', 'West Region'],
        ]);
    }
}
