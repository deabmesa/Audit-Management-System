<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceIndexController extends Controller
{
    public function index(Request $request, $data = [])
    {
        return view('attendance.index', $data);
    }
}