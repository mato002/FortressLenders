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
        Schema::table('self_interview_questions', function (Blueprint $table) {
            $table->enum('question_type', ['multiple_choice', 'text', 'calculation'])->default('multiple_choice')->after('question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('self_interview_questions', function (Blueprint $table) {
            $table->dropColumn('question_type');
        });
    }
};
