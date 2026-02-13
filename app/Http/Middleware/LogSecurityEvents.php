<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogSecurityEvents
{
    /**
     * Log security-relevant events for audit trail.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log authentication attempts
        if ($request->is('login') && $request->isMethod('POST')) {
            $this->logAuthAttempt($request, $response);
        }

        // Log sensitive actions
        if ($this->isSensitiveAction($request)) {
            $this->logSensitiveAction($request, $response);
        }

        return $response;
    }

    /**
     * Check if this is a sensitive action.
     */
    protected function isSensitiveAction(Request $request): bool
    {
        $sensitivePatterns = [
            'permits/*/approve',
            'permits/*/reject',
            'admin/*',
            'users/*',
        ];

        foreach ($sensitivePatterns as $pattern) {
            if ($request->is($pattern) && $request->isMethod('POST')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log authentication attempt.
     */
    protected function logAuthAttempt(Request $request, Response $response): void
    {
        $status = $response->getStatusCode() < 400 ? 'success' : 'failed';
        
        Log::channel('security')->info('Authentication attempt', [
            'status' => $status,
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Log sensitive action.
     */
    protected function logSensitiveAction(Request $request, Response $response): void
    {
        Log::channel('security')->info('Sensitive action performed', [
            'user_id' => Auth::id(),
            'action' => $request->method() . ' ' . $request->path(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status_code' => $response->getStatusCode(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
