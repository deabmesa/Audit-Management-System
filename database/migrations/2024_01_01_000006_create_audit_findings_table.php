<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_engagement_id')->constrained()->cascadeOnDelete();
            $table->string('title')->index();
            $table->enum('risk_rating', ['Low', 'Medium', 'High'])->index();
            $table->text('root_cause');
            $table->text('recommendation');
            $table->text('management_response')->nullable();
            $table->timestamps();
            $table->index(['audit_engagement_id', 'risk_rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_findings');
    }
};
