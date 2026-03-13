<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_finding_id')->constrained()->cascadeOnDelete();
            $table->string('recommendation_status');
            $table->date('due_date')->index();
            $table->text('follow_up_comments')->nullable();
            $table->enum('status', ['Open', 'Closed'])->default('Open')->index();
            $table->timestamps();
            $table->index(['audit_finding_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
