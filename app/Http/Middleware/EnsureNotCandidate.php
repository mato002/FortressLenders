<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotCandidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Block candidates and portal-only employees from accessing admin
        if ($user && ($user->role === 'candidate' || $user->role === 'employee' || ($user->role === 'user' && !$user->is_admin))) {
            abort(403, 'Access denied. This area is for staff only. Use the candidate portal if you are an employee.');
        }
        
        return $next($request);
    }
}

