<?php

namespace App\Services\Reports;

use App\Models\ReportTemplate;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ReportQueryService
{
    public function execute(ReportTemplate $template, array $bindings = [], int $perPage = 20): LengthAwarePaginator
    {
        if (!preg_match('/^\s*select\b/i', $template->sql_query)) {
            throw new InvalidArgumentException('Only SELECT queries are allowed for reports.');
        }

        $connection = match ($template->database_source) {
            'external_pgsql' => 'external_pgsql',
            'external_oracle' => 'external_oracle',
            default => 'pgsql',
        };

        $cacheKey = sprintf(
            'report:%d:%s',
            $template->id,
            md5(json_encode($bindings))
        );

        $rows = Cache::tags(['reports'])->remember($cacheKey, now()->addMinutes(10), function () use ($connection, $template, $bindings) {
            return collect(DB::connection($connection)->select($template->sql_query, $bindings));
        });

        $page = request()->integer('page', 1);
        $items = $rows->forPage($page, $perPage)->values();

        return new LengthAwarePaginator($items, $rows->count(), $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }
}
