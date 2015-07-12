<?php

// Get user ID from App client and retrieve User
$user          = User::find(Input::get('id'));
$notifications = Notification::where('receiver_id', $user->id)
                            ->whereIn('category', array(6, 7))
                            ->where('status', 0)
                            ->count();
if (is_null($notifications)) {

    // No unread notifications, build Json format
    return Response::json(
        array(
            'status'    => 1,
            'num'       => 0
        )
    );
} else {

    // Build Json format
    return Response::json(
        array(
            'status'    => 1,
            'num'       => $notifications
        )
    );
}
