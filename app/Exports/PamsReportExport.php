<?php

namespace App\Exports;

use App\Models\OracleReportModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class PamsReportExport implements FromCollection
{
    public function collection(): Collection
    {
        return OracleReportModel::query()->limit(5000)->get();
    }
}
