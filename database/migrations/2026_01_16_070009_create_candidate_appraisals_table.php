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
        Schema::create('candidate_appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['performance_review', 'hr_communication', 'warning']);
            $table->string('title');
            $table->text('content');
            $table->string('file_path')->nullable(); // For attached documents
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // HR user
            $table->date('review_date')->nullable(); // For performance reviews
            $table->enum('severity', ['low', 'medium', 'high'])->nullable(); // For warnings
            $table->boolean('is_acknowledged')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();
            
            $table->index(['candidate_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_appraisals');
    }
};
