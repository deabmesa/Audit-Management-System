<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('title');
            $table->string('business_unit');
            $table->string('audit_owner');
            $table->date('audit_date');
            $table->enum('status', ['Planned', 'Ongoing', 'Completed'])->default('Planned');
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audits');
    }
};
