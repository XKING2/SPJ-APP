<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\message;
use Illuminate\Support\Facades\Auth;

class chatcontrol extends Controller
{
    public function getMessages($userId)
    {
        $authId = Auth::id();

        $messages = Message::where(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)
                  ->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'ASC')
            ->get();

        return response()->json($messages);
    }

    /**
     * Kirim pesan ke user lain
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message'     => 'required|string'
        ]);

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message'     => $request->message,
            'is_read'     => 0
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }

    /**
     * Tandai pesan sebagai sudah dibaca
     */
    public function markAsRead($userId)
    {
        $authId = Auth::id();

        Message::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->update(['is_read' => 1]);

        return response()->json(['status' => 'read']);
    }
}
