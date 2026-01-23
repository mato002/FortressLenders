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
        Schema::table('aptitude_test_questions', function (Blueprint $table) {
            $table->enum('question_type', ['multiple_choice', 'text', 'calculation'])->default('multiple_choice')->after('section');
            // Make options and correct_answer nullable for text/calculation questions
            $table->json('options')->nullable()->change();
            $table->string('correct_answer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aptitude_test_questions', function (Blueprint $table) {
            $table->dropColumn('question_type');
            // Revert to not nullable (but this might fail if there are null values)
            $table->json('options')->nullable(false)->change();
            $table->string('correct_answer')->nullable(false)->change();
        });
    }
};
