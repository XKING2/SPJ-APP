<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Events\MessageSent;
use App\Http\Controllers\chatcontrol;

Route::post('/send-message', function (Request $request) {
    broadcast(new MessageSent(
        $request->from,
        $request->to,
        $request->message
    ))->toOthers();

    return ['status' => 'ok'];
});


Route::get('/chat/users', [chatcontrol::class, 'chatUsers']);




