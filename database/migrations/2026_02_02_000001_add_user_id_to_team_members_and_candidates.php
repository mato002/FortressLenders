<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Links team members and candidates to users (employees).
     */
    public function up(): void
    {
        if (!Schema::hasColumn('team_members', 'user_id')) {
            Schema::table('team_members', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('candidates', 'user_id')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
};
