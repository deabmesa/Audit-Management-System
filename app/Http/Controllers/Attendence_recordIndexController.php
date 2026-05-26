<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Attendence_recordIndexController extends Controller
{
    public function index(Request $request, $data = [])
    {
        return view('attendence_record.index', $data);
    }
}