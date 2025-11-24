<?php

use Illuminate\Support\Facades\Broadcast;



Broadcast::channel('chat.{userId}', function ($user, $userId) {
    // allow only if authenticated user id equals the channel userId
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('test-channel', function ($user) {
    return true; // sementara untuk testing
});