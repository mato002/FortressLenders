<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidate extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio_data',
        'bio_data_completed',
        'bio_data_completed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'bio_data_completed' => 'boolean',
            'bio_data_completed_at' => 'datetime',
        ];
    }

    /**
     * Get job applications for this candidate.
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get documents for this candidate.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(CandidateDocument::class);
    }

    /**
     * Get appraisals for this candidate.
     */
    public function appraisals(): HasMany
    {
        return $this->hasMany(CandidateAppraisal::class);
    }
}

