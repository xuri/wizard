<?php

$id             = Input::get('senderid');
$receiver_id    = Input::get('id');
$like           = Like::where('sender_id', $id)
                            ->where('receiver_id', $receiver_id)
                            ->first();
if ($like === null) {
    $like           = Like::where('receiver_id', $id)
                            ->where('sender_id', $receiver_id)
                            ->first();
}
// Receiver block user, remove friend relationship in chat system
$like->status   = 3;

// Some user blocked you
$notification   = Notification(5, $id, $receiver_id);

// Remove friend relationship in chat system
Queue::push('DeleteFriendQueue', [
                        'user_id'   => $receiver_id,
                        'block_id'  => $id,
                    ]);
Queue::push('DeleteFriendQueue', [
                        'user_id'   => $id,
                        'block_id'  => $receiver_id,
                    ]);

// Push notifications to App client
// cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
//      'target_type'   => 'users',
//      'target'        => [$id],
//      'msg'           => ['type' => 'cmd', 'action' => '5'],
//      'from'          => $receiver_id,
//      'ext'           => ['content' => User::where('id', $receiver_id)->first()->nickname.'把你加入了黑名单', 'id' => $notification->id]
//  ])
//      ->setHeader('content-type', 'application/json')
//      ->setHeader('Accept', 'json')
//      ->setHeader('Authorization', 'Bearer '.$easemob->token)
//      ->setOptions([CURLOPT_VERBOSE => true])
//      ->send();

if ($like->save()) {
    return Response::json(
        array(
            'status'        => 1
        )
    );
} else {
    return Response::json(
        array(
            'status'        => 0
        )
    );
}
