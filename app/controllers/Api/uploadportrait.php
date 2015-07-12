<?php

// Retrieve user
$user           = User::where('id', Input::get('id'))->first();

// Old pritrait
$oldPortrait    = $user->portrait;

// Portrait data
$portrait       = Input::get('portrait');

// Portrait MIME
$mime           = Input::get('mime');

// User update avatar
if ($portrait != null) {
    $portrait           = str_replace('data:image/' . $mime . ';base64,', '', $portrait);
    $portrait           = str_replace(' ', '+', $portrait);
    $portraitData       = base64_decode($portrait);

    // Decode string
    $portraitPath       = public_path('portrait/');

    // Portrait file name
    $portraitFile       = uniqid() . '.' . $mime;

    // Store file
    $successPortrait    = file_put_contents($portraitPath . $portraitFile, $portraitData);

    // Save file name to database
    $user->portrait     = $portraitFile;

    if ($user->save()) {
        // Determine user portrait type
        $asset = strpos($oldPortrait, 'android');

        // Should to use !== false
        if ($asset !== false) {
            // No nothing
        } else {
            // User set portrait from web delete old poritait
            File::delete($portraitPath . $oldPortrait);
        }
        // Update success
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
}
