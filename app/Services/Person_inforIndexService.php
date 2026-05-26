<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Person_inforIndexService
{
    public function getData($request)
    {
        return [
            'data' => DB::select("SELECT 1 as sample")
        ];
    }
}