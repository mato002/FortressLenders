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
        Schema::table('products', function (Blueprint $table) {
            // Loan amount range
            $table->decimal('min_loan_amount', 15, 2)->nullable()->after('description');
            $table->decimal('max_loan_amount', 15, 2)->nullable()->after('min_loan_amount');
            
            // Interest rate (can be a range or fixed rate)
            $table->decimal('interest_rate_min', 5, 2)->nullable()->after('max_loan_amount');
            $table->decimal('interest_rate_max', 5, 2)->nullable()->after('interest_rate_min');
            $table->string('interest_rate_type')->nullable()->after('interest_rate_max')->comment('e.g., "per_month", "per_year", "flat"');
            
            // Repayment period
            $table->integer('repayment_period_min')->nullable()->after('interest_rate_type')->comment('Minimum repayment period in months');
            $table->integer('repayment_period_max')->nullable()->after('repayment_period_min')->comment('Maximum repayment period in months');
            
            // Repayment information
            $table->text('repayment_methods')->nullable()->after('repayment_period_max')->comment('Comma-separated or JSON of repayment methods');
            $table->text('repayment_schedule_info')->nullable()->after('repayment_methods')->comment('Information about repayment schedule');
            
            // Eligibility and requirements
            $table->text('eligibility_criteria')->nullable()->after('repayment_schedule_info');
            $table->text('required_documents')->nullable()->after('eligibility_criteria');
            
            // Additional information
            $table->string('processing_time')->nullable()->after('required_documents')->comment('e.g., "24-48 hours", "3-5 business days"');
            $table->text('fees_and_charges')->nullable()->after('processing_time');
            $table->text('additional_info')->nullable()->after('fees_and_charges')->comment('Any other important information');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'min_loan_amount',
                'max_loan_amount',
                'interest_rate_min',
                'interest_rate_max',
                'interest_rate_type',
                'repayment_period_min',
                'repayment_period_max',
                'repayment_methods',
                'repayment_schedule_info',
                'eligibility_criteria',
                'required_documents',
                'processing_time',
                'fees_and_charges',
                'additional_info',
            ]);
        });
    }
};
