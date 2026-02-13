<?php

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
        $middleware->trustProxies(at: '*');
        
        // Add security headers to all responses
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'throttle.ip' => \App\Http\Middleware\ThrottleByIp::class,
            'log.security' => \App\Http\Middleware\LogSecurityEvents::class,
        ]);

        // Apply rate limiting to web routes using cache driver instead of Redis
        // $middleware->throttleWithRedis('web'); // Commented out - Redis not available
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Log all exceptions to security log if they're auth-related
        $exceptions->report(function (\Illuminate\Auth\AuthenticationException $e) {
            \Illuminate\Support\Facades\Log::channel('security')->warning('Authentication exception', [
                'message' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);
        });
    })->create();
