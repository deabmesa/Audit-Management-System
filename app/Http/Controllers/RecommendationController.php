<?php

namespace App\Http\Controllers;

use App\Models\Finding;
use App\Models\Recommendation;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index()
    {
        return view('pams.recommendations.index', [
            'recommendations' => Recommendation::with('finding')->paginate(15),
            'findings' => Finding::orderBy('title')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'finding_id' => ['required', 'exists:findings,id'],
            'owner' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'date'],
            'implementation_status' => ['required', 'in:Open,In Progress,Closed'],
        ]);

        Recommendation::create($validated);

        return back()->with('status', 'Recommendation created.');
    }
}
