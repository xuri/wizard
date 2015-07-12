<?php

// Post user ID from App client
$user_id            = Input::get('userid');

// Post last user ID from App client
$last_id            = Input::get('lastid');

// Post count per query from App client
$per_page           = Input::get('perpage');

// Post category ID from App client
$cat_id             = Input::get('catid');

// Post number chars of post summary from App client
$numchars           = Input::get('numchars', 200);

// If App have post last user id
if ($last_id != 'null') {

    // Get last post updated at
    $last_updated_at    = ForumPost::where('id', $last_id)->first()->updated_at;

    // Query all items from database
    $items  = ForumPost::where('category_id', $cat_id)
                ->orderBy('updated_at' , 'desc')
                ->where('updated_at', '<', $last_updated_at)
                ->where('top', 0)
                ->where('block', 0)
                ->select('id', 'user_id', 'title', 'content', 'created_at')
                ->take($per_page)
                ->get()
                ->toArray();

    // Replace receiver ID to receiver portrait
    foreach ($items as $key => $field) {

        // Count how many comments of this post
        $comments_count                 = ForumComments::where('post_id', $items[$key]['id'])->where('block', false)->count();

        // Retrieve all comments to array
        $comments_array                 = ForumComments::where('post_id', $items[$key]['id'])->where('block', false)->select('id')->get()->toArray();

        // Init replies count
        $replies_count                  = 0;

        // Calculate total replies of this post
        foreach ($comments_array as $comments_array_key => $value) {
            $replies_count  = $replies_count + ForumReply::where('comments_id', $value['id'])->where('block', false)->count();
        }

        // Retrieve user
        $post_user                      = User::where('id', $items[$key]['user_id'])->first();

        // Get post user portrait real storage path and user porirait key to array
        $items[$key]['portrait']        = route('home') . '/' . 'portrait/' . $post_user->portrait;

        // Get post user sex (M, F or null) and add user sex key to array
        $items[$key]['sex']             = e($post_user->sex);

        // Count how many comments of this post and add comments_count key to array
        $items[$key]['comments_count']  = e($comments_count + $replies_count);

        // Get post user portrait and add portrait key to array
        $items[$key]['nickname']        = app_out_filter($post_user->nickname);

        // Using expression get all picture attachments (Only with pictures stored on this server.)
        preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $items[$key]['content'], $match );

        // Construct picture attachments list and add thumbnails (array format) to array
        $items[$key]['thumbnails']      = join(',', array_pop($match));

        // Get plain text from post content HTML code and replace to content value in array
        $items[$key]['content']         = app_out_filter(str_ireplace("\n", '', getplaintextintrofromhtml($items[$key]['content'], $numchars)));

        // Get forum title
        $items[$key]['title']           = app_out_filter(Str::limit($items[$key]['title'], 35));
    }

    // Build Json format
    return Response::json(
        array(
            'status'    => 1,
            'data'      => array(
                                'top'   => array(),
                                'items' => $items
                            )
        )
    );

} else { // First get data from App client

    // Determine forum open status
    if (ForumCategories::where('id', 1)->first()->open == 1) {

        // Forum is opening query last user id in database
        $lastRecord = ForumPost::orderBy('updated_at', 'desc')->first();

        // Post not exists
        if (is_null($lastRecord)) {

            // Build Json format
            return Response::json(
                array(
                    'status'    => 1,
                    'data'      => array()
                )
            );
        } else {

            // Post exists

            // Query all items from database
            $top    = ForumPost::where('category_id', $cat_id)
                        ->orderBy('updated_at' , 'desc')
                        ->where('updated_at', '<=', $lastRecord->updated_at)
                        ->where('top', 1)
                        ->where('block', 0)
                        ->select('id', 'user_id', 'title', 'content', 'created_at')
                        ->take('5')
                        ->get()
                        ->toArray();

            // Replace receiver ID to receiver portrait
            foreach ($top as $key => $field) {

                // Count how many comments of this post
                $comments_count                 = ForumComments::where('post_id', $top[$key]['id'])->where('block', false)->count();

                // Retrieve all comments to array
                $comments_array                 = ForumComments::where('post_id', $top[$key]['id'])->where('block', false)->select('id')->get()->toArray();

                // Init replies count
                $replies_count                  = 0;

                // Calculate total replies of this post
                foreach ($comments_array as $comments_array_key => $value) {
                    $replies_count  = $replies_count + ForumReply::where('comments_id', $value['id'])->where('block', false)->count();
                }

                // Retrieve user
                $post_user                      = User::where('id', $top[$key]['user_id'])->first();

                // Get post user portrait real storage path and user porirait key to array
                $top[$key]['portrait']          = route('home') . '/' . 'portrait/' . $post_user->portrait;

                // Get post user sex (M, F or null) and add user sex key to array
                $top[$key]['sex']               = e($post_user->sex);

                // Count how many comments and replies of this post and add comments_count key to array
                $top[$key]['comments_count']    = e($comments_count + $replies_count);

                // Get post user portrait and add portrait key to array
                $top[$key]['nickname']          = app_out_filter($post_user->nickname);

                // Using expression get all picture attachments (Only with pictures stored on this server.)
                preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $top[$key]['content'], $match );

                // Construct picture attachments list and add thumbnails (array format) to array
                $top[$key]['thumbnails']        = join(',', array_pop($match));

                // Get plain text from post content HTML code and replace to content value in array
                $top[$key]['content']           = app_out_filter(getplaintextintrofromhtml($top[$key]['content'], $numchars));

                // Get forum top post title
                $top[$key]['title']             = app_out_filter(Str::limit($top[$key]['title'], 35));
            }

            // Query all items from database
            $items  = ForumPost::where('category_id', $cat_id)
                        ->orderBy('updated_at' , 'desc')
                        ->where('updated_at', '<=', $lastRecord->updated_at)
                        ->where('top', 0)
                        ->where('block', 0)
                        ->select('id', 'user_id', 'title', 'content', 'created_at')
                        ->take($per_page)
                        ->get()
                        ->toArray();

            // Replace receiver ID to receiver portrait
            foreach ($items as $key => $field) {

                // Count how many comments of this post
                $comments_count                 = ForumComments::where('post_id', $items[$key]['id'])->where('block', false)->count();

                // Retrieve all comments to array
                $comments_array                 = ForumComments::where('post_id', $items[$key]['id'])->where('block', false)->select('id')->get()->toArray();

                // Init replies count
                $replies_count                  = 0;

                // Calculate total replies of this post
                foreach ($comments_array as $comments_array_key => $value) {
                    $replies_count  = $replies_count + ForumReply::where('comments_id', $value['id'])->where('block', false)->count();
                }

                // Retrieve user
                $post_user                      = User::where('id', $items[$key]['user_id'])->first();

                // Get post user portrait real storage path and user porirait key to array
                $items[$key]['portrait']        = route('home') . '/' . 'portrait/' . $post_user->portrait;

                // Get post user sex (M, F or null) and add user sex key to array
                $items[$key]['sex']             = e($post_user->sex);

                // Count how many comments and replies of this post and add comments_count key to array
                $items[$key]['comments_count']  = e($comments_count + $replies_count);

                // Get post user portrait and add portrait key to array
                $items[$key]['nickname']        = app_out_filter($post_user->nickname);

                // Using expression get all picture attachments (Only with pictures stored on this server.)
                preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $items[$key]['content'], $match );

                // Construct picture attachments list and add thumbnails (array format) to array
                $items[$key]['thumbnails']      = join(',', array_pop($match));

                // Get plain text from post content HTML code and replace to content value in array
                $items[$key]['content']         = str_ireplace("\n", '',getplaintextintrofromhtml($items[$key]['content'], $numchars));

                // Get forum title
                $items[$key]['title']           = app_out_filter(Str::limit($items[$key]['title'], 35));
            }

            $data = array(
                    'top'   => $top,
                    'items' => $items
                );

            // Build Json format
            return Response::json(
                array(
                    'status'    => 1,
                    'data'      => $data
                )
            );
        }
    } else {

        // Retrieve user
        $user = User::find($user_id);

        // Determine user sex
        if ($user->sex == 'M') {

            // Male user and determine category
            if ($cat_id == 3) {

                // Forum is closed and build Json format
                return Response::json(
                    array(
                        'status'    => 2
                    )
                );
            } else {

                // Forum is opening query last user id in database
                $lastRecord = ForumPost::orderBy('updated_at', 'desc')->first();

                // Post not exists
                if (is_null($lastRecord)) {

                    // Build Json format
                    return Response::json(
                        array(
                            'status'    => 1,
                            'data'      => array()
                        )
                    );
                } else {

                    // Post exists and query all items from database
                    $top    = ForumPost::where('category_id', $cat_id)
                                ->orderBy('updated_at' , 'desc')
                                ->where('updated_at', '<=', $lastRecord->updated_at)
                                ->where('top', 1)
                                ->where('block', 0)
                                ->select('id', 'user_id', 'title', 'content', 'created_at')
                                ->take('5')
                                ->get()
                                ->toArray();

                    // Replace receiver ID to receiver portrait
                    foreach ($top as $key => $field) {

                        // Count how many comments of this post
                        $comments_count                 = ForumComments::where('post_id', $top[$key]['id'])->where('block', false)->count();

                        // Retrieve all comments to array
                        $comments_array                 = ForumComments::where('post_id', $top[$key]['id'])->where('block', false)->select('id')->get()->toArray();

                        // Init replies count
                        $replies_count                  = 0;

                        // Calculate total replies of this post
                        foreach ($comments_array as $comments_array_key => $value) {
                            $replies_count  = $replies_count + ForumReply::where('comments_id', $value['id'])->where('block', false)->count();
                        }

                        // Retrieve user
                        $post_user                      = User::where('id', $top[$key]['user_id'])->first();

                        // Get post user portrait real storage path and user porirait key to array
                        $top[$key]['portrait']          = route('home') . '/' . 'portrait/' . $post_user->portrait;

                        // Get post user sex (M, F or null) and add user sex key to array
                        $top[$key]['sex']               = e($post_user->sex);

                        // Count how many comments of this post and add comments_count key to array
                        $top[$key]['comments_count']    = e($comments_count + $replies_count);

                        // Get post user portrait and add portrait key to array
                        $top[$key]['nickname']          = app_out_filter($post_user->nickname);

                        // Using expression get all picture attachments (Only with pictures stored on this server.)
                        preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $top[$key]['content'], $match );

                        // Construct picture attachments list and add thumbnails (array format) to array
                        $top[$key]['thumbnails']        = join(',', array_pop($match));

                        // Get plain text from post content HTML code and replace to content value in array
                        $top[$key]['content']           = app_out_filter(getplaintextintrofromhtml($top[$key]['content'], $numchars));

                        // Get forum top post title
                        $top[$key]['title']             = app_out_filter(Str::limit($top[$key]['title'], 35));
                    }

                    // Query all items from database
                    $items  = ForumPost::where('category_id', $cat_id)
                                ->orderBy('updated_at' , 'desc')
                                ->where('updated_at', '<=', $lastRecord->updated_at)
                                ->where('top', 0)
                                ->where('block', 0)
                                ->select('id', 'user_id', 'title', 'content', 'created_at')
                                ->take($per_page)
                                ->get()
                                ->toArray();

                    // Replace receiver ID to receiver portrait
                    foreach ($items as $key => $field) {

                        // Count how many comments of this post
                        $comments_count                 = ForumComments::where('post_id', $items[$key]['id'])->where('block', false)->count();

                        // Retrieve all comments to array
                        $comments_array                 = ForumComments::where('post_id', $items[$key]['id'])->where('block', false)->select('id')->get()->toArray();

                        // Init replies count
                        $replies_count                  = 0;

                        // Calculate total replies of this post
                        foreach ($comments_array as $comments_array_key => $value) {
                            $replies_count  = $replies_count + ForumReply::where('comments_id', $value['id'])->where('block', false)->count();
                        }

                        // Retrieve user
                        $post_user                      = User::where('id', $items[$key]['user_id'])->first();

                        // Get post user portrait real storage path and user porirait key to array
                        $items[$key]['portrait']        = route('home') . '/' . 'portrait/' . $post_user->portrait;

                        // Get post user sex (M, F or null) and add user sex key to array
                        $items[$key]['sex']             = e($post_user->sex);

                        // Count how many comments of this post and add comments_count key to array
                        $items[$key]['comments_count']  = e($comments_count + $replies_count);

                        // Get post user portrait and add portrait key to array
                        $items[$key]['nickname']        = app_out_filter($post_user->nickname);

                        // Using expression get all picture attachments (Only with pictures stored on this server.)
                        preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $items[$key]['content'], $match );

                        // Construct picture attachments list and add thumbnails (array format) to array
                        $items[$key]['thumbnails']      = join(',', array_pop($match));

                        // Get plain text from post content HTML code and replace to content value in array
                        $items[$key]['content']         = app_out_filter(getplaintextintrofromhtml($items[$key]['content'], $numchars));

                        // Get forum title
                        $items[$key]['title']           = app_out_filter(Str::limit($items[$key]['title'], 35));
                    }

                    $data = array(
                            'top'   => $top,
                            'items' => $items
                        );

                    // Build Json format
                    return Response::json(
                        array(
                            'status'    => 1,
                            'data'      => $data
                        )
                    );
                }
            }
        } else {

            // Female user and determine category
            if ($cat_id == 2) {

                // Forum is closed and build Json format
                return Response::json(
                    array(
                        'status'    => 2
                    )
                );
            } else {

                // Forum is opening query last user id in database
                $lastRecord = ForumPost::orderBy('updated_at', 'desc')->first();

                // Post not exists
                if (is_null($lastRecord)) {

                    // Build Json format
                    return Response::json(
                        array(
                            'status'    => 1,
                            'data'      => array()
                        )
                    );
                } else {

                    // Post exists

                    // Query all items from database
                    $top    = ForumPost::where('category_id', $cat_id)
                                ->orderBy('updated_at' , 'desc')
                                ->where('updated_at', '<=', $lastRecord->updated_at)
                                ->where('top', 1)
                                ->where('block', 0)
                                ->select('id', 'user_id', 'title', 'content', 'created_at')
                                ->take('5')
                                ->get()
                                ->toArray();

                    // Replace receiver ID to receiver portrait
                    foreach ($top as $key => $field) {

                        // Count how many comments of this post
                        $comments_count                 = ForumComments::where('post_id', $top[$key]['id'])->where('block', false)->count();

                        // Retrieve all comments to array
                        $comments_array                 = ForumComments::where('post_id', $top[$key]['id'])->where('block', false)->select('id')->get()->toArray();

                        // Init replies count
                        $replies_count                  = 0;

                        // Calculate total replies of this post
                        foreach ($comments_array as $comments_array_key => $value) {
                            $replies_count  = $replies_count + ForumReply::where('comments_id', $value['id'])->where('block', false)->count();
                        }

                        // Retrieve user
                        $post_user                      = User::where('id', $top[$key]['user_id'])->first();

                        // Get post user portrait real storage path and user porirait key to array
                        $top[$key]['portrait']          = route('home') . '/' . 'portrait/' . $post_user->portrait;

                        // Get post user sex (M, F or null) and add user sex key to array
                        $top[$key]['sex']               = e($post_user->sex);

                        // Count how many comments of this post and add comments_count key to array
                        $top[$key]['comments_count']    = e($comments_count + $replies_count);

                        // Get post user portrait and add portrait key to array
                        $top[$key]['nickname']          = app_out_filter($post_user->nickname);

                        // Using expression get all picture attachments (Only with pictures stored on this server.)
                        preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $top[$key]['content'], $match );

                        // Construct picture attachments list and add thumbnails (array format) to array
                        $top[$key]['thumbnails']        = join(',', array_pop($match));

                        // Get plain text from post content HTML code and replace to content value in array
                        $top[$key]['content']           = app_out_filter(getplaintextintrofromhtml($top[$key]['content'], $numchars));

                        // Get forum top post title
                        $top[$key]['title']             = app_out_filter(Str::limit($top[$key]['title'], 35));
                    }

                    // Query all items from database
                    $items  = ForumPost::where('category_id', $cat_id)
                                ->orderBy('updated_at' , 'desc')
                                ->where('updated_at', '<=', $lastRecord->updated_at)
                                ->where('top', 0)
                                ->where('block', 0)
                                ->select('id', 'user_id', 'title', 'content', 'created_at')
                                ->take($per_page)
                                ->get()
                                ->toArray();

                    // Replace receiver ID to receiver portrait
                    foreach ($items as $key => $field) {

                        // Count how many comments of this post
                        $comments_count                 = ForumComments::where('post_id', $items[$key]['id'])->where('block', false)->count();

                        // Retrieve all comments to array
                        $comments_array                 = ForumComments::where('post_id', $items[$key]['id'])->where('block', false)->select('id')->get()->toArray();

                        // Init replies count
                        $replies_count                  = 0;

                        // Calculate total replies of this post
                        foreach ($comments_array as $comments_array_key => $value) {
                            $replies_count  = $replies_count + ForumReply::where('comments_id', $value['id'])->where('block', false)->count();
                        }

                        // Retrieve user
                        $post_user                      = User::where('id', $items[$key]['user_id'])->first();

                        // Get post user portrait real storage path and user porirait key to array
                        $items[$key]['portrait']        = route('home') . '/' . 'portrait/' . $post_user->portrait;

                        // Get post user sex (M, F or null) and add user sex key to array
                        $items[$key]['sex']             = e($post_user->sex);

                        // Count how many comments of this post and add comments_count key to array
                        $items[$key]['comments_count']  = e($comments_count + $replies_count);

                        // Get post user portrait and add portrait key to array
                        $items[$key]['nickname']        = app_out_filter($post_user->nickname);

                        // Using expression get all picture attachments (Only with pictures stored on this server.)
                        preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $items[$key]['content'], $match );

                        // Construct picture attachments list and add thumbnails (array format) to array
                        $items[$key]['thumbnails']      = join(',', array_pop($match));

                        // Get plain text from post content HTML code and replace to content value in array
                        $items[$key]['content']         = app_out_filter(getplaintextintrofromhtml($items[$key]['content'], $numchars));

                        // Get forum title
                        $items[$key]['title']           = app_out_filter(Str::limit($items[$key]['title'], 35));
                    }

                    $data = array(
                            'top'   => $top,
                            'items' => $items
                        );

                    // Build Json format
                    return Response::json(
                        array(
                            'status'    => 1,
                            'data'      => $data
                        )
                    );
                }
            }
        }
    }
}
