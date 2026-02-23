<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('audit_type');
            $table->string('department');
            $table->string('risk_category');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('Planned');
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('audit_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->restrictOnDelete();
            $table->timestamp('assigned_at');
            $table->timestamps();
            $table->unique(['audit_id', 'user_id']);
        });

        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('check_in_time');
            $table->timestamp('check_out_time')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
            $table->index(['user_id', 'status']);
        });

        Schema::create('audit_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->cascadeOnDelete();
            $table->text('objectives');
            $table->text('scope');
            $table->text('risk_assessment');
            $table->text('control_areas');
            $table->timestamps();
        });

        Schema::create('workpapers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->cascadeOnDelete();
            $table->text('procedure');
            $table->string('evidence_path')->nullable();
            $table->string('status')->default('Open');
            $table->foreignId('prepared_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('risk_level');
            $table->text('observation');
            $table->text('impact');
            $table->text('root_cause');
            $table->text('recommendation_text');
            $table->string('status')->default('Open');
            $table->timestamps();
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finding_id')->constrained()->cascadeOnDelete();
            $table->string('owner');
            $table->date('due_date');
            $table->string('implementation_status')->default('Open');
            $table->timestamps();
        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('attachable_type');
            $table->unsignedBigInteger('attachable_id');
            $table->string('path');
            $table->foreignId('uploaded_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->index(['attachable_type', 'attachable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('findings');
        Schema::dropIfExists('workpapers');
        Schema::dropIfExists('audit_plans');
        Schema::dropIfExists('attendance_logs');
        Schema::dropIfExists('audit_assignments');
        Schema::dropIfExists('audits');
    }
};
