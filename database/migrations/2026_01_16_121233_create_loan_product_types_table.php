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
        Schema::create('loan_product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Fasta", "Malkia", "Kilimo"
            $table->decimal('min_loan_amount', 15, 2);
            $table->decimal('max_loan_amount', 15, 2);
            $table->string('service_charge_type'); // fixed_amount or percentage
            $table->decimal('service_charge_value', 10, 2); // Amount in KES or percentage
            $table->string('service_charge_period')->nullable(); // per_month, for_6weeks
            $table->integer('max_duration_weeks');
            $table->string('payment_frequency'); // weekly or monthly
            $table->string('target_clients'); // Trade & Service, Farming
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_product_types');
    }
};
