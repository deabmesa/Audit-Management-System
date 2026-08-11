<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_template_id')->constrained()->cascadeOnDelete();
            $table->string('role_name')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('can_view')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_permissions');
    }
};
