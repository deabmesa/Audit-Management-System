<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Summary_loan_in_regionIndexService
{
    public function getData($request)
    {
        return [
            'data' => DB::select("SELECT 1 as sample")
        ];
    }
}