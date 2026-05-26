<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Person_inforIndexController extends Controller
{
    public function index(Request $request, $data = [])
    {
        return view('person_infor.index', $data);
    }
}