<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('task.{task}', \App\Broadcasting\TaskChannel::class,['guards' => ['web', 'admin']] );

Broadcast::channel('chat', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});

Broadcast::channel('post_updated', function ($user) {
    return ['id' => $user->id, 'name'=> $user->name];
},['guards' => ['admin']]);

Broadcast::channel('user.{userId}', function ($user, $userId) {
    \Log::debug($user->id . "  " . $userId);
    return (int ) $user->id === (int) $userId;
}, ['guards' => ['admin']]);