<?php

// Get all form data.
$data = Input::all();

// Create validation rules
$rules = array(
    'phone'               => 'required|digits:11|unique:users',
    'password'            => 'required|between:6,16'
);

// Custom validation message
$messages = array(
    'phone.required'      => '请输入手机号码。',
    'phone.digits'        => '请输入正确的手机号码。',
    'phone.unique'        => '此手机号码已被使用。',
    'password.required'   => '请输入密码。',
    'password.between'    => '密码长度请保持在:min到:max位之间。'
);

// Begin verification
$validator   = Validator::make($data, $rules, $messages);
$phone       = Input::get('phone');
if ($validator->passes()) {

    // Verification success, add user
    $user               = new User;
    $user->phone        = $phone;

    // Signup from 1 - Android, 2 - iOS
    $user->from         = Input::get('from');
    $user->activated_at = date('Y-m-d G:i:s');
    $user->password     = md5(Input::get('password'));

    // Client set sex
    if (null !== Input::get('sex')) {
        $user->sex          = e(Input::get('sex'));
    }

    if ($user->save()) {
        $profile            = new Profile;
        $profile->user_id   = $user->id;
        $profile->save();

        // Add user success and chat Register
        $easemob            = getEasemob();

        // newRequest or newJsonRequest returns a Request object
        $regChat            = cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users', ['username' => $user->id, 'password' => $user->password])
            ->setHeader('content-type', 'application/json')
            ->setHeader('Accept', 'json')
            ->setHeader('Authorization', 'Bearer ' . $easemob->token)
            ->setOptions([CURLOPT_VERBOSE => true])
            ->send();

        // Respond body
        $result             = json_decode($regChat->body, true);

        if (isset($result['entities'])) {
            // Determine register status from Easemob
            //
            if ($result['entities']['0']['activated'] == true) {
                // Create floder to store chat record
                // File::makeDirectory(app_path('chatrecord/user_' . $user->id, 0777, true));

                // Redirect to a registration page, prompts user to activate
                // Signin success, redirect to the previous page that was blocked
                return Response::json(
                    array(
                        'status'    => 1,
                        'id'        => $user->id,
                        'password'  => $user->password
                    )
                );
            } else {
                // Add user success, but register fail in Easemob
                $user->forceDelete();
                $profile->forceDelete();

                return Response::json(
                    array(
                        'status'        => 0
                    )
                );
            }
        } else {
            // Add user success, but register fail in Easemob
            $user->forceDelete();
            $profile->forceDelete();

            return Response::json(
                array(
                    'status'        => 0
                )
            );
        }
    } else {
        // Add user success, but register fail in Easemob
        $user->forceDelete();
        $profile->forceDelete();

        return Response::json(
            array(
                'status'        => 0
            )
        );
    }
} else {
    // Verification fail
    $_validator = $validator->getMessageBag()->toArray();

    //  Checks if the phone key exists in the array
    if (array_key_exists('phone', $_validator)) {
        $phone_error = implode('', $_validator['phone']);
    } else {
        $phone_error = null;
    }

    //  Checks if the password key exists in the array
    if (array_key_exists('password', $_validator)) {
        $password_error = implode('', $_validator['password']);
    } else {
        $password_error = null;
    }

    // Verification fail
    return Response::json(
        array(
            'status'        => 0,
            'error'         => array(
                                    'phone'    => $phone_error,
                                    'password' => $password_error
                                )
        )
    );
}
