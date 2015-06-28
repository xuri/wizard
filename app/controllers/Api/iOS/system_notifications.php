<?php

// Post user ID from App client
$id             = Input::get('id');

// Retrieve all system notifications
$notifications  = Notification::where('receiver_id', $id)
                    ->whereIn('category', array(8, 9))
                    ->orderBy('created_at' , 'desc')
                    ->select('id', 'sender_id', 'created_at')
                    ->where('status', 0)
                    ->get()
                    ->toArray();

$friend_notifications = Notification::where('receiver_id', $id)
                    ->whereIn('category', array(1, 2))
                    ->orderBy('created_at' , 'desc')
                    ->select('id', 'sender_id', 'created_at', 'receiver_id', 'category')
                    ->where('status', 0)
                    ->get()
                    ->toArray();

$accept_notifications = Notification::where('receiver_id', $id)
                    ->whereIn('category', array(3))
                    ->orderBy('created_at' , 'desc')
                    ->select('id', 'sender_id', 'created_at', 'receiver_id', 'category')
                    ->where('status', 0)
                    ->get()
                    ->toArray();

// Build array
foreach ($notifications as $key => $value) {
    $notifications_content              = NotificationsContent::where('notifications_id', $notifications[$key]['id'])->first();
    $notifications[$key]['content']     = $notifications_content->content;
    $notifications[$key]['created_at']  = date('m-d G:i', strtotime($notifications_content->created_at));

}

foreach ($friend_notifications as $key => $value) {
    switch ($friend_notifications[$key]['category']) {
        case '1' :
            $sender_user                            = User::find($friend_notifications[$key]['sender_id']);
            $like                                   = Like::where('sender_id', $friend_notifications[$key]['sender_id'])->where('receiver_id', $friend_notifications[$key]['receiver_id'])->first();
            $friend_notifications[$key]['content']  = app_out_filter($sender_user->nickname) . '追你了，快去看看吧';
            $friend_notifications[$key]['nickname'] = app_out_filter($sender_user->nickname);
            $friend_notifications[$key]['portrait'] = route('home') . '/' . 'portrait/' . $sender_user->portrait;
            $friend_notifications[$key]['answer']   = $like->answer;
            $friend_notifications[$key]['from']     = $friend_notifications[$key]['sender_id'];
            break;

        case '2' :
            $sender_user                            = User::find($friend_notifications[$key]['sender_id']);
            $like                                   = Like::where('sender_id', $friend_notifications[$key]['sender_id'])->where('receiver_id', $friend_notifications[$key]['receiver_id'])->first();
            $friend_notifications[$key]['content']  = app_out_filter($sender_user->nickname) . '再次追你了，快去看看吧';
            $friend_notifications[$key]['nickname'] = app_out_filter($sender_user->nickname);
            $friend_notifications[$key]['portrait'] = route('home') . '/' . 'portrait/' . $sender_user->portrait;
            $friend_notifications[$key]['answer']   = e($like->answer);
            $friend_notifications[$key]['from']     = $friend_notifications[$key]['sender_id'];
            break;
    }
}

// Build notifications
foreach ($accept_notifications as $key => $value) {
    $sender_user                            = User::find($accept_notifications[$key]['sender_id']);
    $accept                                 = Like::where('sender_id', $accept_notifications[$key]['sender_id'])->where('receiver_id', $accept_notifications[$key]['receiver_id'])->first();
    $accept_notifications[$key]['nickname'] = app_out_filter($sender_user->nickname);
    $accept_notifications[$key]['portrait'] = route('home') . '/' . 'portrait/' . $sender_user->portrait;
    $accept_notifications[$key]['from']     = $accept_notifications[$key]['sender_id'];
}

$data = array(
        'system_notifications'  => $notifications,
        'friend_notifications'  => $friend_notifications,
        'accept_notifications'  => $accept_notifications
    );

// Mark read for this user
Notification::where('receiver_id', $id)->update(array('status' => 1));

// Build Json format
return Response::json(
    array(
        'status'    => '1',
        'data'      => $data
    )
);
