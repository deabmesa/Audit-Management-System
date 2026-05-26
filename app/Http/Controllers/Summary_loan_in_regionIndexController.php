<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Summary_loan_in_regionIndexController extends Controller
{
    public function index(Request $request, $data = [])
    {
        return view('summary_loan_in_region.index', $data);
    }
}