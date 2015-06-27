<?php

// Post last user id from App client
$last_id    = Input::get('lastid');

// Post count per query from App client
$per_page   = Input::get('perpage');

// Get user id
$user_id    = Input::get('id');

// If App have post last user id
if ($last_id != 'null') {
    // Query all user liked users
    $allLike    = Like::where('receiver_id', $user_id)
        ->orderBy('id', 'desc')
        ->select('sender_id', 'status', 'created_at', 'count')
        ->where('id', '<', $last_id)
        ->take($per_page)
        ->get()
        ->toArray();

    // Replace sender_id key name to portrait
    foreach ($allLike as $key1 => $val1) {
        foreach ($val1 as $key => $val) {
            $new_key                = str_replace('sender_id', 'portrait', $key);
            $new_array[$new_key]    = $val;
        }
        $likes[] = $new_array;
    }

    // Replace receiver ID to receiver portrait
    foreach ($likes as $key => $field) {

        // Receiver ID
        $likes[$key]['id']          = $likes[$key]['portrait'];

        // Receiver avatar real storage path
        $likes[$key]['portrait']    = route('home') . '/' . 'portrait/' . User::where('id', $likes[$key]['portrait'])->first()->portrait;

        // Receiver school
        $likes[$key]['school']      = User::where('id', $likes[$key]['id'])->first()->school;

        // Receiver ID
        $likes[$key]['name']        = app_out_filter(User::where('id', $likes[$key]['id'])->first()->nickname);

        // Convert how long liked
        $Date_1                     = date('Y-m-d');

        // Current date and time
        $Date_2                     = date('Y-m-d', strtotime($likes[$key]['created_at']));
        $d1                         = strtotime($Date_1);
        $d2                         = strtotime($Date_2);

        // Calculate liked time
        $Days                       = round(($d1-$d2)/3600/24);
        $likes[$key]['created_at']  = $Days;
    }

    if ($allLike) {
        return Response::json(
            array(
                'status'    => 1,
                'data'      => $likes
            )
        );
    } else {
        return Response::json(
            array(
                'status'        => 0
            )
        );
    }
} else { // First get data from App client

    // Query last like id in database
    $lastRecord = Like::where('receiver_id', $user_id)->orderBy('id', 'desc')->first()->id;

    // Query all user liked users
    $allLike    = Like::where('receiver_id', $user_id)
        ->orderBy('id', 'desc')
        ->select('sender_id', 'status', 'created_at', 'count')
        ->where('id', '<=', $lastRecord)
        ->take($per_page)
        ->get()
        ->toArray();

    // Replace receiver_id key name to portrait
    foreach ($allLike as $key1 => $val1) {
        foreach ($val1 as $key => $val) {
            $new_key                = str_replace('sender_id', 'portrait', $key);
            $new_array[$new_key]    = $val;
        }
        $likes[] = $new_array;
    }

    // Replace receiver ID to receiver portrait
    foreach ($likes as $key => $field) {

        // Receiver ID
        $likes[$key]['id']          = $likes[$key]['portrait'];

        // Receiver avatar real storage path
        $likes[$key]['portrait']    = route('home') . '/' . 'portrait/' . User::where('id', $likes[$key]['portrait'])->first()->portrait;

        // Receiver school
        $likes[$key]['school']      = User::where('id', $likes[$key]['id'])->first()->school;

        // Receiver ID
        $likes[$key]['name']        = app_out_filter(User::where('id', $likes[$key]['id'])->first()->nickname);

        // Convert how long liked
        $Date_1                     = date('Y-m-d');

        // Current date and time
        $Date_2                     = date('Y-m-d', strtotime($likes[$key]['created_at']));
        $d1                         = strtotime($Date_1);
        $d2                         = strtotime($Date_2);

        // Calculate liked time
        $Days                       = round(($d1-$d2)/3600/24);
        $likes[$key]['created_at']  = $Days;
    }

    if ($allLike) {
        return Response::json(
            array(
                'status'    => 1,
                'data'      => $likes
            )
        );
    } else {
        return Response::json(
            array(
                'status'        => 0
            )
        );
    }
}
