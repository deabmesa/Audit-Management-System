<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->cascadeOnDelete();
            $table->string('title');
            $table->text('executive_summary')->nullable();
            $table->text('methodology')->nullable();
            $table->integer('total_findings')->default(0);
            $table->integer('critical_findings')->default(0);
            $table->integer('high_findings')->default(0);
            $table->integer('medium_findings')->default(0);
            $table->integer('low_findings')->default(0);
            $table->text('conclusion')->nullable();
            $table->string('generated_by');
            $table->enum('status', ['draft', 'review', 'approved', 'published'])->default('draft');
            $table->date('report_date');
            $table->string('file_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
            $table->index('audit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_reports');
    }
};
