<?php

// Get feedback user id
$user_id            = Input::get('id');

// Format feedback content
$feedback           = app_input_filter(Input::get('content'));

// Check feedback exist
$feedback_exist     = Support::where('user_id', $user_id)
                        ->where('content', $feedback)
                        ->where('created_at', '>=', Carbon::today())
                        ->count();

// User already send feedback today
if ($feedback_exist >= 1) {
    return Response::json(
        array(
            'status'            => 0,
            'empty'             => 0,
            'repeat'            => 1
        )
    );
} else {
    if ($feedback != "") {
        $support            = new Support;
        $support->user_id   = $user_id;
        $support->content   = $feedback;
        if ($support->save()) {
            return Response::json(
                array(
                    'status'        => 1,
                    'empty'         => 0,
                    'repeat'        => 0
                )
            );
        } else {
            return Response::json(
                array(
                    'status'        => 0,
                    'empty'         => 0,
                    'repeat'        => 0
                )
            );
        }
    } else {
        return Response::json(
            array(
                'status'            => 0,
                'empty'             => 1,
                'repeat'            => 0
            )
        );
    }
}
