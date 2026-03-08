<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // ── Anti-DDoS: per-IP hourly rate limit ─────────────────────────────
        $ipKey = 'register|' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            $seconds = RateLimiter::availableIn($ipKey);
            throw ValidationException::withMessages([
                'email' => "Too many registration attempts. Please try again in " . ceil($seconds / 60) . " minute(s).",
            ]);
        }
        RateLimiter::hit($ipKey, 3600); // decay: 1 hour

        // ── Validation ───────────────────────────────────────────────────────
        $request->validate([
            'name'     => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\pL\s\-\.\']+$/u'],
            'email'    => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()],
        ], [
            'name.regex' => 'Name may only contain letters, spaces, hyphens, and periods.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        $user = User::create([
            'name'     => strip_tags(trim($request->name)),
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        RateLimiter::clear($ipKey);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

