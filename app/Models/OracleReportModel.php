<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OracleReportModel extends Model
{
    protected $connection = 'oracle';

    protected $table = 'PAMS_REPORT_VW';

    protected $primaryKey = 'REPORT_ID';

    public $timestamps = false;

    protected $guarded = [];

    public function save(array $options = [])
    {
        throw new \RuntimeException('Oracle reporting connection is read-only.');
    }

    public function delete()
    {
        throw new \RuntimeException('Oracle reporting connection is read-only.');
    }
}
