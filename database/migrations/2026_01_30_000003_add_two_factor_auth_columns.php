<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Two-factor authentication for users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false)->after('email_verified_at');
                $table->text('two_factor_secret')->nullable()->after('two_factor_enabled');
                $table->text('two_factor_backup_codes')->nullable()->after('two_factor_secret');
                $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_backup_codes');
            }
        });

        // Two-factor authentication for candidates
        Schema::table('candidates', function (Blueprint $table) {
            if (!Schema::hasColumn('candidates', 'two_factor_enabled')) {
                $table->boolean('two_factor_enabled')->default(false)->after('email_verified_at');
                $table->text('two_factor_secret')->nullable()->after('two_factor_enabled');
                $table->text('two_factor_backup_codes')->nullable()->after('two_factor_secret');
                $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_backup_codes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['two_factor_enabled', 'two_factor_secret', 'two_factor_backup_codes', 'two_factor_confirmed_at']);
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn(['two_factor_enabled', 'two_factor_secret', 'two_factor_backup_codes', 'two_factor_confirmed_at']);
        });
    }
};
