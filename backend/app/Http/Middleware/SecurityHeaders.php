<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Attaches HTTP security response headers to every request.
 *
 * Headers added:
 *  - X-Frame-Options           – blocks clickjacking (iframes not from same origin)
 *  - X-Content-Type-Options    – blocks MIME-type sniffing
 *  - X-XSS-Protection          – legacy browser XSS filter (defence-in-depth)
 *  - Referrer-Policy           – limits referrer leakage across origins
 *  - Permissions-Policy        – disables browser features we don't use
 *  - Strict-Transport-Security – forces HTTPS for 1 year (only on HTTPS responses)
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // ── Clickjacking protection ────────────────────────────────────────────
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // ── MIME sniffing protection ───────────────────────────────────────────
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ── Legacy XSS filter (IE/older Chrome) ───────────────────────────────
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // ── Referrer leakage control ───────────────────────────────────────────
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ── Disable features the site doesn't use ─────────────────────────────
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=(), payment=(), usb=(), fullscreen=(self)'
        );

        // ── HSTS: only set on HTTPS responses (Cloudflare handles TLS, but
        //    we still send it so the browser caches it from the origin) ─────────
        if ($request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
