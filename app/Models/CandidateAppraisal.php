<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateAppraisal extends Model
{
    protected $fillable = [
        'candidate_id',
        'type',
        'title',
        'content',
        'file_path',
        'created_by',
        'review_date',
        'severity',
        'is_acknowledged',
        'acknowledged_at',
    ];

    protected $casts = [
        'review_date' => 'date',
        'is_acknowledged' => 'boolean',
        'acknowledged_at' => 'datetime',
    ];

    /**
     * Get the candidate that owns this appraisal.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the user who created this appraisal (HR).
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
