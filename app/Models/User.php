<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin (either via role or is_admin flag for backward compatibility).
     */
    public function isAdmin(): bool
    {
        return $this->is_admin || $this->role === 'admin';
    }

    /**
     * Get available roles.
     */
    public static function getRoles(): array
    {
        return [
            'user' => 'User',
            'admin' => 'Administrator',
            'hr_manager' => 'HR Manager',
            'loan_manager' => 'Loan Manager',
            'editor' => 'Editor',
        ];
    }

    /**
     * Check if user is HR Manager or has HR-related access.
     */
    public function isHrManager(): bool
    {
        return $this->role === 'hr_manager' || $this->isAdmin();
    }

    /**
     * Check if user is Loan Manager or has loan-related access.
     */
    public function isLoanManager(): bool
    {
        return $this->role === 'loan_manager' || $this->isAdmin();
    }

    /**
     * Check if user can access careers section.
     */
    public function canAccessCareers(): bool
    {
        return $this->isHrManager() || $this->isAdmin();
    }

    /**
     * Check if user can access loan applications section.
     */
    public function canAccessLoans(): bool
    {
        return $this->isLoanManager() || $this->isAdmin();
    }

    /**
     * Get the activity logs for the user.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the user sessions for the user.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Get active sessions for the user.
     */
    public function activeSessions()
    {
        return $this->sessions()->active()->orderBy('last_activity', 'desc');
    }
}
