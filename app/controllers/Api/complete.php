<?php

// Get all form data
$info = array(
    'nickname'      => Input::get('nickname'),
    'constellation' => Input::get('constellation'),
    'portrait'      => Input::get('portrait'),
    'tag_str'       => Input::get('tag_str'),
    'born_year'     => Input::get('born_year'),
    'grade'         => Input::get('grade'),
    'hobbies'       => Input::get('hobbies'),
    'self_intro'    => Input::get('self_intro'),
    'bio'           => Input::get('bio'),
    'question'      => Input::get('question'),
    'school'        => Input::get('school'),
);

// Create validation rules
$rules = array(
    'nickname'      => 'required|between:1,30',
    'constellation' => 'required',
    'tag_str'       => 'required',
    'born_year'     => 'required',
    'grade'         => 'required',
    'hobbies'       => 'required',
    'self_intro'    => 'required',
    'bio'           => 'required',
    'question'      => 'required',
    'school'        => 'required',
);

// Custom validation message
$messages = array(
    'nickname.required'         => '请输入昵称',
    'nickname.between'          => '昵称长度请保持在:min到:max字之间',
    'constellation.required'    => '请选择星座',
    'tag_str.required'          => '给自己贴个标签吧',
    'born_year.required'        => '请选择出生年',
    'grade.required'            => '请选择入学年',
    'hobbies.required'          => '填写你的爱好',
    'self_intro.required'       => '请填写个人简介',
    'bio.required'              => '请填写你的真爱寄语',
    'question.required'         => '记得填写爱情考验哦',
    'school.required'           => '请选择所在学校',
);

// Begin verification
$validator = Validator::make($info, $rules, $messages);

if ($validator->passes()) {
    // Verification success
    // Update account
    $user                   = User::where('id', Input::get('id'))->first();
    $oldPortrait            = $user->portrait;
    $user->nickname         = app_input_filter(Input::get('nickname'));

    // Protrait section
    $portrait               = Input::get('portrait');

    if ($portrait == null) {
        $user->portrait     = $oldPortrait;  // User not update avatar
    } else {
        // User update avatar
        $portraitPath       = public_path('portrait/');
        $user->portrait     = 'android/' . $portrait; // Save file name to database
    }

    if (is_null($user->sex)) {
        $user->sex          = Input::get('sex');
    }

    if (is_null($user->born_year)) {
        $user->born_year    = Input::get('born_year');
    }

    $user->bio              = app_input_filter(Input::get('bio'));
    $school                 = Input::get('school');

    if (is_null($user->school)) {
        // First set school
        University::where('university', $school)->increment('count');
    } else {
        if ($user->school != $school) {
            University::where('university', $school)->increment('count');
            University::where('university', $user->school)->decrement('count');
        }
    }
    $user->school           = $school;

    // Update profile information
    $profile                = Profile::where('user_id', $user->id)->first();
    $profile->tag_str       = implode(',', array_unique(explode(',', Input::get('tag_str'))));
    $profile->grade         = Input::get('grade');
    $profile->hobbies       = app_input_filter(Input::get('hobbies'));
    $profile->self_intro    = app_input_filter(Input::get('self_intro'));
    $profile->question      = app_input_filter(Input::get('question'));

    // User's constellation filter
    if (Input::get('constellation') != 0) {
        $profile->constellation = e(Input::get('constellation'), NULL);
    }

    if ($user->save() && $profile->save()) {

        // Update success
        if ($portrait != null) { // User update avatar
            // Determine user portrait type
            $asset = strpos($oldPortrait, 'android');

            // Should to use !== false
            if ($asset !== false) {
                // No nothing
            } else {
                // User set portrait from web delete old poritait
                File::delete($portraitPath . $oldPortrait);
            }

        }
        return Response::json(
            array(
                'status'    => 1
            )
        );

    } else {
        // Update fail
        return Response::json(
            array(
                'status'    => 0
            )
        );
    }
} else {
    // Verification fail, redirect back
    return Response::json(
        array(
            'status'        => 0,
            'info'          => $validator
        )
    );
}
