<?php

// Credentials
$credentials = array(
    'email'     => Input::get('phone'),
    'password'  => md5(Input::get('password')
));
$phone_credentials = array(
    'phone'     => Input::get('phone'),
    'password'  => md5(Input::get('password')
));
$w_id_credentials = array(
    'w_id'      => Input::get('phone'),
    'password'  => md5(Input::get('password')
));

if (Auth::attempt($credentials) || Auth::attempt($phone_credentials) || Auth::attempt($w_id_credentials)) {

    // Retrieve user
    $user = User::where('phone', Input::get('phone'))->orWhere('email', Input::get('phone'))->orWhere('w_id', Input::get('phone'))->first();

    // Update reveiver_updated_at in like table
    DB::table('like')->where('receiver_id', $user->id)->update(array('receiver_updated_at' => Carbon::now()));

    // Signin success, redirect to the previous page that was blocked
    return Response::json(
        array(
            'status'    => 1,
            'id'        => $user->id,
            'password'  => $user->password,
            'sex'       => $user->sex,
            'portrait'  => route('home') . '/' . 'portrait/' . $user->portrait
        )
    );
} else {
    return Response::json(
        array(
            'status'        => 0
        )
    );
}
