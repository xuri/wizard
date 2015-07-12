<?php

// Retrieve user
$user           = User::where('id', Input::get('id'))->first();

// Old portrait
$oldPortrait    = $user->portrait;

// Get user portrait name
$portrait       = Input::get('portrait');

// User not update portrait
if ($portrait == $oldPortrait) {
    // Direct return success
    return Response::json(
        array(
            'status'        => 1
        )
    );
} else {

    // User update avatar
    $portraitPath       = public_path('portrait/');

    // Save file name to database
    $user->portrait     = 'android/' . $portrait;

    if ($user->save()) {
        // Update success
        $oldAndroidPortrait = strpos($oldPortrait, 'android');
        if($oldAndroidPortrait === false) { // Must use ===
            // Delete old poritait
            File::delete($portraitPath . $oldPortrait);
            return Response::json(
                array(
                    'status'    => 1
                )
            );
        } else {
            // Update success
            return Response::json(
                array(
                    'status'    => 1
                )
            );
        }
    } else {
        // Update fail
        return Response::json(
            array(
                'status'    => 0
            )
        );
    }
}
