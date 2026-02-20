<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function create(): View
    {
        return view('users.create');
    }

    public function index(): View
    {
        return view('users.manage', ['users' => User::orderBy('name')->get()]);
    }
}
