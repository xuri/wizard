<?php

// Retrieve
$user = User::find(Input::get('id'));

if ($user) {
    // User exist
    return Response::json(
        array(
            'status'    => 1,
            'nickname'  => app_out_filter($user->nickname),
            'portrait'  => route('home') . '/' . 'portrait/' . $user->portrait,
            'points'    => $user->points,
            'is_verify' => e($user->is_verify)
        )
    );
} else {
    // User not exist
    return Response::json(
        array(
            'status'    => 0
        )
    );
}
