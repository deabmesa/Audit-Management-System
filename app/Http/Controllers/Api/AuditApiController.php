<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use Illuminate\Http\Request;

class AuditApiController extends Controller
{
    public function index()
    {
        return Audit::with('issues')->paginate(20);
    }

    public function store(Request $request)
    {
        $audit = Audit::create($request->validate([
            'title' => 'required|string|max:255',
            'business_unit' => 'required|string|max:255',
            'audit_owner' => 'required|string|max:255',
            'audit_date' => 'required|date',
            'status' => 'required|in:Planned,Ongoing,Completed',
        ]));

        return response()->json($audit, 201);
    }

    public function show(Audit $audit)
    {
        return $audit->load('issues');
    }

    public function update(Request $request, Audit $audit)
    {
        $audit->update($request->validate([
            'title' => 'sometimes|required|string|max:255',
            'business_unit' => 'sometimes|required|string|max:255',
            'audit_owner' => 'sometimes|required|string|max:255',
            'audit_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:Planned,Ongoing,Completed',
        ]));

        return $audit;
    }

    public function destroy(Audit $audit)
    {
        $audit->delete();
        return response()->noContent();
    }
}
