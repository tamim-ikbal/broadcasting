<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

//Broadcast::channel('notifications', function ($user) {
//    return $user->id === 22;
//});

Broadcast::channel('messages.{inboxId}', function ($user, $inboxId) {
    return true;
});
