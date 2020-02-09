<?php

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


// todo 是否有权限加入该聊天室！
Broadcast::channel('live.{liveId}', function ($user, $liveId) {
	// if ($user->canJoinRoom($roomId)) {
    // return ['id' => $user->id, 'name' => $user->name];
    // }
	return $user;
});

Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    if ($user->canJoinRoom($roomId)) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});


Broadcast::channel('chat', function ($user) {
    return $user;
});
