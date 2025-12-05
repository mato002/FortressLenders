<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'name',
        'phone',
        'email',
        'education_level',
        'area_of_study',
        'institution',
        'education_status',
        'other_achievements',
        'work_experience',
        'current_job_title',
        'current_company',
        'currently_working',
        'duties_and_responsibilities',
        'other_experiences',
        'ai_summary',
        'ai_details',
        'support_details',
        'referrers',
        'notice_period',
        'agreement_accepted',
        'cv_path',
        'application_message',
        'status',
    ];

    protected $casts = [
        'work_experience' => 'array',
        'referrers' => 'array',
        'currently_working' => 'boolean',
        'agreement_accepted' => 'boolean',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function reviews()
    {
        return $this->hasMany(JobApplicationReview::class);
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }
}

