<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\message;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class chatcontrol extends Controller
{
public function getContacts(Request $request)
{
    $me = $request->user();

    // ===============================
    // 1. ROLE USER → Selalu tampil 2 kontak default
    // ===============================
    if ($me->role === 'users') {

        $contacts = User::whereIn('role', ['Bendahara', 'Kasubag'])
            ->select('id', 'nama', 'role')
            ->get();

        $contacts = $contacts->map(function ($c) use ($me) {

            $last = Message::where(function ($q) use ($me, $c) {
                $q->where('sender_id', $me->id)->where('receiver_id', $c->id);
            })->orWhere(function ($q) use ($me, $c) {
                $q->where('sender_id', $c->id)->where('receiver_id', $me->id);
            })->latest()->first();

            $unread = Message::where('sender_id', $c->id)
                ->where('receiver_id', $me->id)
                ->where('is_read', false)
                ->count();

            return [
                'id'    => $c->id,
                'nama'  => $c->nama,
                'role'  => $c->role,
                'last_message' => $last?->message,
                'last_date'    => $last?->created_at?->toDateTimeString(),
                'unread'       => $unread,
            ];
        });

        return response()->json($contacts);
    }

    // ===============================
    // 2. ROLE Bendahara/Kasubag → tampil
    //    hanya USER yang pernah ada komunikasi + kontak default
    // ===============================
    if (in_array($me->role, ['Bendahara', 'Kasubag'])) {

        // Ambil semua user yg pernah chat dengannya
        $chatUserIds = Message::where('sender_id', $me->id)
            ->orWhere('receiver_id', $me->id)
            ->selectRaw("CASE 
                            WHEN sender_id = {$me->id} THEN receiver_id
                            ELSE sender_id
                        END as user_id")
            ->distinct()
            ->pluck('user_id');

        // ================================
        // TAMBAHKAN KONTAK DEFAULT
        // Jika Kasubag → pastikan Bendahara masuk
        // Jika Bendahara → pastikan Kasubag masuk
        // ================================
        if ($me->role === 'Kasubag') {
            $default = User::where('role', 'Bendahara')->first();
            if ($default) {
                $chatUserIds->push($default->id);
            }
        }

        if ($me->role === 'Bendahara') {
            $default = User::where('role', 'Kasubag')->first();
            if ($default) {
                $chatUserIds->push($default->id);
            }
        }

        // Hapus duplikat ID
        $chatUserIds = $chatUserIds->unique();

        // Jika tetap kosong → kirim kosong
        if ($chatUserIds->isEmpty()) {
            return response()->json([]);
        }

        // Ambil user berdasarkan daftar ID
        $contacts = User::whereIn('id', $chatUserIds)
            ->select('id', 'nama', 'role')
            ->get();

        // Format untuk frontend
        $contacts = $contacts->map(function ($c) use ($me) {

            $last = Message::where(function ($q) use ($me, $c) {
                $q->where('sender_id', $me->id)->where('receiver_id', $c->id);
            })->orWhere(function ($q) use ($me, $c) {
                $q->where('sender_id', $c->id)->where('receiver_id', $me->id);
            })->latest()->first();

            $unread = Message::where('sender_id', $c->id)
                ->where('receiver_id', $me->id)
                ->where('is_read', false)
                ->count();

            return [
                'id'    => $c->id,
                'nama'  => $c->nama,
                'role'  => $c->role,
                'last_message' => $last?->message,
                'last_date'    => $last?->created_at?->toDateTimeString(),
                'unread'       => $unread,
            ];
        });

        return response()->json($contacts);
    }
}



    public function getMessages(Request $request, $otherUserId)
    {
        $me = $request->user();

        // ensure access: user can chat only with bendahara/kasubag, but others (bendahara/kasubag) can chat with anyone.
        $other = User::findOrFail($otherUserId);

        if ($me->role === 'user' && !in_array($other->role, ['Bendahara','Kasubag'])) {
            return response()->json(['message' => 'Not allowed'], 403);
        }

        $messages = Message::where(function ($q) use ($me, $other) {
            $q->where('sender_id', $me->id)->where('receiver_id', $other->id);
        })->orWhere(function ($q) use ($me, $other) {
            $q->where('sender_id', $other->id)->where('receiver_id', $me->id);
        })->orderBy('created_at')->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $me = $request->user();
        $receiver = User::findOrFail($request->receiver_id);

        // enforce user role restriction
        if ($me->role === 'users' && !in_array($receiver->role, ['Bendahara','Kasubag'])) {
            return response()->json(['message' => 'Not allowed to message this user'], 403);
        }

        $msg = Message::create([
            'sender_id' => $me->id,
            'receiver_id' => $receiver->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        // eager load sender relation for event payload
        $msg->load('sender');

        event(new MessageSent($msg));

        return response()->json($msg);
    }

    public function markAsRead(Request $request, $otherUserId)
    {
        $me = $request->user();
        Message::where('sender_id', $otherUserId)
            ->where('receiver_id', $me->id)
            ->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }


   


}
