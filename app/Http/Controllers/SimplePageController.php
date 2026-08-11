<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Response;

class SimplePageController extends Controller
{
    public function export(string $format): Response
    {
        $rows = Issue::with('responsible')->get()->map(fn ($issue) => [
            $issue->title,
            $issue->status,
            optional($issue->responsible)->name,
            optional($issue->due_date)->toDateString(),
        ]);

        $csv = "Title,Status,Responsible,Due Date\n".$rows->map(fn ($row) => implode(',', $row))->implode("\n");

        return response($csv)
            ->header('Content-Type', $format === 'pdf' ? 'application/pdf' : 'text/csv')
            ->header('Content-Disposition', "attachment; filename=issues.{$format}");
    }
}
