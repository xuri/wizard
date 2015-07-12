<?php

// Get all form data.
$data   = Input::all();

// Retrieve user
$user   = User::find(Input::get('id'));

// Determin user portrait is set
if (isset($user->portrait)) {

    // Determin user profile is complete
    if (isset($user->nickname) && isset($user->school) && isset($user->bio)) {

        // Create validation rules
        $rules  = array(
            'id'            => 'required',
            'receiverid'    => 'required',
            // 'answer'     => 'required|min:3',
        );

        // Custom validation message
        $messages = array(
            'answer.required'   => '请回答爱情考验问题。',
            // 'answer.min'     => '至少要写:min个字哦。',
        );

        // Begin verification
        $validator   = Validator::make($data, $rules, $messages);

        if ($validator->passes()) {
            $user           = User::find(Input::get('id'));
            $receiver_id    = Input::get('receiverid');

            if ($user->points >= 0) {
                $have_like = Like::where('sender_id', $user->id)->where('receiver_id', $receiver_id)->first();

                // This user already sent like
                if ($have_like) {
                    $have_like->answer  = app_input_filter(Input::get('answer'));
                    $have_like->count   = $have_like->count + 1;
                    // $user->points       = $user->points - 1;

                    if ($have_like->save() && $user->save()) {

                        // Some user re-liked you
                        $notification = Notification(2, $user->id, $receiver_id);

                        // Add push notifications for App client to queue
                        Queue::push('LikeQueue', [
                                                    'target'    => $receiver_id,
                                                    'action'    => 2,
                                                    'from'      => $user->id,

                                                    // Notification ID
                                                    'id'        => e($notification->id),
                                                    'content'   => app_out_filter($user->nickname) . '再次追你了，快去查看一下吧',
                                                    'sender_id' => e(Input::get('id')),
                                                    'portrait'  => route('home') . '/' . 'portrait/' . $user->portrait,
                                                    'nickname'  => app_out_filter($user->nickname),
                                                    'answer'    => app_out_filter(Input::get('answer'))
                                                ]);

                        return Response::json(
                            array(
                                'status'        => 1
                            )
                        );
                    }
                } else { // First like
                    $like               = new Like();
                    $like->sender_id    = $user->id;
                    $like->receiver_id  = $receiver_id;
                    $like->status       = 0; // User send like, pending accept
                    $like->answer       = app_input_filter(Input::get('answer'));
                    $like->count        = 1;
                    // $user->points       = $user->points - 1;

                    // Determin repeat add points
                    $points_exist = Like::where('receiver_id', $receiver_id)
                                ->where('created_at', '>=', Carbon::today())
                                ->count();

                    // Add points
                    if ($points_exist < 2) {
                        User::find($receiver_id)->increment('points', 1);
                    }

                    if ($like->save() && $user->save()) {
                        $notification = Notification(1, $user->id, $receiver_id); // Some user first like you

                        // Add push notifications for App client to queue
                        Queue::push('LikeQueue', [
                                                    'target'    => $receiver_id,
                                                    'action'    => 1,
                                                    'from'      => $user->id,
                                                    'content'   => app_out_filter($user->nickname) . '追你了，快去查看一下吧',

                                                    // Notification ID
                                                    'id'        => e($notification->id),
                                                    'sender_id' => e(Input::get('id')),
                                                    'portrait'  => route('home') . '/' . 'portrait/' . $user->portrait,
                                                    'nickname'  => app_out_filter($user->nickname),
                                                    'answer'    => app_out_filter(Input::get('answer'))
                                                ]);

                        return Response::json(
                            array(
                                'status'        => 1
                            )
                        );
                    }
                }
            } else {
                return Response::json(
                    array(
                        'status'    => 2 // User's point required
                    )
                );
            }
        } else {
            return Response::json(
                array(
                    'status'        => 0
                )
            );
        }
    } else {
        return Response::json(
            array(
                // User profile uncompleted
                'status'        => 3
            )
        );
    }
} else {
    return Response::json(
        array(
            // User portrait not set
            'status'        => 4
        )
    );
}
