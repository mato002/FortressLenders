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
        // Check if candidate_id column already exists
        if (!Schema::hasColumn('job_applications', 'candidate_id')) {
            // Use raw SQL to safely add the column
            DB::statement('
                ALTER TABLE job_applications 
                ADD COLUMN candidate_id BIGINT UNSIGNED NULL AFTER id
            ');
            
            // Add foreign key constraint
            try {
                DB::statement('
                    ALTER TABLE job_applications 
                    ADD CONSTRAINT job_applications_candidate_id_foreign 
                    FOREIGN KEY (candidate_id) REFERENCES candidates(id) 
                    ON DELETE SET NULL
                ');
            } catch (\Exception $e) {
                // Foreign key might already exist or there's an issue, log it
                \Log::warning('Could not add foreign key for candidate_id: ' . $e->getMessage());
            }
            
            // Optionally drop user_id if it exists (but don't fail if it doesn't)
            if (Schema::hasColumn('job_applications', 'user_id')) {
                try {
                    // Try to drop foreign key first
                    $foreignKeys = DB::select("
                        SELECT CONSTRAINT_NAME 
                        FROM information_schema.KEY_COLUMN_USAGE 
                        WHERE TABLE_SCHEMA = DATABASE() 
                        AND TABLE_NAME = 'job_applications' 
                        AND COLUMN_NAME = 'user_id' 
                        AND REFERENCED_TABLE_NAME IS NOT NULL
                    ");
                    
                    foreach ($foreignKeys as $fk) {
                        DB::statement("ALTER TABLE job_applications DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                    }
                } catch (\Exception $e) {
                    // Continue even if foreign key drop fails
                }
                
                try {
                    Schema::table('job_applications', function (Blueprint $table) {
                        $table->dropColumn('user_id');
                    });
                } catch (\Exception $e) {
                    // Column might not exist or already dropped
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop if column exists
        if (Schema::hasColumn('job_applications', 'candidate_id')) {
            Schema::table('job_applications', function (Blueprint $table) {
                try {
                    $table->dropForeign(['job_applications_candidate_id_foreign']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
                $table->dropColumn('candidate_id');
            });
        }
    }
};
