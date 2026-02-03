<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OnlyCandidates
{
    /**
     * Block portal-only employees from aptitude test and self-interview.
     * Guests and candidates are allowed (candidates take tests; guests may use magic links).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check() && Auth::user()->role === 'employee') {
            abort(403, 'Aptitude tests and self interviews are only available to candidates. You can use the rest of the candidate portal.');
        }

        return $next($request);
    }
}
