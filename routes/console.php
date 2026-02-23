<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('ams:health', function () {
    $this->info('Audit Management System is healthy.');
});
