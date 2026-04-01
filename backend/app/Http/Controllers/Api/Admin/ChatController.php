<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function conversations()
    {
        $userRows  = ChatMessage::selectRaw('user_id, NULL as guest_token, MAX(id) as last_id')
            ->whereNotNull('user_id')->groupBy('user_id')->get();
        $guestRows = ChatMessage::selectRaw('NULL as user_id, guest_token, MAX(id) as last_id')
            ->whereNull('user_id')->whereNotNull('guest_token')->groupBy('guest_token')->get();

        $convos = $userRows->concat($guestRows)->sortByDesc('last_id')->values()
            ->map(function ($row) {
                $last = ChatMessage::find($row->last_id);
                if ($row->user_id) {
                    $user   = User::find($row->user_id, ['id', 'name', 'email']);
                    $unread = ChatMessage::where('user_id', $row->user_id)
                        ->where('sender', 'user')->whereNull('read_at')->count();
                    return [
                        'type' => 'user', 'key' => 'u_' . $row->user_id,
                        'user_id' => $row->user_id, 'guest_token' => null,
                        'user' => $user, 'last_message' => $last?->message,
                        'last_at' => $last?->created_at, 'unread' => $unread,
                    ];
                } else {
                    $short  = substr($row->guest_token, 0, 8);
                    $unread = ChatMessage::where('guest_token', $row->guest_token)
                        ->where('sender', 'user')->whereNull('read_at')->count();
                    return [
                        'type' => 'guest', 'key' => 'g_' . $row->guest_token,
                        'user_id' => null, 'guest_token' => $row->guest_token,
                        'user' => ['id' => null, 'name' => 'Guest #' . $short, 'email' => 'Guest visitor'],
                        'last_message' => $last?->message,
                        'last_at' => $last?->created_at, 'unread' => $unread,
                    ];
                }
            });
        return response()->json($convos);
    }

    public function messages(int $userId)
    {
        User::findOrFail($userId);
        ChatMessage::where('user_id', $userId)->where('sender', 'user')
            ->whereNull('read_at')->update(['read_at' => now()]);
        return response()->json(
            ChatMessage::where('user_id', $userId)->orderBy('created_at', 'asc')
                ->get(['id', 'message', 'sender', 'created_at'])
        );
    }

    public function guestMessages(string $token)
    {
        ChatMessage::where('guest_token', $token)->where('sender', 'user')
            ->whereNull('read_at')->update(['read_at' => now()]);
        return response()->json(
            ChatMessage::where('guest_token', $token)->orderBy('created_at', 'asc')
                ->get(['id', 'message', 'sender', 'created_at'])
        );
    }

    public function reply(Request $request, int $userId)
    {
        $request->validate(['message' => 'required|string|max:2000']);
        User::findOrFail($userId);
        $msg = ChatMessage::create(['user_id' => $userId, 'message' => $request->message, 'sender' => 'admin']);
        return response()->json($msg->only('id', 'message', 'sender', 'created_at'), 201);
    }

    public function guestReply(Request $request, string $token)
    {
        $request->validate(['message' => 'required|string|max:2000']);
        $msg = ChatMessage::create(['guest_token' => $token, 'message' => $request->message, 'sender' => 'admin']);
        return response()->json($msg->only('id', 'message', 'sender', 'created_at'), 201);
    }

    public function poll(Request $request, int $userId)
    {
        $after = $request->integer('after', 0);
        $messages = ChatMessage::where('user_id', $userId)->where('id', '>', $after)
            ->orderBy('created_at', 'asc')->get(['id', 'message', 'sender', 'created_at']);
        ChatMessage::where('user_id', $userId)->where('sender', 'user')
            ->whereNull('read_at')->update(['read_at' => now()]);
        return response()->json($messages);
    }

    public function guestPoll(Request $request, string $token)
    {
        $after = $request->integer('after', 0);
        $messages = ChatMessage::where('guest_token', $token)->where('id', '>', $after)
            ->orderBy('created_at', 'asc')->get(['id', 'message', 'sender', 'created_at']);
        ChatMessage::where('guest_token', $token)->where('sender', 'user')
            ->whereNull('read_at')->update(['read_at' => now()]);
        return response()->json($messages);
    }

    public function unreadCount()
    {
        $count = ChatMessage::where('sender', 'user')->whereNull('read_at')->count();
        return response()->json(['unread' => $count]);
    }
}
