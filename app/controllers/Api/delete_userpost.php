<?php

// Get post ID in forum for delete
$postId     = Input::get('postid');

// Retrieve post
$forumPost  = ForumPost::where('id', $postId)->first();

// Using expression get all picture attachments (Only with pictures stored on this server.)
preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $forumPost->content, $match );

// Construct picture attachments list
$srcArray   = array_pop($match);

// This post have picture attachments
if (!empty( $srcArray )) {
    // Foreach picture attachments list array
    foreach ($srcArray as $key => $field) {

        // Convert to correct real storage path
        $srcArray[$key] = str_replace(route('home'), '', $srcArray[$key]);

        // Destory upload picture attachments in this post
        File::delete(public_path($srcArray[$key]));
    }

    // Delete post in forum
    if ($forumPost->delete()) {
        return Response::json(
            array(
                'status'    => 1
            )
        );
    } else {
        return Response::json(
            array(
                'status'    => 0
            )
        );
    }
} else {

    // Delete post in forum
    if ($forumPost->delete()) {
        return Response::json(
            array(
                'status'    => 1
            )
        );
    } else {
        return Response::json(
            array(
                'status'    => 0
            )
        );
    }

}
