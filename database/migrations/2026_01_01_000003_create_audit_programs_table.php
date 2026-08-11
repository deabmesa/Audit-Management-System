<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('scope');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('owner_id');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_programs');
    }
};
