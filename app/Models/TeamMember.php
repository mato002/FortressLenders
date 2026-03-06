<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'email',
        'phone',
        'photo_path',
        'linkedin_url',
        'bio',
        'display_order',
        'is_active',
        'account_active',
        'user_id',
    ];

    /**
     * Get the user (employee) linked to this team member when login has been generated.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'is_active' => 'boolean',
        'account_active' => 'boolean',
    ];
}







