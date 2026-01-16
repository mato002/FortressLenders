<?php

namespace Database\Seeders;

use App\Models\LoanProductType;
use Illuminate\Database\Seeder;

class LoanProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Trade & Service Products
            ['name' => 'Fasta', 'min_loan_amount' => 3000, 'max_loan_amount' => 5000, 'service_charge_type' => 'fixed_amount', 'service_charge_value' => 1200, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 4, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 1],
            ['name' => 'Fasta Fasta', 'min_loan_amount' => 6000, 'max_loan_amount' => 10000, 'service_charge_type' => 'fixed_amount', 'service_charge_value' => 2000, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 8, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 2],
            ['name' => 'Fasta Fasta Special', 'min_loan_amount' => 6000, 'max_loan_amount' => 10000, 'service_charge_type' => 'fixed_amount', 'service_charge_value' => 3000, 'service_charge_period' => 'for_6weeks', 'max_duration_weeks' => 6, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 3],
            ['name' => 'Malkia', 'min_loan_amount' => 11000, 'max_loan_amount' => 15000, 'service_charge_type' => 'fixed_amount', 'service_charge_value' => 3000, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 8, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 4],
            ['name' => 'Malkia Special', 'min_loan_amount' => 11000, 'max_loan_amount' => 15000, 'service_charge_type' => 'fixed_amount', 'service_charge_value' => 4500, 'service_charge_period' => 'for_6weeks', 'max_duration_weeks' => 6, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 5],
            ['name' => 'Imara', 'min_loan_amount' => 16000, 'max_loan_amount' => 20000, 'service_charge_type' => 'fixed_amount', 'service_charge_value' => 4000, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 12, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 6],
            ['name' => 'Imara Special', 'min_loan_amount' => 16000, 'max_loan_amount' => 20000, 'service_charge_type' => 'fixed_amount', 'service_charge_value' => 6000, 'service_charge_period' => 'for_6weeks', 'max_duration_weeks' => 6, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 7],
            ['name' => 'Pepea', 'min_loan_amount' => 21000, 'max_loan_amount' => 25000, 'service_charge_type' => 'fixed_amount', 'service_charge_value' => 5000, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 12, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 8],
            ['name' => 'Pepea Special', 'min_loan_amount' => 21000, 'max_loan_amount' => 25000, 'service_charge_type' => 'fixed_amount', 'service_charge_value' => 7500, 'service_charge_period' => 'for_6weeks', 'max_duration_weeks' => 6, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 9],
            ['name' => 'Vuka', 'min_loan_amount' => 26000, 'max_loan_amount' => 30000, 'service_charge_type' => 'percentage', 'service_charge_value' => 20, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 16, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 10],
            ['name' => 'Vuka Special', 'min_loan_amount' => 26000, 'max_loan_amount' => 30000, 'service_charge_type' => 'percentage', 'service_charge_value' => 20, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 6, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 11],
            ['name' => 'Mwangaza', 'min_loan_amount' => 31000, 'max_loan_amount' => 35000, 'service_charge_type' => 'percentage', 'service_charge_value' => 20, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 12, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 12],
            ['name' => 'Almasi', 'min_loan_amount' => 36000, 'max_loan_amount' => 40000, 'service_charge_type' => 'percentage', 'service_charge_value' => 20, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 12, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 13],
            ['name' => 'Shaba', 'min_loan_amount' => 41000, 'max_loan_amount' => 45000, 'service_charge_type' => 'percentage', 'service_charge_value' => 20, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 12, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 14],
            ['name' => 'Kilele', 'min_loan_amount' => 46000, 'max_loan_amount' => 50000, 'service_charge_type' => 'percentage', 'service_charge_value' => 20, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 12, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 15],
            ['name' => 'Dhahabu', 'min_loan_amount' => 51000, 'max_loan_amount' => 200000, 'service_charge_type' => 'percentage', 'service_charge_value' => 20, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 12, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 16],
            ['name' => 'Dhahabu Special', 'min_loan_amount' => 51000, 'max_loan_amount' => 200000, 'service_charge_type' => 'percentage', 'service_charge_value' => 20, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 12, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 17],
            ['name' => 'Kuza', 'min_loan_amount' => 5000, 'max_loan_amount' => 6000, 'service_charge_type' => 'percentage', 'service_charge_value' => 28, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 5, 'payment_frequency' => 'weekly', 'target_clients' => 'Trade & Service', 'display_order' => 18],
            // Farming Products
            ['name' => 'Kilimo', 'min_loan_amount' => 5000, 'max_loan_amount' => 15000, 'service_charge_type' => 'percentage', 'service_charge_value' => 25, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 4, 'payment_frequency' => 'monthly', 'target_clients' => 'Farming', 'display_order' => 19],
            ['name' => 'Kilimo Advance', 'min_loan_amount' => 16000, 'max_loan_amount' => 20000, 'service_charge_type' => 'percentage', 'service_charge_value' => 25, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 4, 'payment_frequency' => 'monthly', 'target_clients' => 'Farming', 'display_order' => 20],
            ['name' => 'Mavuno', 'min_loan_amount' => 21000, 'max_loan_amount' => 30000, 'service_charge_type' => 'percentage', 'service_charge_value' => 25, 'service_charge_period' => 'per_month', 'max_duration_weeks' => 4, 'payment_frequency' => 'monthly', 'target_clients' => 'Farming', 'display_order' => 21],
        ];

        foreach ($products as $product) {
            LoanProductType::updateOrCreate(
                ['name' => $product['name']],
                array_merge($product, ['is_active' => true])
            );
        }
    }
}
