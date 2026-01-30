<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration ensures the status column in job_applications table
     * includes all required status values, especially the sieving-related ones
     * that may be missing in production.
     */
    public function up(): void
    {
        // Check if the table exists
        if (!Schema::hasTable('job_applications')) {
            return;
        }

        // This migration relies on MySQL's ENUM + MODIFY syntax, which is not
        // supported on SQLite (used by our automated tests). Skip on SQLite
        // to avoid breaking the test suite.
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        // Use raw SQL to modify enum to include all required status values
        // This is safe to run multiple times as MySQL will handle it gracefully
        DB::statement("ALTER TABLE job_applications MODIFY COLUMN status ENUM(
            'pending', 
            'sieving_passed', 
            'sieving_rejected',
            'stage_2_passed',
            'reviewed', 
            'shortlisted', 
            'rejected', 
            'interview_scheduled', 
            'interview_passed', 
            'interview_failed', 
            'second_interview', 
            'written_test', 
            'case_study', 
            'hired'
        ) DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values (without sieving statuses)
        if (Schema::hasTable('job_applications') && Schema::getConnection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE job_applications MODIFY COLUMN status ENUM(
                'pending', 
                'reviewed', 
                'shortlisted', 
                'rejected', 
                'interview_scheduled', 
                'interview_passed', 
                'interview_failed', 
                'second_interview', 
                'written_test', 
                'case_study', 
                'hired'
            ) DEFAULT 'pending'");
        }
    }
};
