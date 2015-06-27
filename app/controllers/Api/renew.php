<?php

if (Input::get('dorenew') != 'null') {
    $renew      = Input::get('renew');
    $today      = Carbon::today();
    $yesterday  = Carbon::yesterday();
    $user       = Profile::where('user_id', Input::get('id'))->first();
    $points     = User::where('id', Input::get('id'))->first();

    if ($user->renew_at == '0000-00-00 00:00:00') { // First renew
        $user->renew_at = Carbon::now();
        $user->renew    = $user->renew + 1;
        $user->crenew   = $user->crenew + 1;
        $points->points = $points->points + 5;
        $user->save();
        $points->save();

        return Response::json(
            array(
                'status'        => 1,
                'renewdays'     => e($user->renew)
            )
        );
    } elseif ($today >= $user->renew_at) {

        // Check user whether or not renew yesterday
        if ($yesterday <= $user->renew_at) {
            // Keep renew
            $user->crenew   = $user->crenew + 1;
        } else {
            // Not keep renew, reset renew count
            $user->crenew   = 0;
        }

        // You haven't renew today
        $user->renew_at = Carbon::now();
        $user->renew    = $user->renew + 1;
        $points->points = $points->points + 1;
        $user->save();
        $points->save();

        return Response::json(
            array(
                'status'        => 1,
                'renewdays'     => e($user->renew)
            )
        );
    } else {
        // You have renew today
        return Response::json(
            array(
                'status'        => 2
            )
        );
    }
} else {
    return Response::json(
        array(
            'status'    => 1,
            'renewdays' => e(Profile::where('user_id', Input::get('id'))->first()->renew)
        )
    );
}
