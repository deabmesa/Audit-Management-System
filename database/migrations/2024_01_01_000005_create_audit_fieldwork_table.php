<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_fieldwork', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_engagement_id')->constrained()->cascadeOnDelete();
            $table->string('checklist_item');
            $table->string('evidence_path')->nullable();
            $table->text('working_paper')->nullable();
            $table->text('audit_notes')->nullable();
            $table->enum('status', ['Pending', 'Completed'])->default('Pending')->index();
            $table->timestamps();
            $table->index(['audit_engagement_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_fieldwork');
    }
};
