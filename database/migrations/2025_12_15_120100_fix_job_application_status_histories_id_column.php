<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure the `id` column is an auto-incrementing primary key.
        // This is mainly to fix environments where the table was created
        // without AUTO_INCREMENT, which causes "Field 'id' doesn't have a default value".
        if (Schema::hasTable('job_application_status_histories')) {
            DB::statement('ALTER TABLE job_application_status_histories MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't try to revert the AUTO_INCREMENT change automatically.
        // Leaving this empty is safe.
    }
};


