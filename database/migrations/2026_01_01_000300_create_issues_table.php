<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->foreignUuid('audit_id')->constrained('audits')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('risk_level');
            $table->text('recommendation');
            $table->date('due_date');
            $table->string('responsible_person');
            $table->enum('status', ['Open', 'In Progress', 'Closed'])->default('Open');
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
