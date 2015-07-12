<?php

// Get query ID from App client
$id      = Input::get('id');

// Get sender user data
$friends = Like::where('receiver_id', $id)->orWhere('sender_id', $id)
            ->where('status', 1)
            ->select('sender_id', 'receiver_id')
            ->get()
            ->toArray();

foreach ($friends as $key => $field) {

        // Determine user is sender or receiver
        if ($friends[$key]['sender_id'] == $id) {

            // User is sender and retrieve receiver user
            $user = User::where('id', $friends[$key]['receiver_id'])->first();

            // Friend ID
            $friends[$key]['friend_id'] = $user->id;

            // Friend nickname
            $friends[$key]['nickname']  = app_out_filter($user->nickname);

            // Determine user portrait
            if (is_null($user->portrait)) {

                // Friend portrait
                $friends[$key]['portrait']  = null;
            } else {

                // Friend portrait
                $friends[$key]['portrait']  = route('home') . '/' . 'portrait/' . $user->portrait;
            }
        } else {

            // User is receiver and retrieve sender user
            $user = User::where('id', $friends[$key]['sender_id'])->first();

            // Friend ID
            $friends[$key]['friend_id'] = $user->id;

            // Friend nickname
            $friends[$key]['nickname']  = app_out_filter($user->nickname);

            // Determine user portrait
            if (is_null($user->portrait)) {

                // Friend portrait
                $friends[$key]['portrait']  = null;
            } else {

                // Friend portrait
                $friends[$key]['portrait']  = route('home'). '/' . 'portrait/' . $user->portrait;
            }
        }
    }

// Query successful
if ($friends) {
    return Response::json(
        array(
            'status'    => 1,
            'data'      => $friends
        )
    );
} else {
    return Response::json(
        array(
            'status'    => 0
        )
    );
}
