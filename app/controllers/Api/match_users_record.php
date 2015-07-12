<?php

// Get user ID
$user_id                          = Input::get('id');
// Get all match users ID
$match_users_id                   = Input::get('match_users_id');

// Determin user match if empty
if ($match_users_id != "") {
    // Retrieve user profile
    $profile                      =  Profile::where('user_id', $user_id)->first();

    if (is_null($profile->match_users)) {
        $profile->match_users     = $match_users_id;
    } else {
        if ($match_users_id != "") {
            $profile->match_users = $profile->match_users . ',' . $match_users_id;
        }
    }

    $profile->match               = $profile->match + count(explode(',', $match_users_id));
    // Update last match time
    $profile->match_at            = Carbon::now();

    if ($profile->save()) {
        // Add points
        if ($profile->match = 3) {
            User::find($user_id)->increment('points', 1);
        }

        return Response::json(
            array(
                'status'    => 1 // Success
            )
        );
    } else {
        return Response::json(
            array(
                'status'    => 0 // Throw exception
            )
        );
    }
} else {
    return Response::json(
        array(
            'status'        => 1 // Success
        )
    );
}
