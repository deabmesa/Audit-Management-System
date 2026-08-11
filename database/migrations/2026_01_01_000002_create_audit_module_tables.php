<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->enum('module', ['Pre-Audit', 'Fieldwork', 'Audit Report']);
            $table->string('status');
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('audit_id')->constrained('audits')->cascadeOnDelete();
            $table->string('title');
            $table->string('status')->default('Pending');
            $table->foreignUuid('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('issues', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('audit_id')->constrained('audits')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('Open');
            $table->foreignUuid('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('attachments', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuidMorphs('attachable');
            $table->string('filename');
            $table->string('path');
            $table->foreignUuid('uploaded_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('issues');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('audits');
    }
};
