<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Rotate_locationIndexService
{
    public function getData($request)
    {
        return [
            'data' => DB::select("SELECT 1 as sample")
        ];
    }
}