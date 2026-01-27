<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCalculatorLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'whatsapp_number',
        'loan_amount',
        'loan_duration_value',
        'loan_duration_unit',
        'service_charge',
        'total_repayment',
        'payment_frequency',
    ];
}

