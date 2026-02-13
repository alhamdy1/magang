<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Security headers to protect against common vulnerabilities.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Enable XSS filtering in browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Referrer policy - don't leak internal URLs
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions policy - restrict browser features
        $response->headers->set('Permissions-Policy', 'geolocation=(self), camera=(), microphone=()');
        
        // Content Security Policy - prevent XSS and data injection
        if (app()->environment('production')) {
            $response->headers->set('Content-Security-Policy', 
                "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://unpkg.com https://cdn.tailwindcss.com; " .
                "style-src 'self' 'unsafe-inline' https://unpkg.com https://cdn.tailwindcss.com https://fonts.googleapis.com; " .
                "font-src 'self' https://fonts.gstatic.com; " .
                "img-src 'self' data: https://*.tile.openstreetmap.org https://unpkg.com; " .
                "connect-src 'self'; " .
                "frame-ancestors 'self';"
            );
        }
        
        // Remove server identification headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
        
        return $response;
    }
}
