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


Broadcast::channel('live.{liveId}', function ($user, $liveId) {
	//todo 是否加入了该聊天室！
	return $user;
    // return $user->id === Live::findOrNew($orderId)->user_id;
});

Broadcast::channel('chat', function ($user) {
    return $user;
});
