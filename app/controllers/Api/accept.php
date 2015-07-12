<?php

// Get sender ID from client
$id             = Input::get('senderid');
$sender         = User::where('id', $id)->first();

// Get receiver ID from client
$receiver_id    = Input::get('receiverid');
$receiver       = User::where('id', $receiver_id)->first();
$like           = Like::where('sender_id', $id)->where('receiver_id', $receiver_id)->first();

// Receiver accept like
$like->status   = 1;

// Add friend relationship in chat system and start chat
Queue::push('AddFriendQueue', [
                        'user_id'   => $receiver_id,
                        'friend_id' => $id,
                    ]);
Queue::push('AddFriendQueue', [
                        'user_id'   => $id,
                        'friend_id' => $receiver_id,
                    ]);

if ($like->save()) {
    // Save notification in database for website
    $notification   = Notification(3, $receiver_id, $id); // Some user accept you like

    // Add push notifications for App client to queue
    Queue::push('LikeQueue', [
                                'target'    => $id,
                                'action'    => 3,
                                'from'      => $receiver_id,

                                // Notification ID
                                'id'        => e($notification->id),
                                'content'   => app_out_filter($receiver->nickname) . '接受了你的邀请，快去查看一下吧',
                                'sender_id' => e($receiver_id),
                                'portrait'  => route('home') . '/' . 'portrait/' . $receiver->portrait,
                                'nickname'  => app_out_filter($receiver->nickname),
                                'answer'    => null
                            ]);

    return Response::json(
            array(
                'status'    => 1,
                'id'        => $id,
                'portrait'  => route('home') . '/' . 'portrait/' . $sender->portrait,
                'nickname'  => app_out_filter($sender->nickname)
            )
        );
} else {
    return Response::json(
            array(
                'status'        => 0
            )
        );
}
