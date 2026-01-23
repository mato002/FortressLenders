<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfInterviewQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'company_id',
        'question',
        'question_type',
        'options',
        'correct_answer',
        'points',
        'explanation',
        'display_order',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
    ];

    public function jobPost(): BelongsTo
    {
        return $this->belongsTo(JobPost::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to filter by company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Check if question is multiple choice
     */
    public function isMultipleChoice(): bool
    {
        return $this->question_type === 'multiple_choice' || (!empty($this->options) && $this->question_type !== 'text' && $this->question_type !== 'calculation');
    }

    /**
     * Check if question is text-based
     */
    public function isText(): bool
    {
        return $this->question_type === 'text' || (empty($this->options) && $this->question_type !== 'calculation');
    }

    /**
     * Check if question requires calculation
     */
    public function isCalculation(): bool
    {
        return $this->question_type === 'calculation';
    }
}


