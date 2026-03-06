<?php

namespace App\Http\Controllers;

use App\Models\ReportTemplate;
use App\Services\Reports\ReportQueryService;
use Illuminate\Http\Request;

class ReportTemplateController extends Controller
{
    public function __construct(private readonly ReportQueryService $reportQueryService)
    {
    }

    public function index()
    {
        return view('reports.index', [
            'templates' => ReportTemplate::latest()->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        ReportTemplate::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'database_source' => ['required', 'in:pgsql,external_pgsql,external_oracle'],
            'sql_query' => ['required', 'string'],
            'output_columns' => ['required', 'array'],
            'filters' => ['nullable', 'array'],
        ]) + ['is_active' => true]);

        return back()->with('status', 'Report template created.');
    }

    public function run(Request $request, ReportTemplate $reportTemplate)
    {
        $result = $this->reportQueryService->execute($reportTemplate, $request->except(['page']));

        return view('reports.run', [
            'template' => $reportTemplate,
            'result' => $result,
        ]);
    }
}
