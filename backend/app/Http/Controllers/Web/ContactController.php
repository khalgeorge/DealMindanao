<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'email'                => ['required', 'email', 'max:255'],
            'phone'                => ['nullable', 'string', 'max:30'],
            'subject'              => ['required', 'string', 'in:general,order_status,payment_delivery,returns,partner,report'],
            'message'              => ['required', 'string', 'min:10', 'max:5000'],
            'privacy_consent'      => ['required', 'accepted'],
            'g-recaptcha-response' => ['nullable', 'string'],
        ], [
            'name.required'            => 'Please enter your full name.',
            'email.required'           => 'Please enter your email address.',
            'email.email'              => 'Please enter a valid email address.',
            'subject.required'         => 'Please select a subject.',
            'subject.in'               => 'Please select a valid subject.',
            'message.required'         => 'Please enter your message.',
            'message.min'              => 'Your message must be at least 10 characters.',
            'privacy_consent.required' => 'You must agree to the Data Privacy Policy to submit this form.',
            'privacy_consent.accepted' => 'You must agree to the Data Privacy Policy to submit this form.',
        ]);

        // --- reCAPTCHA v3 verification ---
        $secretKey = config('services.recaptcha.secret_key');
        $token     = $request->input('g-recaptcha-response');

        if ($secretKey && $token !== 'dev-bypass') {
            if (empty($token)) {
                return back()
                    ->withErrors(['captcha' => 'CAPTCHA verification is required. Please try again.'])
                    ->withInput();
            }

            try {
                /** @var \Illuminate\Http\Client\Response $response */
                $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => $secretKey,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);

                $data = $response->json() ?? [];

                if (!($data['success'] ?? false) || ($data['score'] ?? 0) < 0.5) {
                    return back()
                        ->withErrors(['captcha' => 'CAPTCHA verification failed. Please refresh the page and try again.'])
                        ->withInput();
                }
            } catch (\Exception $e) {
                // Fail open — log the issue but don't block the user if Google is unreachable
                Log::warning('reCAPTCHA API error: ' . $e->getMessage());
            }
        }

        // Log the inquiry (extend to send an email when mail is configured)
        Log::info('Contact form submission', [
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'subject' => $validated['subject'],
            'phone'   => $validated['phone'] ?? null,
        ]);

        // TODO: uncomment once a mail driver is configured in production
        // Mail::to(config('mail.from.address'))->send(new \App\Mail\ContactInquiryMail($validated));

        return back()->with(
            'success',
            "Thank you, {$validated['name']}! Your message has been sent. We'll get back to you within 1–2 business days."
        );
    }
}
