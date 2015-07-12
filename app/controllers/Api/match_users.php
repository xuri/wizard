<?php

// Get user ID
$user_id = Input::get('id');
// Retrieve user
$user    = User::find($user_id);

if ($user) {
    // Retrieve user sex
    $sex     = $user->sex;
    // Retrieve user profile
    $profile =  Profile::where('user_id', $user_id)->first();
    // Check user profile complete
    if (is_null(Profile::where('user_id', $user->id)->first()->tag_str)) {
        // Calculate how long user last match time from now (more than one day)
        if ($profile->match_at <= Carbon::today()) {
            // Rest match count
            $profile->match = 0;
            $profile->save();
            $match_count    = 0;

            switch ($sex) {
                case 'M':

                    if (is_null($profile->match_users)) {
                        // Male user, match female user
                        $match_users = User::where('sex', 'F')
                                            ->whereNotNull('school')
                                            ->whereNotNull('born_year')
                                            ->whereNotNull('portrait')
                                            ->whereNotNull('bio')
                                            ->where('activated_at', '>=', Carbon::now()->subMonth())
                                            ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                            ->take(6)
                                            ->get()
                                            ->toArray();
                    } else {
                        $match_users = User::where('sex', 'F')
                                            ->whereNotNull('school')
                                            ->whereNotNull('born_year')
                                            ->whereNotNull('portrait')
                                            ->whereNotNull('bio')
                                            ->whereNotIn('id', explode(',', $profile->match_users))
                                            ->where('activated_at', '>=', Carbon::now()->subMonth())
                                            ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                            ->take(6)
                                            ->get()
                                            ->toArray();
                    }

                    // Convert
                    // foreach ($match_users as $key => $value) {
                    //     $match_users_id[]  = $value['id'];
                    // }

                    // if (is_null($profile->match_users)) {
                    //     $profile->match_users = implode(',', $match_users_id);
                    // } else {
                    //     $profile->match_users = $profile->match_users . ',' . implode(',', $match_users_id);
                    // }

                    // $profile->save();

                    foreach ($match_users as $users => $value) {
                        $match_users[$users]['portrait'] = route('home') . '/' . 'portrait/' . $match_users[$users]['portrait'];
                        $match_users[$users]['grade']    = $profile->grade;
                    }

                    return Response::json(
                        array(
                            'status' => 2,
                            'users'  => $match_users,
                            'match'  => $match_count
                        )
                    );
                    break;

                default:

                    if (is_null($profile->match_users)) {
                        // Female user, match male user
                        $match_users = User::where('sex', 'M')
                                            ->whereNotNull('school')
                                            ->whereNotNull('born_year')
                                            ->whereNotNull('portrait')
                                            ->whereNotNull('bio')
                                            ->where('activated_at', '>=', Carbon::now()->subMonth())
                                            ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                            ->take(6)
                                            ->get()
                                            ->toArray();
                    } else {
                        $match_users = User::where('sex', 'M')
                                            ->whereNotNull('school')
                                            ->whereNotNull('born_year')
                                            ->whereNotNull('portrait')
                                            ->whereNotNull('bio')
                                            ->whereNotIn('id', explode(',', $profile->match_users))
                                            ->where('activated_at', '>=', Carbon::now()->subMonth())
                                            ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                            ->take(6)
                                            ->get()
                                            ->toArray();
                    }

                    foreach ($match_users as $users => $value) {
                        $match_users[$users]['portrait'] = route('home') . '/' . 'portrait/' . $match_users[$users]['portrait'];
                        $match_users[$users]['grade']    = $profile->grade;
                    }

                    return Response::json(
                        array(
                            'status' => 2,
                            'users'  => $match_users,
                            'match'  => $match_count
                        )
                    );

                    break;
            }
        } else {
            // User have match other users today
            if ($profile->match >= 3) {
                return Response::json(
                    array(
                        'status'        => 3 // User match other user over 3 times today
                    )
                );
            } else {
                // Retrieve user match count
                if (is_null($profile->match)) {
                    $match_count = 0;
                } else {
                    $match_count = $profile->match;
                }

                switch ($sex) {
                    case 'M':

                        if (is_null($profile->match_users)) {
                            // Male user, match female user
                            $match_users = User::where('sex', 'F')
                                                ->whereNotNull('school')
                                                ->whereNotNull('born_year')
                                                ->whereNotNull('portrait')
                                                ->whereNotNull('bio')
                                                ->where('activated_at', '>=', Carbon::now()->subMonth())
                                                ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                                ->take(6)
                                                ->get()
                                                ->toArray();
                        } else {
                            $match_users = User::where('sex', 'F')
                                                ->whereNotNull('school')
                                                ->whereNotNull('born_year')
                                                ->whereNotNull('portrait')
                                                ->whereNotNull('bio')
                                                ->whereNotIn('id', explode(',', $profile->match_users))
                                                ->where('activated_at', '>=', Carbon::now()->subMonth())
                                                ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                                ->take(6)
                                                ->get()
                                                ->toArray();
                        }

                        foreach ($match_users as $users => $value) {
                            $match_users[$users]['portrait'] = route('home') . '/' . 'portrait/' . $match_users[$users]['portrait'];
                            $match_users[$users]['grade']    = $profile->grade;
                        }

                        return Response::json(
                            array(
                                'status' => 2,
                                'users'  => $match_users,
                                'match'  => $match_count
                            )
                        );
                        break;

                    default:

                        if (is_null($profile->match_users)) {
                             // Female user, match male user
                            $match_users = User::where('sex', 'M')
                                                ->whereNotNull('school')
                                                ->whereNotNull('born_year')
                                                ->whereNotNull('portrait')
                                                ->whereNotNull('bio')
                                                ->where('activated_at', '>=', Carbon::now()->subMonth())
                                                ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                                ->take(6)
                                                ->get()
                                                ->toArray();
                        } else {
                            $match_users = User::where('sex', 'M')
                                                ->whereNotNull('school')
                                                ->whereNotNull('born_year')
                                                ->whereNotNull('portrait')
                                                ->whereNotNull('bio')
                                                ->whereNotIn('id', explode(',', $profile->match_users))
                                                ->where('activated_at', '>=', Carbon::now()->subMonth())
                                                ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                                ->take(6)
                                                ->get()
                                                ->toArray();
                        }

                        foreach ($match_users as $users => $value) {
                            $match_users[$users]['portrait'] = route('home') . '/' . 'portrait/' . $match_users[$users]['portrait'];
                            $match_users[$users]['grade']    = $profile->grade;
                        }

                        return Response::json(
                            array(
                                'status' => 2,
                                'users'  => $match_users,
                                'match'  => $match_count
                            )
                        );

                        break;
                }
            }
        }
    } else {
        // Calculate how long user last match time from now (more than one day)
        if ($profile->match_at <= Carbon::today()) {
            // Rest match count
            $profile->match = 0;
            $profile->save();
            $match_count    = 0;

            switch ($sex) {
                case 'M':

                    if (is_null($profile->match_users)) {
                        // Male user, match female user
                        $match_users = User::where('sex', 'F')
                                            ->whereNotNull('school')
                                            ->whereNotNull('born_year')
                                            ->whereNotNull('portrait')
                                            ->whereNotNull('bio')
                                            ->where('activated_at', '>=', Carbon::now()->subMonth())
                                            ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                            ->take(6)
                                            ->get()
                                            ->toArray();
                    } else {
                        $match_users = User::where('sex', 'F')
                                            ->whereNotNull('school')
                                            ->whereNotNull('born_year')
                                            ->whereNotNull('portrait')
                                            ->whereNotNull('bio')
                                            ->whereNotIn('id', explode(',', $profile->match_users))
                                            ->where('activated_at', '>=', Carbon::now()->subMonth())
                                            ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                            ->take(6)
                                            ->get()
                                            ->toArray();
                    }

                    // Convert
                    // foreach ($match_users as $key => $value) {
                    //     $match_users_id[]  = $value['id'];
                    // }

                    // if (is_null($profile->match_users)) {
                    //     $profile->match_users = implode(',', $match_users_id);
                    // } else {
                    //     $profile->match_users = $profile->match_users . ',' . implode(',', $match_users_id);
                    // }

                    // $profile->save();

                    foreach ($match_users as $users => $value) {
                        $match_users[$users]['portrait'] = route('home') . '/' . 'portrait/' . $match_users[$users]['portrait'];
                        $match_users[$users]['grade']    = $profile->grade;
                    }

                    return Response::json(
                        array(
                            'status' => 1,
                            'users'  => $match_users,
                            'match'  => $match_count
                        )
                    );
                    break;

                default:

                    if (is_null($profile->match_users)) {
                        // Female user, match male user
                        $match_users = User::where('sex', 'M')
                                            ->whereNotNull('school')
                                            ->whereNotNull('born_year')
                                            ->whereNotNull('portrait')
                                            ->whereNotNull('bio')
                                            ->where('activated_at', '>=', Carbon::now()->subMonth())
                                            ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                            ->take(6)
                                            ->get()
                                            ->toArray();
                    } else {
                        $match_users = User::where('sex', 'M')
                                            ->whereNotNull('school')
                                            ->whereNotNull('born_year')
                                            ->whereNotNull('portrait')
                                            ->whereNotNull('bio')
                                            ->whereNotIn('id', explode(',', $profile->match_users))
                                            ->where('activated_at', '>=', Carbon::now()->subMonth())
                                            ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                            ->take(6)
                                            ->get()
                                            ->toArray();
                    }

                    foreach ($match_users as $users => $value) {
                        $match_users[$users]['portrait'] = route('home') . '/' . 'portrait/' . $match_users[$users]['portrait'];
                        $match_users[$users]['grade']    = $profile->grade;
                    }

                    return Response::json(
                        array(
                            'status' => 1,
                            'users'  => $match_users,
                            'match'  => $match_count
                        )
                    );

                    break;
            }
        } else {
            // User have match other users today
            if ($profile->match >= 3) {
                return Response::json(
                    array(
                        'status'        => 3 // User match other user over 3 times today
                    )
                );
            } else {
                // Retrieve user match count
                if (is_null($profile->match)) {
                    $match_count = 0;
                } else {
                    $match_count = $profile->match;
                }

                switch ($sex) {
                    case 'M':

                        if (is_null($profile->match_users)) {
                            // Male user, match female user
                            $match_users = User::where('sex', 'F')
                                                ->whereNotNull('school')
                                                ->whereNotNull('born_year')
                                                ->whereNotNull('portrait')
                                                ->whereNotNull('bio')
                                                ->where('activated_at', '>=', Carbon::now()->subMonth())
                                                ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                                ->take(6)
                                                ->get()
                                                ->toArray();
                        } else {
                            $match_users = User::where('sex', 'F')
                                                ->whereNotNull('school')
                                                ->whereNotNull('born_year')
                                                ->whereNotNull('portrait')
                                                ->whereNotNull('bio')
                                                ->whereNotIn('id', explode(',', $profile->match_users))
                                                ->where('activated_at', '>=', Carbon::now()->subMonth())
                                                ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                                ->take(6)
                                                ->get()
                                                ->toArray();
                        }

                        foreach ($match_users as $users => $value) {
                            $match_users[$users]['portrait'] = route('home') . '/' . 'portrait/' . $match_users[$users]['portrait'];
                            $match_users[$users]['grade']    = $profile->grade;
                        }

                        return Response::json(
                            array(
                                'status' => 1,
                                'users'  => $match_users,
                                'match'  => $match_count
                            )
                        );
                        break;

                    default:

                        if (is_null($profile->match_users)) {
                             // Female user, match male user
                            $match_users = User::where('sex', 'M')
                                                ->whereNotNull('school')
                                                ->whereNotNull('born_year')
                                                ->whereNotNull('portrait')
                                                ->whereNotNull('bio')
                                                ->where('activated_at', '>=', Carbon::now()->subMonth())
                                                ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                                ->take(6)
                                                ->get()
                                                ->toArray();
                        } else {
                            $match_users = User::where('sex', 'M')
                                                ->whereNotNull('school')
                                                ->whereNotNull('born_year')
                                                ->whereNotNull('portrait')
                                                ->whereNotNull('bio')
                                                ->whereNotIn('id', explode(',', $profile->match_users))
                                                ->where('activated_at', '>=', Carbon::now()->subMonth())
                                                ->select('id', 'portrait', 'nickname', 'sex', 'born_year', 'school')
                                                ->take(6)
                                                ->get()
                                                ->toArray();
                        }

                        foreach ($match_users as $users => $value) {
                            $match_users[$users]['portrait'] = route('home') . '/' . 'portrait/' . $match_users[$users]['portrait'];
                            $match_users[$users]['grade']    = $profile->grade;
                        }

                        return Response::json(
                            array(
                                'status' => 1,
                                'users'  => $match_users,
                                'match'  => $match_count
                            )
                        );

                        break;
                }
            }
        }
    }

} else {
    return Response::json(
        array(
            'status'        => 0 // Throw exception
        )
    );
}
