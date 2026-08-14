<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OracleReportingService
{
    /**
     * Read-only report fetcher. Uses SELECT-only SQL and Oracle connection.
     */
    public function financialSummary(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = DB::connection('oracle')
            ->table('FINANCIAL_REPORT_VIEW')
            ->select(['DEPARTMENT', 'REPORT_DATE', 'TOTAL_AMOUNT', 'TRANSACTION_COUNT']);

        if (! empty($filters['department'])) {
            $query->where('DEPARTMENT', $filters['department']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('REPORT_DATE', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('REPORT_DATE', '<=', $filters['date_to']);
        }

        return $query->orderByDesc('REPORT_DATE')->paginate($perPage);
    }

    public function transactionSummary(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = DB::connection('oracle')
            ->table('TRANSACTION_SUMMARY_VIEW')
            ->select(['TRANSACTION_DATE', 'DEPARTMENT', 'CATEGORY', 'TOTAL_VALUE']);

        foreach (['department' => 'DEPARTMENT', 'category' => 'CATEGORY'] as $filter => $column) {
            if (! empty($filters[$filter])) {
                $query->where($column, $filters[$filter]);
            }
        }

        return $query->orderByDesc('TRANSACTION_DATE')->paginate($perPage);
    }
}
