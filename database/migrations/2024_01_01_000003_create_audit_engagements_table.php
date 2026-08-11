<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_engagements', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->text('scope');
            $table->enum('risk_level', ['Low', 'Medium', 'High'])->index();
            $table->date('planned_start_date');
            $table->date('planned_end_date');
            $table->enum('status', ['Planned', 'In Progress', 'Completed'])->default('Planned')->index();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->index(['status', 'risk_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_engagements');
    }
};
