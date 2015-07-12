<?php

// User ID
$id = Input::get('id');

if (User::find($id)) {
    return Response::json(
        array(
            'status'        => 1
        )
    );
} else {
    return Response::json(
        array(
            'status'        => 0
        )
    );
}
