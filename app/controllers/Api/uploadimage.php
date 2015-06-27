<?php

// Get all json format data from App client and json decode data
$items  = json_decode(Input::get('data'));

// Create an empty array to store path of upload image
$path   = array();

// Foreach upload data
foreach ($items as $key => $item) {
    $image          = str_replace('data:image/' . $item['0'] . ';base64,', '', $item['1']);
    $image          = str_replace(' ', '+', $image);

    // Decode string
    $imageData      = base64_decode($image);

    // Define upload path
    $imagePath      = public_path('upload/image/android_upload/');

    // Portrait file name
    $imageFile      = uniqid() . '.' . $item['0'];

    // Store file
    $successUpload  = file_put_contents($imagePath . $imageFile, $imageData);
    $path[]['img']      = route('home') . '/upload/image/android_upload/' . $imageFile;
}
// Create success
return Response::json(
    array(
        'status'    => 1,
        'path'      => $path
    )
);
