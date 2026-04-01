<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * List all user conversations (one entry per user, sorted by latest message).
     */
    public function conversations()
    {
        $convos = ChatMessage::selectRaw('user_id, MAX(id) as last_id')
            ->groupBy('user_id')
            ->orderByDesc('last_id')
            ->get()
            ->map(function ($row) {
                $last   = ChatMessage::find($row->last_id);
                $user   = User::find($row->user_id, ['id', 'name', 'email']);
                $unread = ChatMessage::where('user_id', $row->user_id)
                    ->where('sender', 'user')
                    ->whereNull('read_at')
                    ->count();

                return [
                    'user_id'      => $row->user_id,
                    'user'         => $user,
                    'last_message' => $last?->message,
                    'last_at'      => $last?->created_at,
                    'unread'       => $unread,
                ];
            });

        return response()->json($convos);
    }

    /**
     * Get all messages for a specific user. Marks user messages as read.
     */
    public function messages(int $userId)
    {
        User::findOrFail($userId);

        // Mark user's messages as read
        ChatMessage::where('user_id', $userId)
            ->where('sender', 'user')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = ChatMessage::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get(['id', 'message', 'sender', 'created_at']);

        return response()->json($messages);
    }

    /**
     * Send a reply to a specific user from the admin.
     */
    public function reply(Request $request, int $userId)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        User::findOrFail($userId);

        $msg = ChatMessage::create([
            'user_id' => $userId,
            'message' => $request->message,
            'sender'  => 'admin',
        ]);

        return response()->json(
            $msg->only('id', 'message', 'sender', 'created_at'),
            201
        );
    }

    /**
     * Poll for new messages in a conversation (used for real-time on admin side).
     */
    public function poll(Request $request, int $userId)
    {
        $after = $request->integer('after', 0);

        $messages = ChatMessage::where('user_id', $userId)
            ->where('id', '>', $after)
            ->orderBy('created_at', 'asc')
            ->get(['id', 'message', 'sender', 'created_at']);

        // Also mark new user messages as read
        ChatMessage::where('user_id', $userId)
            ->where('sender', 'user')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    /**
     * Get total unread message count across all conversations (for badge).
     */
    public function unreadCount()
    {
        $count = ChatMessage::where('sender', 'user')
            ->whereNull('read_at')
            ->count();

        return response()->json(['unread' => $count]);
    }
}
