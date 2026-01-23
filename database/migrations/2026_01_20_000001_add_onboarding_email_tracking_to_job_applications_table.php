<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('job_applications', 'sieving_passed_email_sent_at')) {
                $table->timestamp('sieving_passed_email_sent_at')->nullable()->after('confirmation_email_sent_at');
            }

            if (! Schema::hasColumn('job_applications', 'candidate_credentials_sent_at')) {
                $table->timestamp('candidate_credentials_sent_at')->nullable()->after('sieving_passed_email_sent_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            if (Schema::hasColumn('job_applications', 'candidate_credentials_sent_at')) {
                $table->dropColumn('candidate_credentials_sent_at');
            }
            if (Schema::hasColumn('job_applications', 'sieving_passed_email_sent_at')) {
                $table->dropColumn('sieving_passed_email_sent_at');
            }
        });
    }
};

