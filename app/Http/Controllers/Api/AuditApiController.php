<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use Illuminate\Http\Request;

class AuditApiController extends Controller
{
    public function index()
    {
        return Audit::with(['owner', 'findings', 'reports'])->paginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'planned_start_at' => 'required|date',
            'planned_end_at' => 'required|date|after_or_equal:planned_start_at',
            'owner_id' => 'required|exists:users,id',
        ]);

        return Audit::create($validated + ['status' => 'planned']);
    }
}
