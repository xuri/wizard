<?php

// Post last user id from App client
$last_id    = Input::get('lastid');

// Post count per query from App client
$per_page   = Input::get('perpage');

// Get user id
$user_id    = Input::get('id');

// If App have post last user id
if ($last_id) {
    // Query all user liked users
    $allLike    = Like::where('sender_id', $user_id)
        ->orderBy('id', 'desc')
        ->select('receiver_id', 'status', 'created_at', 'count')
        ->where('id', '<', $last_id)
        ->take($per_page)
        ->get()
        ->toArray();

    // Replace receiver_id key name to portrait
    foreach ($allLike as $key1 => $val1) {
        foreach ($val1 as $key => $val) {
            $new_key                = str_replace('receiver_id', 'portrait', $key);
            $new_array[$new_key]    = $val;
        }
        $likes[] = $new_array;
    }

    // Replace receiver ID to receiver portrait
    foreach ($likes as $key => $field) {

        // Retrieve receiver user
        $user                       = User::where('id',  $likes[$key]['portrait'])->first();

        // Receiver ID
        $likes[$key]['id']          = e($user->id);

        // Receiver avatar real storage path
        $likes[$key]['portrait']    = route('home') . '/' . 'portrait/' . $user->portrait;

        // Receiver school
        $likes[$key]['school']      = e($user->school);

        // Receiver nickname
        $likes[$key]['name']        = app_out_filter($user->nickname);

        // Receiver sex
        $likes[$key]['sex']         = e($user->sex);

        // Convert how long liked
        $Date_1                     = date('Y-m-d'); // Current date and time
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
                'status'    => 0
            )
        );
    }
} else {

    // First get data from App client and query last like id in database
    $lastRecord = Like::where('sender_id', $user_id)->orderBy('id', 'desc')->first();

    // Determin like exist
    if (is_null($lastRecord)) {
        return Response::json(
            array(
                'status'    => 1,
                'data'      => array()
            )
        );
    } else {

        // Query all user liked users
        $allLike    = Like::where('sender_id', $user_id)
            ->orderBy('id', 'desc')
            ->select('receiver_id', 'status', 'created_at', 'count')
            ->where('id', '<=', $lastRecord->id)
            ->take($per_page)
            ->get()
            ->toArray();

        // Replace receiver_id key name to portrait
        foreach ($allLike as $key1 => $val1) {
            foreach ($val1 as $key => $val) {
                $new_key                = str_replace('receiver_id', 'portrait', $key);
                $new_array[$new_key]    = $val;
            }
            $likes[] = $new_array;
        }

        // Replace receiver ID to receiver portrait
        foreach ($likes as $key => $field) {

            // Retrieve receiver user
            $user                       = User::where('id',  $likes[$key]['portrait'])->first();

            // Receiver ID
            $likes[$key]['id']          = e($user->id);

            // Receiver avatar real storage path
            $likes[$key]['portrait']    = route('home') . '/' . 'portrait/' . $user->portrait;

            // Receiver school
            $likes[$key]['school']      = e($user->school);

            // Receiver nickname
            $likes[$key]['name']        = app_out_filter($user->nickname);

            // Receiver sex
            $likes[$key]['sex']         = e($user->sex);

            // Convert how long liked
            // Current date and time
            $Date_1                     = date('Y-m-d');
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
}
