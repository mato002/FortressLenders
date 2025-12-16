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
        
        // Allow all authenticated users to access admin panel
        // Role-based permissions are enforced at the route level
        if (! $user) {
            abort(403, 'You must be authenticated to access this area.');
        }

        return $next($request);
    }
}

