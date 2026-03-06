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
        if (Schema::hasTable('team_members') && ! Schema::hasColumn('team_members', 'account_active')) {
            Schema::table('team_members', function (Blueprint $table) {
                $table->boolean('account_active')
                    ->default(true)
                    ->after('is_active')
                    ->comment('Controls whether this team member\'s portal login is allowed to operate');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('team_members') && Schema::hasColumn('team_members', 'account_active')) {
            Schema::table('team_members', function (Blueprint $table) {
                $table->dropColumn('account_active');
            });
        }
    }
};

