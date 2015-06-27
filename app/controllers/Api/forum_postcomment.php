<?php

// Determin user block status
if (User::find(Input::get('userid'))->block == 1) {

    // User is blocked forbidden post
    return Response::json(
        array(
            'status'    => 0,
            'repeat'    => 0
        )
    );

} else {

    $user_id    = Input::get('userid');
    $post_id    = Input::get('postid');
    $content    = app_input_filter(Input::get('content'));
    $forum_post = ForumPost::where('id', $post_id)->first();
    $user       = User::find($user_id);

    // Select post type
    if (Input::get('type') == 'comments') {
        // Determin repeat comment
        $comment_exist = ForumComments::where('user_id', $user_id)
                        ->where('post_id', $post_id)
                        ->where('content', $content)
                        ->where('created_at', '>=', Carbon::today())
                        ->count();

        if ($comment_exist >= 1) {

            // Rpeat comment
            return Response::json(
                    array(
                        'status'    => 0,
                        'repeat'    => 1
                    )
                );

        } else {
            $forum_post->updated_at = Carbon::now();
            $forum_post->save();
            // Post comments
            $comment                = new ForumComments;
            $comment->post_id       = $post_id;
            $comment->content       = $content;
            $comment->user_id       = $user_id;

            // Calculate this comment in which floor
            $comment->floor         = ForumComments::where('post_id', $post_id)->where('block', false)->count() + 2;

            // Determin repeat add points
            $points_exist = ForumComments::where('user_id', $user_id)
                            ->where('created_at', '>=', Carbon::today())
                            ->count();
            // Add points
            if ($points_exist < 2) {
                $user->increment('points', 1);
            }

            if ($comment->save()) {
                // Determine sender and receiver
                if ($user_id != $forum_post->user_id) {

                    // Retrieve author of post
                    $post_author                = ForumPost::where('id', $post_id)->first();

                    // Retrieve forum notifications of post author
                    $post_author_notifications  = Notification::where('receiver_id', $post_author->user_id)->whereIn('category', array(6, 7))->where('status', 0);

                    $unread = $post_author_notifications->count() + 1;

                    // Add push notifications for App client to queue
                    Queue::push('ForumQueue', [
                                                'target'    => $post_author->user_id,
                                                'action'    => 6,
                                                'from'      => $user_id,

                                                // Notification content
                                                'content'   => '有人评论了你的帖子，快去看看吧',

                                                // Sender user ID
                                                'id'        => $user_id,

                                                // Count unread notofications of receiver user
                                                'unread'    => $unread
                                            ]);

                    // Create notifications
                    Notifications(6, $user_id, $forum_post->user_id, $forum_post->category_id, $post_id, $comment->id, null);
                }
                return Response::json(
                    array(
                        'status'    => 1,
                        'repeat'    => 0
                    )
                );
            } else {
                return Response::json(
                    array(
                        'status'    => 0,
                        'repeat'    => 0
                    )
                );
            }
        } // End of comment exist check
    } else {

        // Post reply
        $reply_id           = e(Input::get('replyid'));
        $comments_id        = e(Input::get('commentid'));

        // Determin repeat reply
        $reply_exist = ForumReply::where('user_id', $user_id)
                        ->where('reply_id', $reply_id)
                        ->where('comments_id', $comments_id)
                        ->where('content', $content)
                        ->where('created_at', '>=', Carbon::today())
                        ->count();

        if ($reply_exist >= 1) {

            // Rpeat reply
            return Response::json(
                array(
                    'status'    => 0,
                    'repeat'    => 1
                )
            );

        } else {
            // Create comments reply
            $reply              = new ForumReply;
            $reply->content     = $content;
            $reply->reply_id    = $reply_id;
            $reply->comments_id = $comments_id;
            $reply->user_id     = $user_id;

            // Calculate this reply in which floor
            $reply->floor       = ForumReply::where('comments_id', Input::get('commentid'))->where('block', false)->count() + 1;

            // Determin repeat add points
            $points_exist = ForumReply::where('user_id', $user_id)
                        ->where('created_at', '>=', Carbon::today())
                        ->count();

            // Add points
            if ($points_exist < 2) {
                $user->increment('points', 1);
            }

            if ($reply->save()) {

                // Retrieve comments
                $comment                        = ForumComments::where('id', $comments_id)->first();

                // Retrieve author of comment
                $comment_author                 = User::where('id', $comment->user_id)->first();

                // Retrieve forum notifications of comment author
                $comment_author_notifications   = Notification::where('receiver_id', $comment_author->id)->whereIn('category', array(6, 7))->where('status', 0);

                $unread = $comment_author_notifications->count() + 1;

                // Determine sender and receiver
                if ($user_id != $comment_author->id) {

                    // Add push notifications for App client to queue
                    Queue::push('ForumQueue', [
                                                'target'    => $comment_author->id,
                                                // category = 7 Some user reply your comments in forum (Get more info from app/controllers/MemberController.php)

                                                'action'    => 7,
                                                // Sender user ID

                                                'from'      => $user_id,
                                                // Notification content

                                                'content'   => '有人回复了你的评论，快去看看吧',
                                                // Sender user ID

                                                'id'        => $user_id,
                                                // Count unread notofications of receiver user
                                                'unread'    => $unread
                                            ]);

                    // Create notifications
                    Notifications(7, $user_id, $comment_author->id, $forum_post->category_id, $post_id, $comment->id, $reply->id);
                }

                // Reply success
                return Response::json(
                    array(
                        'status'    => 1,
                        'repeat'    => 0
                    )
                );
            } else {
                // Reply fail
                return Response::json(
                    array(
                        'status'    => 0,
                        'repeat'    => 0
                    )
                );
            }
        } // End of repeat reply check

    } // End of select post type
} // End of determin user block status
