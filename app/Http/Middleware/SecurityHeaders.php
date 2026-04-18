<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Evita que la página se cargue dentro de un iframe (clickjacking)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Evita que el navegador adivine el tipo de archivo (MIME sniffing)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Activa protección XSS del navegador (IE/Edge legacy)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Solo envía referrer en el mismo sitio
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Desactiva funciones del navegador que no se usan
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Fuerza HTTPS por 1 año (solo en producción)
        if (app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Content Security Policy — permite recursos propios + CDNs usadas
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline'",          // inline JS de Blade
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data: https:",                 // imágenes externas (maps, storage)
            "frame-src https://www.google.com",            // iframe de Google Maps
            "connect-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
