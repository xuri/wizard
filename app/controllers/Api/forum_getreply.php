<?php

// Post forum post ID from App client
$post_id        = Input::get('postid');

// Post comment ID from App client
$comment_id     = Input::get('commentid');

// Retrieve comment
$comment        = ForumComments::where('id', $comment_id)->first();

// Retrieve comment user
$comment_author = User::where('id', $comment->user_id)->first();

// Retrieve reply
$replies = ForumReply::where('comments_id', $comment_id)
                ->select('id', 'user_id', 'content', 'created_at')
                ->orderBy('created_at' , 'asc')
                ->where('block', 0)
                ->get()
                ->toArray();

// Build Data Array
$data = array(

    // Post user portrait
    'user_portrait'     => route('home') . '/' . 'portrait/' . $comment_author->portrait,

    // Comment user sex
    'user_sex'          => $comment_author->sex,

    // Comment user nickname
    'user_nickname'     => app_out_filter($comment_author->nickname),

    // Comment user ID
    'user_id'           => $comment_author->id,

    // Comment ID
    'id'                => $comment->id,

    // Comment title
    'title'             => app_out_filter($comment->title),

    // Comment created date
    'created_at'        => $comment->created_at->toDateTimeString(),

    // Comment content (removing contents html tags except image and text string)
    'content'           => app_out_filter($comment->content),

    // Post comments reply (array format and include reply)
    'comment_reply'     => $replies
);

// Build Json format
return Response::json(
    array(
        'status'    => 1,
        'data'      => $data
    )
);
