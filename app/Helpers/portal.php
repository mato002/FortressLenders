<?php

use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;

if (! function_exists('current_portal_candidate')) {
    /**
     * Get the current candidate for the candidate portal (either logged in as candidate or as employee).
     */
    function current_portal_candidate(): ?Candidate
    {
        if (Auth::guard('candidate')->check()) {
            return Auth::guard('candidate')->user();
        }
        if (Auth::guard('web')->check() && Auth::user()->role === 'employee') {
            return Candidate::where('user_id', Auth::id())->first();
        }

        return null;
    }
}

if (! function_exists('is_portal_employee')) {
    /**
     * Check if the current user is a portal-only employee (uses candidate dashboard, no aptitude/self-interview).
     */
    function is_portal_employee(): bool
    {
        return Auth::guard('web')->check() && Auth::user()->role === 'employee';
    }
}
