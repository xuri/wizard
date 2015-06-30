<?php

// Get user id from App client
$user_id            = Input::get('userid');

// Post last user id from App client
$last_id            = Input::get('lastid');

// Post count per query from App client
$per_page           = Input::get('perpage');

// User born year filter
$born_year_filter   = Input::get('born_year');

// Post university filter from App client
$university_filter  = Input::get('university');

// Province filter
$province_id        = Input::get('province_id');

if ($user_id) {
    // Retrieve user
    $user               = User::find($user_id);

    // Updated user active date
    $user->updated_at   = Carbon::now();

    // Update reveiver_updated_at in like table
    DB::table('like')->where('receiver_id', $user->id)->update(array('receiver_updated_at' => Carbon::now()));
    $user->save();

    switch ($user->sex) {
        case 'M':
            // Male user show female user information
            $sex_filter = 'F';
            break;

        case 'F':
            // Female user show male user information
            $sex_filter = 'M';
            break;

        default:
            unset($sex_filter);
            break;
    }

    $profile = Profile::where('user_id', $user->id)->first();
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
    // $hasTagStr->where('tag_str', '!=', ',')->whereNotNull('constellation')->where('constellation', '!=', 0);
    // });

    // Sex filter
    if ($sex_filter) {
        isset($sex_filter) AND $query->where('sex', $sex_filter);
    }

    // User born year filter
    if ($born_year_filter) {
        isset($born_year_filter) AND $query->where('born_year', $born_year_filter);
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

    // Province filter
    if ($province_id) {
        isset($province_id) AND $query->where('province_id', $province_id);
    }

    $users = $query
        ->orderBy('updated_at', 'desc')
        ->where('block', 0)
        ->select('id', 'nickname', 'school', 'sex', 'province_id', 'portrait', 'is_admin', 'is_verify', 'points', 'born_year')
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

            // Retrieve tag_str with UTF8 encode
            $users[$key]['tag_str']     = e(Cache::get('api_user_' . $users[$key]['id'] . '_tag_str'));

            // Retrieve location province with UTF8 encode
            $users[$key]['province']    = Cache::get('api_user_' . $users[$key]['id'] . '_province');

            // Retrieve user salary with UTF8 encode
            $users[$key]['salary']      = Cache::get('api_user_' . $users[$key]['id'] . '_salary');

            // Retrieve user born year with UTF8 encode
            $users[$key]['born_year']   = Cache::get('api_user_' . $users[$key]['id'] . '_born_year');

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

            // Determine user location province
            if ($users[$key]['province_id'] != "") {
                $province = Province::find($users[$key]['province_id'])->province;
            } else {
                $province = '未设置所在地';
            }

            // Determine user salary selection
            switch ($profile->salary) {

                case '0':
                    $salary = '在校学生';
                    break;

                case '1':
                    $salary = '0-2000';
                    break;

                case '2':
                    $salary = '2000-5000';
                    break;

                case '3':
                    $salary = '5000-9000';
                    break;

                case '4':
                    $salary = '9000以上';
                    break;

                default:
                    $salary = '在校学生';
                    break;
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

            // Retrieve tag_str with UTF8 encode
            $users[$key]['tag_str']     = e(implode(',', array_slice(explode(',', trim($profile->tag_str,',')), 0, 2)));

            Cache::put('api_user_' . $users[$key]['id'] . '_tag_str', e(implode(',', array_slice(explode(',', trim($profile->tag_str,',')), 0, 2))), 60);

            // Retrieve location province with UTF8 encode
            $users[$key]['province']    = e($province);

            Cache::put('api_user_' . $users[$key]['id'] . '_province', e($province), 60);

            // Retrieve user salary with UTF8 encode
            $users[$key]['salary']      = e($salary);

            Cache::put('api_user_' . $users[$key]['id'] . '_salary', e($salary), 60);

            // Retrieve user born_year with UTF8 encode
            $users[$key]['born_year']   = e($users[$key]['born_year']);

            Cache::put('api_user_' . $users[$key]['id'] . '_born_year', e($users[$key]['born_year']), 60);

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

    // First get data from App client, retrieve and skip profile not completed user
    $query      = User::whereNotNull('portrait')
                        ->whereNotNull('nickname')
                        ->whereNotNull('bio')
                        ->whereNotNull('school');

    // Ruled out not set tags and select has correct format constellation user
    // $query->whereHas('hasOneProfile', function($hasTagStr) {
    // $hasTagStr->where('tag_str', '!=', ',')->whereNotNull('constellation')->where('constellation', '!=', 0);
    // });

    // Sex filter
    if ($sex_filter) {
        isset($sex_filter) AND $query->where('sex', $sex_filter);
    }

    // User born year filter
    if ($born_year_filter) {
        isset($born_year_filter) AND $query->where('born_year', $born_year_filter);
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

    // Province filter
    if ($province_id) {
        isset($province_id) AND $query->where('province_id', $province_id);
    }

    // Query last user id in database
    $lastRecord = User::orderBy('updated_at', 'desc')->first()->updated_at;

    $users      = $query
                    ->orderBy('updated_at', 'desc')
                    ->select('id', 'nickname', 'school', 'sex', 'province_id', 'portrait', 'is_admin', 'is_verify', 'points', 'born_year')
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

            // Retrieve tag_str with UTF8 encode
            $users[$key]['tag_str']     = e(Cache::get('api_user_' . $users[$key]['id'] . '_tag_str'));

            // Retrieve location province with UTF8 encode
            $users[$key]['province']    = Cache::get('api_user_' . $users[$key]['id'] . '_province');

            // Retrieve user salary with UTF8 encode
            $users[$key]['salary']      = Cache::get('api_user_' . $users[$key]['id'] . '_salary');

            // Retrieve user born year with UTF8 encode
            $users[$key]['born_year']   = Cache::get('api_user_' . $users[$key]['id'] . '_born_year');

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

            // Determine user location province
            if ($users[$key]['province_id'] != "") {
                $province = Province::find($users[$key]['province_id'])->province;
            } else {
                $province = '未设置所在地';
            }

            // Determine user salary selection
            switch ($profile->salary) {

                case '0':
                    $salary = '在校学生';
                    break;

                case '1':
                    $salary = '0-2000';
                    break;

                case '2':
                    $salary = '2000-5000';
                    break;

                case '3':
                    $salary = '5000-9000';
                    break;

                case '4':
                    $salary = '9000以上';
                    break;

                default:
                    $salary = '在校学生';
                    break;
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

            // Retrieve tag_str with UTF8 encode
            $users[$key]['tag_str']     = e(implode(',', array_slice(explode(',', trim($profile->tag_str,',')), 0, 2)));

            Cache::put('api_user_' . $users[$key]['id'] . '_tag_str', e(implode(',', array_slice(explode(',', trim($profile->tag_str,',')), 0, 2))), 60);

            // Retrieve location province with UTF8 encode
            $users[$key]['province']    = e($province);

            Cache::put('api_user_' . $users[$key]['id'] . '_province', e($province), 60);

            // Retrieve user salary with UTF8 encode
            $users[$key]['salary']      = e($salary);

            Cache::put('api_user_' . $users[$key]['id'] . '_salary', e($salary), 60);

            // Retrieve user born_year with UTF8 encode
            $users[$key]['born_year']   = e($users[$key]['born_year']);

            Cache::put('api_user_' . $users[$key]['id'] . '_born_year', e($users[$key]['born_year']), 60);

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
