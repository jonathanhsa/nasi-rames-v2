<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getMessages()
    {
        $user = Auth::user();
        if (!$user) return response()->json([]);

        if ($user->is_admin) {
            // Admin gets messages differently, usually by user. For simplicity, we can fetch all or handle via an admin view.
            return response()->json([]); 
        }

        return response()->json(ChatMessage::where('user_id', $user->id)->orderBy('created_at', 'asc')->get());
    }

    public function sendMessage(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthenticated'], 401);

        ChatMessage::create([
            'user_id' => $request->user_id ?? $user->id,
            'is_admin' => $user->is_admin,
            'message' => $request->message
        ]);

        return response()->json(['success' => true]);
    }

    public function adminChats()
    {
        $messages = ChatMessage::with('user')->orderBy('created_at', 'desc')->get()->groupBy('user_id');
        return view('admin.chats', compact('messages'));
    }
}
