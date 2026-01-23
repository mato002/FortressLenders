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
        // Add candidate_id if missing (from previous migration)
        if (!Schema::hasColumn('job_applications', 'candidate_id')) {
            DB::statement('
                ALTER TABLE job_applications 
                ADD COLUMN candidate_id BIGINT UNSIGNED NULL AFTER id
            ');
            
            try {
                DB::statement('
                    ALTER TABLE job_applications 
                    ADD CONSTRAINT job_applications_candidate_id_foreign 
                    FOREIGN KEY (candidate_id) REFERENCES candidates(id) 
                    ON DELETE SET NULL
                ');
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
        }
        
        // Add aptitude test columns if missing
        if (!Schema::hasColumn('job_applications', 'aptitude_test_score')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->integer('aptitude_test_score')->nullable()->after('status');
            });
        }
        
        if (!Schema::hasColumn('job_applications', 'aptitude_test_passed')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->boolean('aptitude_test_passed')->nullable()->after('aptitude_test_score');
            });
        }
        
        if (!Schema::hasColumn('job_applications', 'aptitude_test_completed_at')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->timestamp('aptitude_test_completed_at')->nullable()->after('aptitude_test_passed');
            });
        }
        
        // Add self interview columns if missing
        if (!Schema::hasColumn('job_applications', 'self_interview_score')) {
            Schema::table('job_applications', function (Blueprint $table) {
                // Try to add after aptitude_test_completed_at, or after the last column
                $afterColumn = Schema::hasColumn('job_applications', 'aptitude_test_completed_at') 
                    ? 'aptitude_test_completed_at' 
                    : null;
                
                if ($afterColumn) {
                    $table->integer('self_interview_score')->nullable()->after($afterColumn);
                } else {
                    $table->integer('self_interview_score')->nullable();
                }
            });
        }
        
        if (!Schema::hasColumn('job_applications', 'self_interview_passed')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $afterColumn = Schema::hasColumn('job_applications', 'self_interview_score') 
                    ? 'self_interview_score' 
                    : (Schema::hasColumn('job_applications', 'aptitude_test_completed_at') 
                        ? 'aptitude_test_completed_at' 
                        : null);
                
                if ($afterColumn) {
                    $table->boolean('self_interview_passed')->nullable()->after($afterColumn);
                } else {
                    $table->boolean('self_interview_passed')->nullable();
                }
            });
        }
        
        if (!Schema::hasColumn('job_applications', 'self_interview_completed_at')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $afterColumn = Schema::hasColumn('job_applications', 'self_interview_passed') 
                    ? 'self_interview_passed' 
                    : (Schema::hasColumn('job_applications', 'self_interview_score') 
                        ? 'self_interview_score' 
                        : null);
                
                if ($afterColumn) {
                    $table->timestamp('self_interview_completed_at')->nullable()->after($afterColumn);
                } else {
                    $table->timestamp('self_interview_completed_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop if columns exist
        if (Schema::hasColumn('job_applications', 'self_interview_completed_at')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->dropColumn('self_interview_completed_at');
            });
        }
        
        if (Schema::hasColumn('job_applications', 'self_interview_passed')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->dropColumn('self_interview_passed');
            });
        }
        
        if (Schema::hasColumn('job_applications', 'self_interview_score')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->dropColumn('self_interview_score');
            });
        }
        
        if (Schema::hasColumn('job_applications', 'aptitude_test_completed_at')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->dropColumn('aptitude_test_completed_at');
            });
        }
        
        if (Schema::hasColumn('job_applications', 'aptitude_test_passed')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->dropColumn('aptitude_test_passed');
            });
        }
        
        if (Schema::hasColumn('job_applications', 'aptitude_test_score')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->dropColumn('aptitude_test_score');
            });
        }
    }
};
