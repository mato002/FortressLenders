<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanProductType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_loan_amount',
        'max_loan_amount',
        'service_charge_type',
        'service_charge_value',
        'service_charge_period',
        'max_duration_weeks',
        'payment_frequency',
        'target_clients',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'min_loan_amount' => 'decimal:2',
        'max_loan_amount' => 'decimal:2',
        'service_charge_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
