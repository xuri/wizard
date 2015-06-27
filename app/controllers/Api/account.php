<?php

// Get all form data
$info = array(
    'id'   => Input::get('id'),
);

if ($info) {
    // Retrieve user
    $user               = User::find(Input::get('id'));
    $profile            = Profile::where('user_id', $user->id)->first();

    // Get user's constellation
    $constellationInfo  = getConstellation($profile->constellation);

    // Get user's tag
    if (is_null($profile->tag_str)) {
        $tag_str = [];
    } else {
        // Convert string to array and remove duplicate tags code
        $tag_str = array_merge(array_unique(explode(',', substr($profile->tag_str, 1))));
    }

    $data = array(
            'status'        => 1,
            'sex'           => e($user->sex),
            'bio'           => app_out_filter($user->bio),
            'nickname'      => app_out_filter($user->nickname),
            'born_year'     => e($user->born_year),
            'school'        => e($user->school),
            'portrait'      => route('home') . '/' . 'portrait/' . $user->portrait,
            'constellation' => $constellationInfo['name'],
            'hobbies'       => app_out_filter($profile->hobbies),
            'tag_str'       => $tag_str,
            'grade'         => e($profile->grade),
            'question'      => app_out_filter($profile->question),
            'self_intro'    => app_out_filter($profile->self_intro),
            'is_verify'     => e($user->is_verify),
            'points'        => $user->points
        );
    return Response::json($data);
} else {
    return Response::json(
        array(
            'status'        => 0
        )
    );
}
