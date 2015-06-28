<?php

// Get all form data
$info = array(

    // Current signin user phone number on App
    'phone'   => Input::get('senderid'),

    // Which user want to see
    'user_id' => Input::get('userid'),
);

if ($info) {
    // Sender user ID
    $sender_id  = Input::get('senderid');
    $user_id    = Input::get('userid');
    $data       = User::find($user_id);
    $profile    = Profile::where('user_id', $user_id)->first();
    $like       = Like::where('sender_id', $sender_id)->where('receiver_id', $user_id)->first();
    $like_me    = Like::where('sender_id', $user_id)->where('receiver_id', $sender_id)->first();
    if ($like) {
        $likeCount = $like->count;
    } else {
        $likeCount = 0;
    }

    // Determine user renew status
    if ($profile->crenew >= 30) {
        $crenew = 1;
    } else {
        $crenew = 0;
    }

    if (is_null($like_me)) {
        // This user never liked you
        $user_like_me   = 5;
        $answer         = null;
    } else {
        // Determine users relationship, see code explanation in MembersController
        $user_like_me   = $like_me->status;
        // User liked answer
        $answer         = $like_me->answer;
    }

    // Get user's constellation
    $constellationInfo = getConstellation($profile->constellation);

    // Get user's tag
    if (is_null($profile->tag_str)) {
        $tag_str = [];
    } else {
        // Convert string to array and remove duplicate tags code
        $tag_str = array_merge(array_unique(explode(',', substr($profile->tag_str, 1))));
    }

    switch ($profile->salary) {

        case '0':
            $salary = '在校学生';
            break;

        case '1':
            $salary = '0-2000';
            break;

        case '2':
            $salary = '2000-5000';
            break;

        case '3':
            $salary = '5000-9000';
            break;

        case '4':
            $salary = '9000以上';
            break;

        default:
            $salary = '在校学生';
            break;
    }

    if ($data->province_id != "") {
        $province = Province::find($data->province_id)->province;
    } else {
        $province = '未设置所在地';
    }

    return Response::json(
        array(
            'status'        => 1,
            'user_id'       => e($data->id),
            'sex'           => e($data->sex),
            'bio'           => app_out_filter($data->bio),
            'nickname'      => app_out_filter($data->nickname),
            'born_year'     => e($data->born_year),
            'school'        => e($data->school),
            'is_admin'      => e($data->is_admin),
            'is_verify'     => e($data->is_verify),
            'portrait'      => route('home') . '/' . 'portrait/' . $data->portrait,
            'points'        => e($data->points),
            'constellation' => $constellationInfo['name'],
            'tag_str'       => $tag_str,
            'hobbies'       => app_out_filter($profile->hobbies),
            'grade'         => e($profile->grade),
            'question'      => app_out_filter($profile->question),
            'self_intro'    => app_out_filter($profile->self_intro),
            'like'          => e($likeCount),
            'user_like_me'  => e($user_like_me),
            'answer'        => app_out_filter($answer),
            'crenew'        => e($crenew),
            'salary'        => e($salary),
            'province'      => e($province),
        )
    );
} else {
    return Response::json(
        array(
            'status'        => 0
        )
    );
}
