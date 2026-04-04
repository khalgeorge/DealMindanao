<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacebookChatAuthController extends Controller
{
    /**
     * Redirect to Facebook OAuth (server-side code flow).
     * Opens in a popup from the chat widget.
     */
    public function redirect(Request $request)
    {
        $appId       = config('services.facebook.app_id');
        $redirectUri = urlencode(url('/auth/fb-chat/callback'));

        $url = "https://www.facebook.com/v22.0/dialog/oauth"
             . "?client_id={$appId}"
             . "&redirect_uri={$redirectUri}"
             . "&response_type=code"
             . "&display=popup";

        return redirect($url);
    }

    /**
     * Callback — Facebook redirects here with ?code=...
     * We exchange the code for a token server-side, then pass the
     * user info to a Blade view that postMessages back to the opener.
     */
    public function callback(Request $request)
    {
        // User denied or FB returned an error
        if ($request->has('error')) {
            return view('auth.facebook-chat-callback', [
                'error' => $request->get('error_description', 'Facebook login was cancelled.'),
            ]);
        }

        $code = $request->get('code');
        if (!$code) {
            return view('auth.facebook-chat-callback', [
                'error' => 'No authorisation code received from Facebook.',
            ]);
        }

        $appId      = config('services.facebook.app_id');
        $appSecret  = config('services.facebook.app_secret');
        $redirectUri = url('/auth/fb-chat/callback');

        // Exchange code → access token
        $tokenRes = Http::get('https://graph.facebook.com/v22.0/oauth/access_token', [
            'client_id'     => $appId,
            'redirect_uri'  => $redirectUri,
            'client_secret' => $appSecret,
            'code'          => $code,
        ]);

        $tokenData = $tokenRes->json();

        if (!isset($tokenData['access_token'])) {
            $msg = $tokenData['error']['message'] ?? 'Failed to obtain access token.';
            return view('auth.facebook-chat-callback', ['error' => $msg]);
        }

        // Fetch user profile
        $userRes = Http::get('https://graph.facebook.com/v22.0/me', [
            'fields'       => 'id,name',
            'access_token' => $tokenData['access_token'],
        ]);

        $user = $userRes->json();

        if (!isset($user['id'])) {
            $msg = $user['error']['message'] ?? 'Failed to retrieve Facebook profile.';
            return view('auth.facebook-chat-callback', ['error' => $msg]);
        }

        return view('auth.facebook-chat-callback', [
            'guestToken' => 'fb_' . $user['id'],
            'name'       => $user['name'] ?? 'Guest',
        ]);
    }
}
