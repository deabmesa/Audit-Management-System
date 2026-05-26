<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Rotate_locationIndexController extends Controller
{
    public function index(Request $request, $data = [])
    {
        return view('rotate_location.index', $data);
    }
}