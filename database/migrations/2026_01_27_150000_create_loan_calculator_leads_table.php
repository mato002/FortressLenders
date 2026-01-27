<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_calculator_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('whatsapp_number');
            $table->decimal('loan_amount', 15, 2);
            $table->unsignedInteger('loan_duration_value');
            $table->string('loan_duration_unit')->default('weeks'); // weeks or months
            $table->decimal('service_charge', 15, 2)->default(0);
            $table->decimal('total_repayment', 15, 2);
            $table->string('payment_frequency')->nullable(); // weekly / monthly
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_calculator_leads');
    }
};

