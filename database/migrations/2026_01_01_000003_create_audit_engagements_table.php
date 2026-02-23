<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_engagements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('entity_name');
            $table->text('activity');
            $table->text('working_notes')->nullable();
            $table->string('evidence_path')->nullable();
            $table->enum('status', ['open', 'in-progress', 'closed'])->default('open');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['status', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_engagements');
    }
};
