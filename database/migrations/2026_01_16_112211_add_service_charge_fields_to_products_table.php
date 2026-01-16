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
            // Service charge fields
            $table->string('service_charge_type')->nullable()->after('interest_rate_type')->comment('fixed_amount or percentage');
            $table->decimal('service_charge_value', 10, 2)->nullable()->after('service_charge_type')->comment('Fixed amount in KES or percentage value');
            $table->string('service_charge_period')->nullable()->after('service_charge_value')->comment('per_month, for_6weeks, etc.');
            
            // Payment frequency
            $table->string('payment_frequency')->nullable()->after('service_charge_period')->comment('weekly or monthly');
            
            // Duration in weeks (in addition to months)
            $table->integer('max_duration_weeks')->nullable()->after('repayment_period_max')->comment('Maximum duration in weeks');
            
            // Target clients
            $table->string('target_clients')->nullable()->after('max_duration_weeks')->comment('e.g., Trade & Service, Farming');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'service_charge_type',
                'service_charge_value',
                'service_charge_period',
                'payment_frequency',
                'max_duration_weeks',
                'target_clients',
            ]);
        });
    }
};
