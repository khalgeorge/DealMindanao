<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    /**
     * Get chat messages for the authenticated user.
     * Pass ?after={id} for polling to only return new messages.
     */
    public function messages(Request $request)
    {
        $query = ChatMessage::where('user_id', auth()->id())
            ->orderBy('created_at', 'asc');

        if ($request->has('after')) {
            $query->where('id', '>', $request->integer('after'));
        }

        return response()->json(
            $query->get(['id', 'message', 'sender', 'created_at'])
        );
    }

    /**
     * Send a message from the authenticated user.
     */
    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $msg = ChatMessage::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'sender'  => 'user',
        ]);

        // Notify admin via Facebook Messenger if configured
        $this->notifyAdminViaFacebook($request->user(), $request->message);

        return response()->json(
            $msg->only('id', 'message', 'sender', 'created_at'),
            201
        );
    }

    /**
     * Forward the user message to the admin's Messenger via Facebook Send API.
     * Requires FACEBOOK_PAGE_ACCESS_TOKEN and FACEBOOK_ADMIN_PSID in .env.
     */
    private function notifyAdminViaFacebook($user, string $message): void
    {
        $token   = config('services.facebook.page_access_token');
        $adminId = config('services.facebook.admin_psid');

        if (!$token || !$adminId) {
            return;
        }

        try {
            Http::timeout(5)->post(
                "https://graph.facebook.com/v22.0/me/messages?access_token={$token}",
                [
                    'recipient' => ['id' => $adminId],
                    'message'   => [
                        'text' => "💬 {$user->name}: {$message}\n\n👉 Reply at: " . url('/admin/chat'),
                    ],
                ]
            );
        } catch (\Throwable $e) {
            Log::warning('FB Messenger notify failed: ' . $e->getMessage());
        }
    }
}
