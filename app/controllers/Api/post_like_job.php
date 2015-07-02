<?php

// Get user id from App client
$user_id            = Input::get('userid');
// Job's title
$title              = Input::get('title');
// Job's content
$content            = Input::get('content');
// Job's first rule (require)
$rule_1             = Input::get('rule_1');
// Job's second rule (require)
$rule_2             = Input::get('rule_2');
// Job's third rule (optional)
$rule_3             = Input::get('rule_3');
// Job's forth rule (optional)
$rule_4             = Input::get('rule_4');
// Job's fifth rule (optional)
$rule_5             = Input::get('rule_5');
// Create new like jobs
$like_jobs          = new LikeJobs;
$like_jobs->user_id = $user_id;
$like_jobs->title   = $title;
$like_jobs->content = $content;
$like_jobs->rule_1  = $rule_1;
$like_jobs->rule_2  = $rule_2;

if ($rule_3 != "") {
    $like_jobs->rule_3 = $rule_3;
}

if ($rule_4 != "") {
    $like_jobs->rule_4 = $rule_4;
}

if ($rule_5 != "") {
    $like_jobs->rule_5 = $rule_5;
}
// Determin create like jobs if successful
if ($like_jobs->save()) {
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
