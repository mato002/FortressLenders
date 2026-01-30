<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Loan application timeline and SLA tracking
        Schema::table('loan_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('loan_applications', 'sla_due_date')) {
                $table->timestamp('sla_due_date')->nullable()->after('status');
                $table->integer('sla_days')->default(5)->after('sla_due_date');
                $table->boolean('sla_breached')->default(false)->after('sla_days');
                $table->timestamp('approved_at')->nullable()->after('sla_breached');
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
                $table->timestamp('disbursed_at')->nullable()->after('rejected_at');
                $table->unsignedBigInteger('document_count')->default(0)->after('disbursed_at');
                $table->index(['status', 'sla_due_date']);
                $table->index('sla_breached');
            }
        });

        // Loan application documents/checklist
        Schema::create('loan_application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')->constrained('loan_applications')->onDelete('cascade');
            $table->string('document_type'); // ID, proof_of_income, bank_statement, etc
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index(['loan_application_id', 'verification_status'], 'idx_loan_app_doc_verification');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_application_documents');
        
        Schema::table('loan_applications', function (Blueprint $table) {
            $table->dropColumn(['sla_due_date', 'sla_days', 'sla_breached', 'approved_at', 'rejected_at', 'disbursed_at', 'document_count']);
        });
    }
};
