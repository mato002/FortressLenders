<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'highlight_color',
        'cta_label',
        'cta_link',
        'summary',
        'description',
        'is_active',
        'display_order',
        // Loan details
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
        // Service charge fields
        'service_charge_type',
        'service_charge_value',
        'service_charge_period',
        'payment_frequency',
        'max_duration_weeks',
        'target_clients',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->orderBy('is_primary', 'desc')
            ->orderBy('display_order');
    }
}
