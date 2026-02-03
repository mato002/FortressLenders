<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'role' => EnsureUserHasRole::class,
            'not.candidate' => \App\Http\Middleware\EnsureNotCandidate::class,
            'portal.candidate.or.employee' => \App\Http\Middleware\EnsureCandidateOrEmployee::class,
            'only.candidates' => \App\Http\Middleware\OnlyCandidates::class,
        ]);
        
        // Track user sessions for authenticated users, check blocked IPs, and check banned users
        $middleware->web(append: [
            \App\Http\Middleware\CheckBlockedIp::class,
            \App\Http\Middleware\CheckBannedUser::class,
            \App\Http\Middleware\TrackUserSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Custom error page rendering
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Resource not found.'], 404);
            }
            return response()->view('errors.404', [], 404);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
            }
            
            $statusCode = $e->getStatusCode();
            $errorViews = [
                403 => 'errors.403',
                404 => 'errors.404',
                419 => 'errors.419',
                500 => 'errors.500',
                503 => 'errors.503',
            ];
            
            if (isset($errorViews[$statusCode]) && view()->exists($errorViews[$statusCode])) {
                return response()->view($errorViews[$statusCode], [], $statusCode);
            }
        });

        // Handle general exceptions
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => config('app.debug') ? $e->getTraceAsString() : null,
                ], 500);
            }
            
            // Always show detailed error - don't hide it
            if (config('app.debug')) {
                return null; // Let Laravel handle it with debug info
            }
            
            // Even in production, show the actual error message
            return response()->view('errors.500', [
                'error' => $e,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        });

        // Log all exceptions
        $exceptions->report(function (\Throwable $e) {
            \Log::error('Exception occurred', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        });
    })->create();
