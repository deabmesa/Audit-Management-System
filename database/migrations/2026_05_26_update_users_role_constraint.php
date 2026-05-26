<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the old check constraint
            $table->dropCheck('users_role_check');
        });

        Schema::table('users', function (Blueprint $table) {
            // Create new check constraint allowing 'admin' and 'user'
            $table->check("(role IN ('admin', 'user'))");
        });

        // Update any NULL roles to 'user'
        DB::table('users')->whereNull('role')->update(['role' => 'user']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the new check constraint
            $table->dropCheck('users_role_check');
        });

        Schema::table('users', function (Blueprint $table) {
            // Restore the old constraint (if needed)
            $table->check("(role = 'admin')");
        });
    }
};
