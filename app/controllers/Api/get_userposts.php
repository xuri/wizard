<?php

// Post user ID from App client
$user_id    = Input::get('id');

// Retrieve user
$user       = User::find($user_id);

// Get all post of this user
$posts      = ForumPost::where('user_id', $user_id)
                ->orderBy('created_at', 'desc')
                ->select('id', 'title', 'created_at')
                ->where('block', 0)
                ->get()
                ->toArray();

// Build format
foreach ($posts as $key => $value) {

    // Query how many comment of this post
    $posts[$key]['comments_count'] = ForumComments::where('post_id', $posts[$key]['id'])->where('block', false)->count();
}

// Build format
$data = array(
        'portrait'      => route('home') . '/' . 'portrait/' . $user->portrait,
        'nickname'      => app_out_filter($user->nickname),
        'posts_count'   => ForumPost::where('user_id', $user_id)->where('block', false)->count(),
        'posts'         => $posts
    );

// Build Json format
return Response::json(
    array(
        'status'    => 1,
        'data'      => $data
    )
);
