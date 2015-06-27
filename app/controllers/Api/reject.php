<?php

// Get sender ID from client
$id             = Input::get('senderid');

// Get receiver ID from client
$receiver_id    = Input::get('receiverid');
$receiver       = User::where('id', $receiver_id)->first();
$like           = Like::where('sender_id', $id)->where('receiver_id', $receiver_id)->first();

// Receiver reject user, remove friend relationship in chat system
$like->status   = 2;

if ($like->save()) {
    // Save notification in database for website
    $notification   = Notification(4, $receiver_id, $id); // Some user reject you like

    // $easemob     = getEasemob();
    // Push notifications to App client
    // cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
    //      'target_type'   => 'users',
    //      'target'        => [$id],
    //      'msg'           => ['type' => 'cmd', 'action' => '4'],
    //      'from'          => $receiver_id,
    //      'ext'           => [
    //                              'content'   => $receiver->nickname.'拒绝了你的邀请',
    //                              'id'        => $receiver_id,
    //                              'portrait'  => route('home').'/'.'portrait/'.$receiver->portrait,
    //                              'nickname'  => $receiver->nickname
    //                          ]
    //  ])
    //      ->setHeader('content-type', 'application/json')
    //      ->setHeader('Accept', 'json')
    //      ->setHeader('Authorization', 'Bearer '.$easemob->token)
    //      ->setOptions([CURLOPT_VERBOSE => true])
    //      ->send();
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
