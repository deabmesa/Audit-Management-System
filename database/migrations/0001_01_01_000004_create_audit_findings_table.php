<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->text('root_cause')->nullable();
            $table->text('recommendation')->nullable();
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed', 'verified'])->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('evidence_owner')->nullable()->constrained('users')->cascadeOnDelete();
            $table->date('due_date')->nullable();
            $table->date('resolved_date')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->string('evidence_url')->nullable();
            $table->integer('days_open')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
            $table->index('severity');
            $table->index('assigned_to');
            $table->index('audit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_findings');
    }
};
