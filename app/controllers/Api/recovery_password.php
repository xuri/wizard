<?php

// Retrieve user
if ($user = User::where('phone', Input::get('phone'))->first()) {

    // Update user password
    $user->password = md5(Input::get('password'));

    // Update successful
    if ($user->save()) {

        // Update user password in easemob
        $easemob            = getEasemob();

        // newRequest or newJsonRequest returns a Request object
        $regChat            = cURL::newJsonRequest('put', 'https://a1.easemob.com/jinglingkj/pinai/users/' . $user->id . '/password', ['newpassword' => $user->password])
            ->setHeader('content-type', 'application/json')
            ->setHeader('Accept', 'json')
            ->setHeader('Authorization', 'Bearer '.$easemob->token)
            ->setOptions([CURLOPT_VERBOSE => true])
            ->send();

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
    // Can't find user
    return Response::json(
        array(
            'status'    => 2
        )
    );
}
