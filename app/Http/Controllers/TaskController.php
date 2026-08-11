<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        return view('tasks.list', ['tasks' => Task::with(['assignedTo', 'audit'])->latest()->get()]);
    }

    public function assignment(): View
    {
        return view('tasks.assignment', ['tasks' => Task::with('assignedTo')->get()]);
    }

    public function status(): View
    {
        return view('tasks.status', ['tasks' => Task::select(['title', 'status', 'due_date'])->get()]);
    }
}
