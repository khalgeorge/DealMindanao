<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyHoneypot
{
    /**
     * Handle an incoming request.
     *
     * Checks:
     *  1. Honeypot fields (_hp, website) must be empty — bots fill these.
     *  2. Minimum form-fill time — bot submissions arrive in < 1 second.
     *
     * On detection we silently redirect back with input so the user (or bot)
     * just sees the form again. Bots get stuck; real users can resubmit.
     */
    public function handle(Request $request, Closure $next, int $minSeconds = 3): Response
    {
        // ── Honeypot: bot filled a hidden field ──────────────────────────────
        if ($request->filled('_hp') || $request->filled('website')) {
            // Silent — don't tell the bot it was caught
            return redirect()->back()->withInput(
                $request->except(['password', 'password_confirmation', '_hp', 'website'])
            );
        }

        // ── Timing: form submitted impossibly fast ───────────────────────────
        $startedAt = session('_form_started');
        if ($startedAt !== null && (time() - (int) $startedAt) < $minSeconds) {
            return redirect()->back()->withInput(
                $request->except(['password', 'password_confirmation', '_hp', 'website'])
            );
        }

        session()->forget('_form_started');

        return $next($request);
    }
}
