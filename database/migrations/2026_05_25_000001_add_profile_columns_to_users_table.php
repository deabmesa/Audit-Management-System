<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 100)->nullable()->unique()->after('name');
            }

            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 30)->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'branch')) {
                $table->string('branch', 255)->nullable()->after('phone');
            }

            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('branch');
            }

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('users', 'username') ? 'username' : null,
                Schema::hasColumn('users', 'phone')    ? 'phone'    : null,
                Schema::hasColumn('users', 'branch')   ? 'branch'   : null,
                Schema::hasColumn('users', 'is_active')? 'is_active': null,
            ]));
        });
    }
};
