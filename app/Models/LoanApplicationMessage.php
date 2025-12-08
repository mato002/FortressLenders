<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplicationMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_application_id',
        'sent_by',
        'channel',
        'message',
        'recipient',
        'status',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}

