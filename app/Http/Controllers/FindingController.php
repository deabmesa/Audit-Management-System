<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFindingRequest;
use App\Models\Audit;
use App\Models\Finding;

class FindingController extends Controller
{
    public function index()
    {
        return view('pams.findings.index', [
            'findings' => Finding::with('audit')->paginate(15),
            'audits' => Audit::orderBy('title')->get(),
        ]);
    }

    public function store(StoreFindingRequest $request)
    {
        Finding::create($request->validated());

        return back()->with('status', 'Finding recorded.');
    }
}
