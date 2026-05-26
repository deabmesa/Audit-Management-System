<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Contact_listIndexController extends Controller
{
    public function index(Request $request, $data = [])
    {
        return view('contact_list.index', $data);
    }
}