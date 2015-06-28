<?php

$postid     = Input::get('postid');

$lastid     = Input::get('lastid');

$perpage    = Input::get('perpage', 10);

// If App have post last user id
if ($lastid == null) {

    // First get data from App client and Retrieve post data
    $post       = ForumPost::where('id', $postid)->first();

    // Determine forum post exist
    if (is_null($post)) {

        // Build Json format
        return Response::json(
            array(
                'status'    => 2
            )
        );

    } else {

        // Retrieve user data of this post
        $author     = User::where('id', $post->user_id)->first();

        // Get last record from database
        $lastRecord = ForumComments::orderBy('id', 'desc')->first();

        // Determine forum comments exist
        if (is_null($lastRecord)) {

            // Build Data Array
            $data = array(

                // Post user portrait
                'portrait'      => route('home') . '/' . 'portrait/' . $author->portrait,

                // Post user sex
                'sex'           => e($author->sex),

                // Post user nickname
                'nickname'      => app_out_filter($author->nickname),

                // Post user ID
                'user_id'       => $author->id,

                // Post comments count
                'comment_count' => ForumComments::where('post_id', $postid)->where('block', false)->get()->count(),

                // Post created date
                'created_at'    => $post->created_at->toDateTimeString(),

                // Post content (removing contents html tags except image and text string)
                'content'       => app_out_filter($post->content),

                // Post comments (array format and include reply)
                'comments'      => array(),

                // Post title
                'title'         => app_out_filter($post->title)

            );

            // Build Json format
            return Response::json(
                array(
                    'status'    => '1',
                    'data'      => $data
                )
            );

        } else {

            // Query all comments of this post
            $comments   = ForumComments::where('post_id', $postid)
                                ->orderBy('created_at' , 'asc')
                                ->where('id', '<=', $lastRecord->id)
                                ->where('block', 0)
                                ->select('id', 'user_id', 'content', 'created_at')
                                ->take($perpage)
                                ->get()
                                ->toArray();

            // Build comments array and include reply information
            foreach ($comments as $key => $field) {

                // Retrieve comments user
                $comments_user                      = User::where('id', $comments[$key]['user_id'])->first();

                // Comments user ID
                $comments[$key]['user_id']          = $comments_user->id;

                // Removing contents html tags except image and text string
                $comments[$key]['content']          = app_out_filter($comments[$key]['content']);
                // Comments user portrait
                $comments[$key]['user_portrait']    = route('home') . '/' . 'portrait/' . $comments_user->portrait;

                // Comments user sex
                $comments[$key]['user_sex']         = e($comments_user->sex);

                // Comments user nickname
                $comments[$key]['user_nickname']    = app_out_filter($comments_user->nickname);

                // Query all replies of this post
                $replies = ForumReply::where('comments_id', $comments[$key]['id'])
                            ->select('id', 'user_id', 'content', 'created_at')
                            ->orderBy('created_at' , 'asc')
                            ->where('block', 0)
                            ->take(3)
                            ->get()
                            ->toArray();

                // Calculate total replies of this post
                $comments[$key]['reply_count'] = ForumReply::where('comments_id', $comments[$key]['id'])->where('block', false)->count();

                // Build reply array
                foreach ($replies as $keys => $field) {

                    // Retrieve reply user
                    $reply_user                 = User::where('id', $replies[$keys]['user_id'])->first();

                    // Reply user sex
                    $replies[$keys]['sex']      = $reply_user->sex;

                    $replies[$keys]['content']  = app_out_filter($replies[$keys]['content']);

                    // Reply user portrait
                    $replies[$keys]['portrait'] = route('home') . '/' . 'portrait/' . $reply_user->portrait;
                }

                // Add comments replies array to post comments_reply array
                $comments[$key]['comment_reply'] = $replies;

            }

            // Build Data Array
            $data = array(

                // Post user portrait
                'portrait'      => route('home') . '/' . 'portrait/' . $author->portrait,

                // Post user sex
                'sex'           => e($author->sex),

                // Post user nickname
                'nickname'      => app_out_filter($author->nickname),

                // Post user ID
                'user_id'       => $author->id,

                // Post comments count
                'comment_count' => ForumComments::where('post_id', $postid)->where('block', false)->get()->count(),

                // Post created date
                'created_at'    => $post->created_at->toDateTimeString(),

                // Post content (removing contents html tags except image and text string)
                'content'       => app_out_filter($post->content),

                // Post comments (array format and include reply)
                'comments'      => $comments,

                // Post title
                'title'         => app_out_filter($post->title)

            );

            // Build Json format
            return Response::json(
                array(
                    'status'    => '1',
                    'data'      => $data
                )
            );
        }
    }

} else {

    // First get data from App client and Retrieve post data
    $post       = ForumPost::where('id', $postid)->first();

    // Determine forum post exist
    if (is_null($post)) {

        // Build Json format
        return Response::json(
            array(
                'status'    => 2
            )
        );

    } else {
        // Query all comments of this post
        $comments   = ForumComments::where('post_id', $postid)
                            ->orderBy('id' , 'asc')
                            ->where('id', '>', $lastid)
                            ->where('block', 0)
                            ->select('id', 'user_id', 'content', 'created_at')
                            ->take($perpage)
                            ->get()
                            ->toArray();

        // Build comments array and include reply information
        foreach ($comments as $key => $field) {

            // Retrieve comments user
            $comments_user                      = User::where('id', $comments[$key]['user_id'])->first();

            // Comments user ID
            $comments[$key]['user_id']          = $comments_user->id;

            // Comments user portrait
            $comments[$key]['user_portrait']    = route('home') . '/' . 'portrait/' . $comments_user->portrait;

            // Comments user sex
            $comments[$key]['user_sex']         = e($comments_user->sex);

            // Comments user nickname
            $comments[$key]['user_nickname']    = app_out_filter($comments_user->nickname);

            // Removing contents html tags except image and text string
            $comments[$key]['content']          = app_out_filter($comments[$key]['content']);

            // Query all replies of this post
            $replies = ForumReply::where('comments_id', $comments[$key]['id'])
                        ->select('id', 'user_id', 'content', 'created_at')
                        ->orderBy('created_at' , 'desc')
                        ->where('block', 0)
                        ->take(3)
                        ->get()
                        ->toArray();

            // Calculate total replies of this post
            $comments[$key]['reply_count'] = ForumReply::where('comments_id', $comments[$key]['id'])->where('block', false)->count();

            // Build reply array
            foreach ($replies as $keys => $field) {

                // Retrieve reply user
                $reply_user                 = User::where('id', $replies[$keys]['user_id'])->first();

                // Reply user sex
                $replies[$keys]['sex']      = e($reply_user->sex);

                $replies[$keys]['content']  = app_out_filter($replies[$keys]['content']);

                // Reply user portrait
                $replies[$keys]['portrait'] = route('home') . '/' . 'portrait/' . $reply_user->portrait;
            }

            // Add comments replies array to post comments_reply array
            $comments[$key]['comment_reply'] = $replies;
        }

        // Build Data Array
        $data = array(

            // Post comments (array format and include reply)
            'comments'      => $comments
        );

        // Build Json format
        return Response::json(
            array(
                'status'    => '1',
                'data'      => $data
            )
        );
    }
}
