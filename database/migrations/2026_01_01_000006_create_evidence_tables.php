<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('evidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finding_id')->constrained('findings')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('evidence_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evidence_id')->constrained('evidence')->cascadeOnDelete();
            $table->unsignedInteger('version');
            $table->string('file_path');
            $table->string('checksum_sha256', 64);
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
            $table->unique(['evidence_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidence_versions');
        Schema::dropIfExists('evidence');
    }
};
