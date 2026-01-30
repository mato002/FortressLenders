<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Must be authenticated
        if (! $user) {
            abort(403, 'You must be authenticated to access this area.');
        }
        
        // Block candidates from accessing admin areas
        if ($user->role === 'candidate') {
            abort(403, 'Access denied. This area is for employees only. Candidates should use the candidate dashboard.');
        }
        
        // Check if user is admin (for users with role 'user' or any other role)
        if (! $user->is_admin) {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}

