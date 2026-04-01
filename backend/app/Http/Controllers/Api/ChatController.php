<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    private function resolveConversation(Request $request): ?array
    {
        $user = auth('api')->user();
        if ($user) {
            return ['type' => 'user', 'value' => $user->id];
        }
        $guestToken = $request->header('X-Guest-Token');
        if ($guestToken && strlen($guestToken) >= 16 && strlen($guestToken) <= 64) {
            return ['type' => 'guest', 'value' => $guestToken];
        }
        return null;
    }

    public function messages(Request $request)
    {
        $convo = $this->resolveConversation($request);
        if (!$convo) {
            return response()->json([]);
        }
        $query = ChatMessage::orderBy('created_at', 'asc');
        if ($convo['type'] === 'user') {
            $query->where('user_id', $convo['value']);
        } else {
            $query->where('guest_token', $convo['value']);
        }
        if ($request->has('after')) {
            $query->where('id', '>', $request->integer('after'));
        }
        return response()->json($query->get(['id', 'message', 'sender', 'created_at']));
    }

    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string|max:2000']);
        $convo = $this->resolveConversation($request);
        if (!$convo) {
            return response()->json(['error' => 'Missing guest token or authentication.'], 400);
        }
        $data = ['message' => $request->message, 'sender' => 'user'];
        if ($convo['type'] === 'user') {
            $data['user_id'] = $convo['value'];
            $this->notifyAdminViaFacebook(auth('api')->user(), $request->message);
        } else {
            $data['guest_token'] = $convo['value'];
            $this->notifyAdminViaFacebookGuest($convo['value'], $request->message);
        }
        $msg = ChatMessage::create($data);
        return response()->json($msg->only('id', 'message', 'sender', 'created_at'), 201);
    }

    private function notifyAdminViaFacebook($user, string $message): void
    {
        $token   = config('services.facebook.page_access_token');
        $adminId = config('services.facebook.admin_psid');
        if (!$token || !$adminId) return;
        try {
            Http::timeout(5)->post(
                "https://graph.facebook.com/v22.0/me/messages?access_token={$token}",
                ['recipient' => ['id' => $adminId],
                 'message'   => ['text' => "Chat from {$user->name}: {$message} - Reply at: " . url('/admin/chat')]]
            );
        } catch (\Throwable $e) {
            Log::warning('FB Messenger notify failed: ' . $e->getMessage());
        }
    }

    private function notifyAdminViaFacebookGuest(string $guestToken, string $message): void
    {
        $token   = config('services.facebook.page_access_token');
        $adminId = config('services.facebook.admin_psid');
        if (!$token || !$adminId) return;
        $short = substr($guestToken, 0, 8);
        try {
            Http::timeout(5)->post(
                "https://graph.facebook.com/v22.0/me/messages?access_token={$token}",
                ['recipient' => ['id' => $adminId],
                 'message'   => ['text' => "Chat from Guest #{$short}: {$message} - Reply at: " . url('/admin/chat')]]
            );
        } catch (\Throwable $e) {
            Log::warning('FB Messenger guest notify failed: ' . $e->getMessage());
        }
    }
}
