<?php

// Retrieve like job ID
$like_job_id = Input::get('id');

// Retrieve like job
$like_job    = LikeJobs::find($like_job_id);

// Determin if like job exists
if ($like_job) {
    // Retrieve user
    $user     = User::find($like_job->user_id);
    // Get user portrait
    $portrait = route('home') . '/' . 'portrait/' . $user->portrait;

    if ($like_job->rule_3 != "") {
        $rule_3 = "\n3. " .  $like_job->rule_3;
    } else {
        $rule_3 = null;
    }

    if ($like_job->rule_4 != "") {
        $rule_4 = "\n4. " .  $like_job->rule_4;
    } else {
        $rule_4 = null;
    }

    if ($like_job->rule_5 != "") {
        $rule_5 = "\n5. " .  $like_job->rule_5;
    } else {
        $rule_5 = null;
    }

    return Response::json(
            array(
                'status'   => 1, // Success
                'user_id'  => $user->id,
                'sex'      => $user->sex,
                'portrait' => $portrait,
                'title'    => e($like_job->title),
                'content'  => e($like_job->content . "\n\n 要求:\n1. " . $like_job->rule_1 . "\n2. " . $like_job->rule_2 . $rule_3 . $rule_4 . $rule_5),
            )
        );
} else {
    return Response::json(
            array(
                'status'    => 0 // Throw exception
            )
        );
}
