<?php

// Determin user block status
if (User::find(Input::get('userid'))->block == 1) {

    // User is blocked forbidden post
    return Response::json(
        array(
            'status'        => 0,
            'repeat'        => 0
        )
    );

} else {
    // Determin repeat post
    $posts_exist = ForumPost::where('user_id', Input::get('userid'))
                    ->where('title', app_input_filter(Input::get('title')))
                    ->where('category_id', Input::get('catid'))
                    ->where('content', app_input_filter(Input::get('content')))
                    ->where('created_at', '>=', Carbon::today())
                    ->count();

    if ($posts_exist >= 1) {
        // User repeat post
        return Response::json(
            array(
                'status'        => 0,
                'repeat'        => 1
            )
        );
    } else {

        // Create new post
        $post               = new ForumPost;
        $post->category_id  = Input::get('catid');
        $post->user_id      = Input::get('userid');
        $post->title        = app_input_filter(Input::get('title'));
        $post->content      = app_input_filter(Input::get('content'));

        if($post->save()) {
            // Create successful
            return Response::json(
                array(
                    'status'        => 1,
                    'repeat'        => 0
                )
            );
        } else {
            // Create fail
            return Response::json(
                array(
                    'status'        => 0,
                    'repeat'        => 0
                )
            );
        }
    } // End of determin user block status
} // End of determin user block status
