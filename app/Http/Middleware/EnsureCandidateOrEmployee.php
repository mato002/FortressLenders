<?php

namespace App\Http\Middleware;

use App\Models\Candidate;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCandidateOrEmployee
{
    /**
     * Allow access if user is logged in as candidate OR as employee (web guard, role employee).
     * Employees use the same candidate portal but without aptitude test / self-interview.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('candidate')->check()) {
            return $next($request);
        }

        if (Auth::guard('web')->check() && Auth::user()->role === 'employee') {
            $candidate = Candidate::where('user_id', Auth::id())->first();
            if (! $candidate) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors(['email' => 'Your employee account is not properly linked. Please contact support.']);
            }

            return $next($request);
        }

        return redirect()->route('login');
    }
}
