<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'date_of_birth',
        'town',
        'residence',
        'client_type',
        'loan_type',
        'amount_requested',
        'repayment_period',
        'purpose',
        'agreed_to_terms',
        'status',
        'admin_notes',
        'handled_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'agreed_to_terms' => 'boolean',
        'handled_at' => 'datetime',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function markHandled(?string $notes = null): void
    {
        $this->update([
            'handled_at' => now(),
            'status' => 'handled',
            'admin_notes' => $notes ?? $this->admin_notes,
        ]);
    }

    public function messages()
    {
        return $this->hasMany(LoanApplicationMessage::class);
    }
}



