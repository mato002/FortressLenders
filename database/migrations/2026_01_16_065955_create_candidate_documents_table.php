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
        Schema::create('candidate_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->string('document_type'); // offer_letter, contract, id, kra, sha, filled_offer_letter, filled_contract
            $table->string('file_path');
            $table->string('original_filename')->nullable();
            $table->string('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null'); // HR user who uploaded
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            $table->index(['candidate_id', 'document_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_documents');
    }
};
