<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStaffRequest;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with('user')->paginate(15);

        return view('staff.index', compact('staff'));
    }

    public function store(StoreStaffRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('ChangeMe123!'),
        ]);

        Staff::create([
            'user_id' => $user->id,
            'department' => $request->department,
            'position' => $request->position,
            'status' => $request->status,
        ]);

        return back()->with('status', 'Staff created successfully.');
    }
}
