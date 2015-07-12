<?php

// Post last user id from App client
$last_id         = Input::get('lastid');

// Post count per query from App client
$per_page        = Input::get('perpage');

// Get user ID
$user_id         = Input::get('userid');

// User location province filter
$province_filter = Input::get('province_id');

// Retrieve user
$user            = User::find($user_id);

// Determin user profile if complete
if (isset($user->nickname) && isset($user->school) && isset($user->bio) && isset($user->sex)) {
    if ($last_id) {
        // App client have post last like job id, retrieve like jobs
        $query         = LikeJobs::select('id', 'title', 'user_id')
                            ->orderBy('id', 'desc')
                            ->where('id', '<', $last_id);

        // User location province filter
        if ($province_filter) {
            $_id       = User::where('province_id', $province_filter)->select('id')->get()->toArray();
            isset($province_filter) AND $query->whereIn('user_id', $_id);
        }

        $query         = $query->take($per_page)
                            ->get()
                            ->toArray();

        // Convert like job title in array
        foreach ($query as $key => $value) {
            // User ID
            $query_user_id = $query[$key]['user_id'];
            // Retrieve user
            $query_user    = User::find($query_user_id);
            switch ($query_user->sex) {
                case 'M':
                    // Male user
                    $query[$key]['title'] = '聘妻: ' . $query[$key]['title'];
                    break;

                default:
                    // Female user
                    $query[$key]['title'] = '聘夫: ' . $query[$key]['title'];
                    break;
            }
        }

        return Response::json(
            array(
                'status' => 1, // Success
                'data'   => $query
            )
        );

    } else {
        // First get data from App client, retrieve like jobs
        $query         = LikeJobs::select('id', 'title', 'user_id')
                            ->orderBy('id', 'desc');

        // User location province filter
        if ($province_filter) {
            $_id       = User::where('province_id', $province_filter)->select('id')->get()->toArray();
            isset($province_filter) AND $query->whereIn('user_id', $_id);
        }

        $query         = $query->take($per_page)
                            ->get()
                            ->toArray();

        // Convert like job title in array
        foreach ($query as $key => $value) {
            // User ID
            $query_user_id = $query[$key]['user_id'];
            // Retrieve user
            $query_user    = User::find($query_user_id);
            switch ($query_user->sex) {
                case 'M':
                    // Male user
                    $query[$key]['title'] = '聘妻: ' . $query[$key]['title'];
                    break;

                default:
                    // Female user
                    $query[$key]['title'] = '聘夫: ' . $query[$key]['title'];
                    break;
            }
        }

        return Response::json(
            array(
                'status' => 1, // Success
                'data'   => $query
            )
        );
    }
} else {
    if ($last_id) {
        // App client have post last like job id, retrieve like jobs
        $query         = LikeJobs::select('id', 'title', 'user_id')
                            ->orderBy('id', 'desc')
                            ->where('id', '<', $last_id);

        // User location province filter
        if ($province_filter) {
            $_id       = User::where('province_id', $province_filter)->select('id')->get()->toArray();
            isset($province_filter) AND $query->whereIn('user_id', $_id);
        }

        $query         = $query->take($per_page)
                            ->get()
                            ->toArray();

        // Convert like job title in array
        foreach ($query as $key => $value) {
            // User ID
            $query_user_id = $query[$key]['user_id'];
            // Retrieve user
            $query_user    = User::find($query_user_id);
            switch ($query_user->sex) {
                case 'M':
                    // Male user
                    $query[$key]['title'] = '聘妻: ' . $query[$key]['title'];
                    break;

                default:
                    // Female user
                    $query[$key]['title'] = '聘夫: ' . $query[$key]['title'];
                    break;
            }
        }

        return Response::json(
            array(
                'status' => 2, // Success
                'data'   => $query
            )
        );

    } else {
        // First get data from App client, retrieve like jobs
        $query         = LikeJobs::select('id', 'title', 'user_id')
                            ->orderBy('id', 'desc');

        // User location province filter
        if ($province_filter) {
            $_id       = User::where('province_id', $province_filter)->select('id')->get()->toArray();
            isset($province_filter) AND $query->whereIn('user_id', $_id);
        }

        $query         = $query->take($per_page)
                            ->get()
                            ->toArray();

        // Convert like job title in array
        foreach ($query as $key => $value) {
            // User ID
            $query_user_id = $query[$key]['user_id'];
            // Retrieve user
            $query_user    = User::find($query_user_id);
            switch ($query_user->sex) {
                case 'M':
                    // Male user
                    $query[$key]['title'] = '聘妻: ' . $query[$key]['title'];
                    break;

                default:
                    // Female user
                    $query[$key]['title'] = '聘夫: ' . $query[$key]['title'];
                    break;
            }
        }

        return Response::json(
            array(
                'status' => 2, // Success
                'data'   => $query
            )
        );
    }
}
