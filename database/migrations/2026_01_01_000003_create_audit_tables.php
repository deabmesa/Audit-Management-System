<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('category')->index();
            $table->string('status')->index();
            $table->timestamp('planned_start_at');
            $table->timestamp('planned_end_at');
            $table->uuid('owner_id');
            $table->foreign('owner_id')->references('id')->on('users')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('audit_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('audit_id')->unique();
            $table->text('planning_notes')->nullable();
            $table->jsonb('risk_assessment')->nullable();
            $table->text('audit_program')->nullable();
            $table->jsonb('document_requests')->nullable();
            $table->foreign('audit_id')->references('id')->on('audits')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('audit_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('audit_id');
            $table->uuid('assigned_to')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->index();
            $table->date('due_date')->nullable()->index();
            $table->foreign('audit_id')->references('id')->on('audits')->cascadeOnDelete();
            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('audit_findings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('audit_id');
            $table->string('title');
            $table->text('details');
            $table->string('severity')->index();
            $table->string('status')->index();
            $table->date('due_date')->nullable()->index();
            $table->timestamp('resolved_at')->nullable();
            $table->foreign('audit_id')->references('id')->on('audits')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('audit_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('audit_id');
            $table->string('type')->index();
            $table->longText('content')->nullable();
            $table->string('status')->index();
            $table->timestamp('published_at')->nullable();
            $table->foreign('audit_id')->references('id')->on('audits')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('finding_id')->unique();
            $table->uuid('owner_id')->nullable();
            $table->text('recommendation');
            $table->string('status')->index();
            $table->date('target_date')->nullable()->index();
            $table->foreign('finding_id')->references('id')->on('audit_findings')->cascadeOnDelete();
            $table->foreign('owner_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('attachable_type');
            $table->uuid('attachable_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->uuid('uploaded_by');
            $table->foreign('uploaded_by')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['attachable_type', 'attachable_id']);
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('action');
            $table->string('entity_type')->nullable();
            $table->uuid('entity_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->jsonb('metadata')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['entity_type', 'entity_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('audit_reports');
        Schema::dropIfExists('audit_findings');
        Schema::dropIfExists('audit_tasks');
        Schema::dropIfExists('audit_plans');
        Schema::dropIfExists('audits');
    }
};
