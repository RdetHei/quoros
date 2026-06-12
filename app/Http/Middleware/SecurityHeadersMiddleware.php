<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // Headers that require HTTPS/Trustworthy Origin
        if ($request->isSecure() || app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
            $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        }

        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' blob: https://cdn.jsdelivr.net http://localhost:5173 http://127.0.0.1:5173 http://localhost:5174 http://127.0.0.1:5174; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com http://localhost:5173 http://127.0.0.1:5173 http://localhost:5174 http://127.0.0.1:5174; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https://res.cloudinary.com https://images.unsplash.com *; connect-src 'self' ws://localhost:5173 ws://127.0.0.1:5173 ws://localhost:5174 ws://127.0.0.1:5174 http://localhost:5173 http://127.0.0.1:5173 http://localhost:5174 http://127.0.0.1:5174; worker-src 'self' blob:;");

        // Browser Caching for static-ish responses (PWA manifest, etc)
        if ($request->isMethod('GET') && $response->isSuccessful()) {
            if ($request->routeIs('pwa.manifest') || $request->is('build/*') || $request->is('storage/*')) {
                $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            } else {
                $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
                $response->headers->set('Pragma', 'no-cache');
            }
        }

        return $response;
    }

}

