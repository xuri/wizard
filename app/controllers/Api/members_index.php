<?php

// Get user id from App client
$user_id            = Input::get('userid');

// Post last user id from App client
$last_id            = Input::get('lastid');

// Post count per query from App client
$per_page           = Input::get('perpage');

// Post sex filter from App client
$sex_filter         = Input::get('sex');

// Post university filter from App client
$university_filter  = Input::get('university');

// Grade filter
$grade              = Input::get('grade');

if ($user_id) {
    // Retrieve user
    $user               = User::find($user_id);

    // Updated user active date
    $user->updated_at   = Carbon::now();

    // Update reveiver_updated_at in like table
    DB::table('like')->where('receiver_id', $user->id)->update(array('receiver_updated_at' => Carbon::now()));
    $user->save();
}

if ($last_id) {
    // User last signin at time
    $last_updated_at    = User::find($last_id)->updated_at;

    // App client have post last user id, retrieve and skip profile not completed user
    $query              = User::whereNotNull('portrait')
                                ->whereNotNull('nickname')
                                ->whereNotNull('bio')
                                ->whereNotNull('school');
    // Ruled out not set tags and select has correct format constellation user
    // $query->whereHas('hasOneProfile', function($hasTagStr) {
    //  $hasTagStr->where('tag_str', '!=', ',')->whereNotNull('constellation')->where('constellation', '!=', 0);
    // });

    // Sex filter
    if ($sex_filter) {
        isset($sex_filter) AND $query->where('sex', $sex_filter);
    }

    // University filter
    if ($university_filter) {
        if ($university_filter == '其他') {
            $universities_list = University::where('status', 2)->select('university')->get()->toArray();
            isset($university_filter) AND $query->whereNotIn('school', $universities_list);
        } else {
            isset($university_filter) AND $query->where('school', $university_filter);
        }
    }

    // Grade filter
    if ($grade) {
        $_id = Profile::where('grade', '=', Input::get('grade'))->select('id')->get()->toArray();
        isset($grade) AND $query->whereIn('id', $_id);
        // isset($grade) AND $query->whereHas('hasOneProfile', function($profileQuery){
        //  $profileQuery->where('grade', '=', Input::get('grade'));
        // });
    }

    $users = $query
        ->orderBy('updated_at', 'desc')
        ->where('block', 0)
        ->select('id', 'nickname', 'school', 'sex', 'portrait', 'is_admin', 'is_verify')
        ->where('updated_at', '<', $last_updated_at)
        ->take($per_page)
        ->get()
        ->toArray();

    // Replace receiver ID to receiver portrait
    foreach ($users as $key => $field) {

        if(Cache::has('api_user_' . $users[$key]['id'])) {
            $profile                    = Cache::get('api_user_' . $users[$key]['id']);

            // User renew status
            $users[$key]['crenew']      = Cache::get('api_user_' . $users[$key]['id'] . '_crenew');

            // Convert to real storage path
            $users[$key]['portrait']    = route('home') . '/' . 'portrait/' . $users[$key]['portrait'];

            // Retrieve sex with UTF8 encode
            $users[$key]['sex']         = Cache::get('api_user_' . $users[$key]['id'] . '_sex');

            // Retrieve nickname with UTF8 encode
            $users[$key]['nickname']    = Cache::get('api_user_' . $users[$key]['id'] . '_nickname');

            // Retrieve school with UTF8 encode
            $users[$key]['school']      = Cache::get('api_user_' . $users[$key]['id'] . '_school');

        } else {
            // Retrieve user profile
            $profile    = Profile::where('user_id', $users[$key]['id'])->first();

            Cache::put('api_user_' . $users[$key]['id'], $profile, 60);

            // Determine user renew status
            if ($profile->crenew >= 30) {
                $users[$key]['crenew'] = 1;
                Cache::put('api_user_' . $users[$key]['id'] . '_crenew', 1, 60);
            } else {
                $users[$key]['crenew'] = 0;
                Cache::put('api_user_' . $users[$key]['id'] . '_crenew', 0, 60);
            }

            // Convert to real storage path
            $users[$key]['portrait']    = route('home') . '/' . 'portrait/' . $users[$key]['portrait'];

            // Retrieve sex with UTF8 encode
            $users[$key]['sex']         = e($users[$key]['sex']);

            Cache::put('api_user_' . $users[$key]['id'] . '_sex', e($users[$key]['sex']), 60);

            // Retrieve nickname with UTF8 encode
            $users[$key]['nickname']    = app_out_filter($users[$key]['nickname']);

            Cache::put('api_user_' . $users[$key]['id'] . '_nickname', app_out_filter($users[$key]['nickname']), 60);

            // Retrieve school with UTF8 encode
            $users[$key]['school']      = e($users[$key]['school']);

            Cache::put('api_user_' . $users[$key]['id'] . '_school', e($users[$key]['school']), 60);

            Cache::put('api_user_' . $users[$key]['id'] . '_tag_str', e(implode(',', array_slice(explode(',', trim($profile->tag_str,',')), 0, 2))), 60);
        }

    }

    // If get query success
    if ($users) {

        // Build Json format
        return Response::json(
            array(
                'status'    => 1,
                'data'      => $users
            )
        );
    } else {
        // Get query fail
        return Response::json(
            array(
                'status'    => 0
            )
        );
    }
} else {

    //  First get data from App client, retrieve and skip profile not completed user
    $query      = User::whereNotNull('portrait')
                        ->whereNotNull('nickname')
                        ->whereNotNull('bio')
                        ->whereNotNull('school');

    // Ruled out not set tags and select has correct format constellation user
    // $query->whereHas('hasOneProfile', function($hasTagStr) {
    //  $hasTagStr->where('tag_str', '!=', ',')->whereNotNull('constellation')->where('constellation', '!=', 0);
    // });

    // Sex filter
    if ($sex_filter) {
        isset($sex_filter) AND $query->where('sex', $sex_filter);
    }

    // University filter
    if ($university_filter) {
        if ($university_filter == '其他') {
            $universities_list = University::where('status', 2)->select('university')->get()->toArray();
            isset($university_filter) AND $query->whereNotIn('school', $universities_list);
        } else {
            isset($university_filter) AND $query->where('school', $university_filter);
        }
    }

    // Grade filter
    if ($grade) {
        $_id = Profile::where('grade', '=', Input::get('grade'))->select('id')->get()->toArray();
        isset($grade) AND $query->whereIn('id', $_id);
        // isset($grade) AND $query->whereHas('hasOneProfile', function($profileQuery){
        //  $profileQuery->where('grade', '=', Input::get('grade'));
        // });
    }

    // Query last user id in database
    $lastRecord = User::orderBy('updated_at', 'desc')->first()->updated_at;

    $users      = $query
                    ->orderBy('updated_at', 'desc')
                    ->select('id', 'nickname', 'school', 'sex', 'portrait', 'is_admin', 'is_verify')
                    ->where('block', 0)
                    ->where('updated_at', '<=', $lastRecord)
                    ->take($per_page)
                    ->get()
                    ->toArray();

    // Replace receiver ID to receiver portrait
    foreach ($users as $key => $field) {

        if (Cache::has('api_user_' . $users[$key]['id'])) {
            $profile                    = Cache::get('api_user_' . $users[$key]['id']);

            // User renew status
            $users[$key]['crenew']      = Cache::get('api_user_' . $users[$key]['id'] . '_crenew');

            // Convert to real storage path
            $users[$key]['portrait']    = route('home') . '/' . 'portrait/' . $users[$key]['portrait'];

            // Retrieve sex with UTF8 encode
            $users[$key]['sex']         = Cache::get('api_user_' . $users[$key]['id'] . '_sex');

            // Retrieve nickname with UTF8 encode
            $users[$key]['nickname']    = Cache::get('api_user_' . $users[$key]['id'] . '_nickname');

            // Retrieve school with UTF8 encode
            $users[$key]['school']      = Cache::get('api_user_' . $users[$key]['id'] . '_school');

        } else {
            // Retrieve user profile
            $profile    = Profile::where('user_id', $users[$key]['id'])->first();

            Cache::put('api_user_' . $users[$key]['id'], $profile, 60);

            // Determine user renew status
            if ($profile->crenew >= 30) {
                $users[$key]['crenew'] = 1;
                Cache::put('api_user_' . $users[$key]['id'] . '_crenew', 1, 60);
            } else {
                $users[$key]['crenew'] = 0;
                Cache::put('api_user_' . $users[$key]['id'] . '_crenew', 0, 60);
            }

            // Convert to real storage path
            $users[$key]['portrait']    = route('home') . '/' . 'portrait/' . $users[$key]['portrait'];

            // Retrieve sex with UTF8 encode
            $users[$key]['sex']         = e($users[$key]['sex']);

            Cache::put('api_user_' . $users[$key]['id'] . '_sex', e($users[$key]['sex']), 60);

            // Retrieve nickname with UTF8 encode
            $users[$key]['nickname']    = app_out_filter($users[$key]['nickname']);

            Cache::put('api_user_' . $users[$key]['id'] . '_nickname', app_out_filter($users[$key]['nickname']), 60);

            // Retrieve school with UTF8 encode
            $users[$key]['school']      = e($users[$key]['school']);

            Cache::put('api_user_' . $users[$key]['id'] . '_school', e($users[$key]['school']), 60);

            Cache::put('api_user_' . $users[$key]['id'] . '_tag_str', e(implode(',', array_slice(explode(',', trim($profile->tag_str,',')), 0, 2))), 60);
        }
    }

    if ($users) {
        return Response::json(
            array(
                'status'    => 1,
                'data'      => $users
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
