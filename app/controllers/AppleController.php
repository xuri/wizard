<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Class include main iOS client API
 *
 * Code Explanation
 *
 * Status Code Explanation
 *
 * - from = 0 (default)     Signup from Website
 * - from = 1               Signup from Android
 * - from = 3               Signup from iOS Client
 * - from = 4               Add user by administrator
 * - from = 5               Signup from WAP
 *
 * Forum Code Explanation
 *
 * - status = 0             Response fail
 * - status = 1             Forum is open and response success
 * - status = 2             Forum is closed and response success
 *
 * @uses        Laravel The PHP frameworks for web artisans http://laravel.com
 * @author      Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright   Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link        http://www.jinglingkj.com
 * @license     Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version     Release: 0.1 2014-12-25
 */

class AppleController extends BaseController
{

    /**
     * Main iOS API
     *
     * @api
     *
     * @return Json All response are in JSON format.
     */
    public function postApple()
    {

        $token  = Input::get('token');
        $action = Input::get('action');

        // Define token
        if ($token == 'jciy9ldJ') {
            switch ($action) {

                /*
                |--------------------------------------------------------------------------
                | Authority Section
                |--------------------------------------------------------------------------
                |
                */

                // Signin

                case 'login' :
                    // Credentials
                    $credentials = array(
                        'email'     => Input::get('phone'),
                        'password'  => md5(Input::get('password')
                    ));
                    $phone_credentials = array(
                        'phone'     => Input::get('phone'),
                        'password'  => md5(Input::get('password')
                    ));
                    $w_id_credentials = array(
                        'w_id'      => Input::get('phone'),
                        'password'  => md5(Input::get('password')
                    ));

                    if (Auth::attempt($credentials) || Auth::attempt($phone_credentials) || Auth::attempt($w_id_credentials)) {

                        // Retrieve user
                        $user = User::where('phone', Input::get('phone'))->orWhere('email', Input::get('phone'))->orWhere('w_id', Input::get('phone'))->first();

                        // Update reveiver_updated_at in like table
                        DB::table('like')->where('receiver_id', $user->id)->update(array('receiver_updated_at' => Carbon::now()));

                        // Signin success, redirect to the previous page that was blocked
                        return Response::json(
                            array(
                                'status'    => 1,
                                'id'        => $user->id,
                                'password'  => $user->password,
                                'sex'       => $user->sex,
                                'portrait'  => route('home') . '/' . 'portrait/' . $user->portrait
                            )
                        );
                    } else {
                        return Response::json(
                            array(
                                'status'        => 0
                            )
                        );
                    }

                    break;

                // Signup

                case 'signup' :

                    // Get all form data.
                    $data = Input::all();

                    // Create validation rules
                    $rules = array(
                        'phone'               => 'required|digits:11|unique:users',
                        'password'            => 'required|between:6,16'
                    );

                    // Custom validation message
                    $messages = array(
                        'phone.required'      => '请输入手机号码。',
                        'phone.digits'        => '请输入正确的手机号码。',
                        'phone.unique'        => '此手机号码已被使用。',
                        'password.required'   => '请输入密码。',
                        'password.between'    => '密码长度请保持在:min到:max位之间。'
                    );

                    // Begin verification
                    $validator   = Validator::make($data, $rules, $messages);
                    $phone       = Input::get('phone');
                    if ($validator->passes()) {

                        // Verification success, add user
                        $user               = new User;
                        $user->phone        = $phone;

                        // Signup from 1 - Android, 2 - iOS
                        $user->from         = Input::get('from');
                        $user->activated_at = date('Y-m-d G:i:s');
                        $user->password     = md5(Input::get('password'));

                        // Client set sex
                        if (null !== Input::get('sex')) {
                            $user->sex          = e(Input::get('sex'));
                        }

                        if ($user->save()) {
                            $profile            = new Profile;
                            $profile->user_id   = $user->id;
                            $profile->tag_str   = ',40';
                            $profile->save();

                            // Add user success and chat Register
                            $easemob            = getEasemob();

                            // newRequest or newJsonRequest returns a Request object
                            $regChat            = cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users', ['username' => $user->id, 'password' => $user->password])
                                ->setHeader('content-type', 'application/json')
                                ->setHeader('Accept', 'json')
                                ->setHeader('Authorization', 'Bearer ' . $easemob->token)
                                ->setOptions([CURLOPT_VERBOSE => true])
                                ->send();

                            // Respond body
                            $result             = json_decode($regChat->body, true);

                            if (isset($result['entities'])) {
                                // Determine register status from Easemob
                                //
                                if ($result['entities']['0']['activated'] == true) {
                                    // Create floder to store chat record
                                    File::makeDirectory(app_path('chatrecord/user_' . $user->id, 0777, true));

                                    // Redirect to a registration page, prompts user to activate
                                    // Signin success, redirect to the previous page that was blocked
                                    return Response::json(
                                        array(
                                            'status'    => 1,
                                            'id'        => $user->id,
                                            'password'  => $user->password
                                        )
                                    );
                                } else {
                                    // Add user success, but register fail in Easemob
                                    $user->forceDelete();
                                    $profile->forceDelete();

                                    return Response::json(
                                        array(
                                            'status'        => 0
                                        )
                                    );
                                }
                            } else {
                                // Add user success, but register fail in Easemob
                                $user->forceDelete();
                                $profile->forceDelete();

                                return Response::json(
                                    array(
                                        'status'        => 0
                                    )
                                );
                            }
                        } else {
                            // Add user success, but register fail in Easemob
                            $user->forceDelete();
                            $profile->forceDelete();

                            return Response::json(
                                array(
                                    'status'        => 0
                                )
                            );
                        }
                    } else {
                        // Verification fail
                        $_validator = $validator->getMessageBag()->toArray();

                        //  Checks if the phone key exists in the array
                        if (array_key_exists('phone', $_validator)) {
                            $phone_error = implode('', $_validator['phone']);
                        } else {
                            $phone_error = null;
                        }

                        //  Checks if the password key exists in the array
                        if (array_key_exists('password', $_validator)) {
                            $password_error = implode('', $_validator['password']);
                        } else {
                            $password_error = null;
                        }

                        // Verification fail
                        return Response::json(
                            array(
                                'status'        => 0,
                                'error'         => array(
                                                        'phone'    => $phone_error,
                                                        'password' => $password_error
                                                    )
                            )
                        );
                    }

                    break;

                // Profile complete

                case 'complete' :

                    // Get all form data
                    $info = array(
                        'nickname'      => Input::get('nickname'),
                        'constellation' => Input::get('constellation'),
                        'portrait'      => Input::get('portrait'),
                        'tag_str'       => Input::get('tag_str'),
                        'born_year'     => Input::get('born_year'),
                        'grade'         => Input::get('grade'),
                        'hobbies'       => Input::get('hobbies'),
                        'self_intro'    => Input::get('self_intro'),
                        'bio'           => Input::get('bio'),
                        'question'      => Input::get('question'),
                        'school'        => Input::get('school'),
                    );

                    // Create validation rules
                    $rules = array(
                        'nickname'      => 'required|between:1,30',
                        'constellation' => 'required',
                        'tag_str'       => 'required',
                        'born_year'     => 'required',
                        'grade'         => 'required',
                        'hobbies'       => 'required',
                        'self_intro'    => 'required',
                        'bio'           => 'required',
                        'question'      => 'required',
                        'school'        => 'required',
                    );

                    // Custom validation message
                    $messages = array(
                        'nickname.required'         => '请输入昵称',
                        'nickname.between'          => '昵称长度请保持在:min到:max字之间',
                        'constellation.required'    => '请选择星座',
                        'tag_str.required'          => '给自己贴个标签吧',
                        'born_year.required'        => '请选择出生年',
                        'grade.required'            => '请选择入学年',
                        'hobbies.required'          => '填写你的爱好',
                        'self_intro.required'       => '请填写个人简介',
                        'bio.required'              => '请填写你的真爱寄语',
                        'question.required'         => '记得填写爱情考验哦',
                        'school.required'           => '请选择所在学校',
                    );

                    // Begin verification
                    $validator = Validator::make($info, $rules, $messages);

                    if ($validator->passes()) {
                        // Verification success
                        // Update account
                        $user                   = User::where('id', Input::get('id'))->first();
                        $oldPortrait            = $user->portrait;
                        $user->nickname         = app_input_filter(Input::get('nickname'));

                        // Protrait section
                        $portrait               = Input::get('portrait');

                        if ($portrait == null) {
                            $user->portrait     = $oldPortrait;  // User not update avatar
                        } else {
                            // User update avatar
                            $portraitPath       = public_path('portrait/');
                            $user->portrait     = 'android/' . $portrait; // Save file name to database
                        }

                        if (is_null($user->sex)) {
                            $user->sex          = Input::get('sex');
                        }

                        if (is_null($user->born_year)) {
                            $user->born_year    = Input::get('born_year');
                        }

                        $user->bio              = app_input_filter(Input::get('bio'));
                        $school                 = Input::get('school');

                        if (is_null($user->school)) {
                            // First set school
                            University::where('university', $school)->increment('count');
                        } else {
                            if ($user->school != $school) {
                                University::where('university', $school)->increment('count');
                                University::where('university', $user->school)->decrement('count');
                            }
                        }
                        $user->school           = $school;

                        // Update profile information
                        $profile                = Profile::where('user_id', $user->id)->first();
                        $profile->tag_str       = implode(',', array_unique(explode(',', Input::get('tag_str'))));
                        $profile->grade         = Input::get('grade');
                        $profile->hobbies       = app_input_filter(Input::get('hobbies'));
                        $profile->self_intro    = app_input_filter(Input::get('self_intro'));
                        $profile->question      = app_input_filter(Input::get('question'));

                        // User's constellation filter
                        if (Input::get('constellation') != 0) {
                            $profile->constellation = e(Input::get('constellation'), NULL);
                        }

                        if ($user->save() && $profile->save()) {

                            // Update success
                            if ($portrait != null) { // User update avatar
                                // Determine user portrait type
                                $asset = strpos($oldPortrait, 'android');

                                // Should to use !== false
                                if ($asset !== false) {
                                    // No nothing
                                } else {
                                    // User set portrait from web delete old poritait
                                    File::delete($portraitPath . $oldPortrait);
                                }

                            }
                            return Response::json(
                                array(
                                    'status'    => 1
                                )
                            );

                        } else {
                            // Update fail
                            return Response::json(
                                array(
                                    'status'    => 0
                                )
                            );
                        }
                    } else {
                        // Verification fail, redirect back
                        return Response::json(
                            array(
                                'status'        => 0,
                                'info'          => $validator
                            )
                        );
                    }

                    break;

                // Members

                case 'members_index' :

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

                    break;

                // Members show profile

                case 'members_show' :

                    // Get all form data
                    $info = array(

                        // Current signin user phone number on App
                        'phone'   => Input::get('senderid'),

                        // Which user want to see
                        'user_id' => Input::get('userid'),
                    );

                    if ($info) {
                        // Sender user ID
                        $sender_id  = Input::get('senderid');
                        $user_id    = Input::get('userid');
                        $data       = User::where('id', $user_id)->first();
                        $profile    = Profile::where('user_id', $user_id)->first();
                        $like       = Like::where('sender_id', $sender_id)->where('receiver_id', $user_id)->first();
                        $like_me    = Like::where('sender_id', $user_id)->where('receiver_id', $sender_id)->first();
                        if ($like) {
                            $likeCount = $like->count;
                        } else {
                            $likeCount = 0;
                        }

                        // Determine user renew status
                        if ($profile->crenew >= 30) {
                            $crenew = 1;
                        } else {
                            $crenew = 0;
                        }

                        if (is_null($like_me)) {
                            // This user never liked you
                            $user_like_me   = 5;
                            $answer         = null;
                        } else {
                            // Determine users relationship, see code explanation in MembersController
                            $user_like_me   = $like_me->status;
                            // User liked answer
                            $answer         = $like_me->answer;
                        }

                        // Get user's constellation
                        $constellationInfo = getConstellation($profile->constellation);

                        // Get user's tag
                        if (is_null($profile->tag_str)) {
                            $tag_str = e(null);
                        } else {
                            // Convert string to array and remove duplicate tags code
                            $tag_str = array_unique(explode(',', substr($profile->tag_str, 1)));
                        }

                        return Response::json(
                            array(
                                'status'        => 1,
                                'user_id'       => e($data->id),
                                'sex'           => e($data->sex),
                                'bio'           => app_out_filter($data->bio),
                                'nickname'      => app_out_filter($data->nickname),
                                'born_year'     => e($data->born_year),
                                'school'        => e($data->school),
                                'is_admin'      => e($data->is_admin),
                                'is_verify'     => e($data->is_verify),
                                'portrait'      => route('home') . '/' . 'portrait/' . $data->portrait,
                                'points'        => e($data->points),
                                'constellation' => $constellationInfo['name'],
                                'tag_str'       => $tag_str,
                                'hobbies'       => app_out_filter($profile->hobbies),
                                'grade'         => e($profile->grade),
                                'question'      => app_out_filter($profile->question),
                                'self_intro'    => app_out_filter($profile->self_intro),
                                'like'          => e($likeCount),
                                'user_like_me'  => e($user_like_me),
                                'answer'        => app_out_filter($answer),
                                'crenew'        => e($crenew),
                            )
                        );
                    } else {
                        return Response::json(
                            array(
                                'status'        => 0
                            )
                        );
                    }

                    break;

                // Profile

                case 'account' :

                    // Get all form data
                    $info = array(
                        'id'   => Input::get('id'),
                    );

                    if ($info) {
                        // Retrieve user
                        $user               = User::find(Input::get('id'));
                        $profile            = Profile::where('user_id', $user->id)->first();

                        // Get user's constellation
                        $constellationInfo  = getConstellation($profile->constellation);

                        // Get user's tag
                        if (is_null($profile->tag_str)) {
                            $tag_str = e(null);
                        } else {
                            // Convert string to array and remove duplicate tags code
                            $tag_str = array_unique(explode(',', substr($profile->tag_str, 1)));
                        }

                        $data = array(
                                'status'        => 1,
                                'sex'           => e($user->sex),
                                'bio'           => app_out_filter($user->bio),
                                'nickname'      => app_out_filter($user->nickname),
                                'born_year'     => e($user->born_year),
                                'school'        => e($user->school),
                                'portrait'      => route('home') . '/' . 'portrait/' . $user->portrait,
                                'constellation' => $constellationInfo['name'],
                                'hobbies'       => app_out_filter($profile->hobbies),
                                'tag_str'       => $tag_str,
                                'grade'         => e($profile->grade),
                                'question'      => app_out_filter($profile->question),
                                'self_intro'    => app_out_filter($profile->self_intro),
                                'is_verify'     => e($user->is_verify),
                                'points'        => $user->points
                            );
                        return Response::json($data);
                    } else {
                        return Response::json(
                            array(
                                'status'        => 0
                            )
                        );
                    }

                    break;

                // Like

                case 'like' :

                    // Get all form data.
                    $data   = Input::all();

                    // Retrieve user
                    $user   = User::find(Input::get('id'));

                    // Determin user portrait is set
                    if (isset($user->portrait)) {

                        // Determin user profile is complete
                        if (isset($user->nickname) && isset($user->school) && isset($user->bio)) {

                            // Create validation rules
                            $rules  = array(
                                'id'            => 'required',
                                'receiverid'    => 'required',
                                // 'answer'     => 'required|min:3',
                            );

                            // Custom validation message
                            $messages = array(
                                'answer.required'   => '请回答爱情考验问题。',
                                // 'answer.min'     => '至少要写:min个字哦。',
                            );

                            // Begin verification
                            $validator   = Validator::make($data, $rules, $messages);

                            if ($validator->passes()) {
                                $user           = User::find(Input::get('id'));
                                $receiver_id    = Input::get('receiverid');

                                if ($user->points > 0) {
                                    $have_like = Like::where('sender_id', $user->id)->where('receiver_id', $receiver_id)->first();

                                    // This user already sent like
                                    if ($have_like) {
                                        $have_like->answer  = app_input_filter(Input::get('answer'));
                                        $have_like->count   = $have_like->count + 1;
                                        $user->points       = $user->points - 1;

                                        if ($have_like->save() && $user->save()) {

                                            // Some user re-liked you
                                            $notification = Notification(2, $user->id, $receiver_id);

                                            // Add push notifications for App client to queue
                                            Queue::push('LikeQueue', [
                                                                        'target'    => $receiver_id,
                                                                        'action'    => 2,
                                                                        'from'      => $user->id,

                                                                        // Notification ID
                                                                        'id'        => e($notification->id),
                                                                        'content'   => app_out_filter($user->nickname) . '再次追你了，快去查看一下吧',
                                                                        'sender_id' => e(Input::get('id')),
                                                                        'portrait'  => route('home') . '/' . 'portrait/' . $user->portrait,
                                                                        'nickname'  => app_out_filter($user->nickname),
                                                                        'answer'    => app_out_filter(Input::get('answer'))
                                                                    ]);

                                            return Response::json(
                                                array(
                                                    'status'        => 1
                                                )
                                            );
                                        }
                                    } else { // First like
                                        $like               = new Like();
                                        $like->sender_id    = $user->id;
                                        $like->receiver_id  = $receiver_id;
                                        $like->status       = 0; // User send like, pending accept
                                        $like->answer       = app_input_filter(Input::get('answer'));
                                        $like->count        = 1;
                                        $user->points       = $user->points - 1;

                                        // Determin repeat add points
                                        $points_exist = Like::where('receiver_id', $receiver_id)
                                                    ->where('created_at', '>=', Carbon::today())
                                                    ->count();

                                        // Add points
                                        if ($points_exist < 2) {
                                            User::find($receiver_id)->increment('points', 1);
                                        }

                                        if ($like->save() && $user->save()) {
                                            $notification = Notification(1, $user->id, $receiver_id); // Some user first like you

                                            // Add push notifications for App client to queue
                                            Queue::push('LikeQueue', [
                                                                        'target'    => $receiver_id,
                                                                        'action'    => 1,
                                                                        'from'      => $user->id,
                                                                        'content'   => app_out_filter($user->nickname) . '追你了，快去查看一下吧',

                                                                        // Notification ID
                                                                        'id'        => e($notification->id),
                                                                        'sender_id' => e(Input::get('id')),
                                                                        'portrait'  => route('home') . '/' . 'portrait/' . $user->portrait,
                                                                        'nickname'  => app_out_filter($user->nickname),
                                                                        'answer'    => app_out_filter(Input::get('answer'))
                                                                    ]);

                                            return Response::json(
                                                array(
                                                    'status'        => 1
                                                )
                                            );
                                        }
                                    }
                                } else {
                                    return Response::json(
                                        array(
                                            'status'    => 2 // User's point required
                                        )
                                    );
                                }
                            } else {
                                return Response::json(
                                    array(
                                        'status'        => 0
                                    )
                                );
                            }
                        } else {
                            return Response::json(
                                array(
                                    // User profile uncompleted
                                    'status'        => 3
                                )
                            );
                        }
                    } else {
                        return Response::json(
                            array(
                                // User portrait not set
                                'status'        => 4
                            )
                        );
                    }

                    break;

                // Sent

                case 'sent' :
                    // Post last user id from App client
                    $last_id    = Input::get('lastid');

                    // Post count per query from App client
                    $per_page   = Input::get('perpage');

                    // Get user id
                    $user_id    = Input::get('id');

                    // If App have post last user id
                    if ($last_id) {
                        // Query all user liked users
                        $allLike    = Like::where('sender_id', $user_id)
                            ->orderBy('id', 'desc')
                            ->select('receiver_id', 'status', 'created_at', 'count')
                            ->where('id', '<', $last_id)
                            ->take($per_page)
                            ->get()
                            ->toArray();

                        // Replace receiver_id key name to portrait
                        foreach ($allLike as $key1 => $val1) {
                            foreach ($val1 as $key => $val) {
                                $new_key                = str_replace('receiver_id', 'portrait', $key);
                                $new_array[$new_key]    = $val;
                            }
                            $likes[] = $new_array;
                        }

                        // Replace receiver ID to receiver portrait
                        foreach ($likes as $key => $field) {

                            // Retrieve receiver user
                            $user                       = User::where('id',  $likes[$key]['portrait'])->first();

                            // Receiver ID
                            $likes[$key]['id']          = e($user->id);

                            // Receiver avatar real storage path
                            $likes[$key]['portrait']    = route('home') . '/' . 'portrait/' . $user->portrait;

                            // Receiver school
                            $likes[$key]['school']      = e($user->school);

                            // Receiver nickname
                            $likes[$key]['name']        = app_out_filter($user->nickname);

                            // Receiver sex
                            $likes[$key]['sex']         = e($user->sex);

                            // Convert how long liked
                            $Date_1                     = date('Y-m-d'); // Current date and time
                            $Date_2                     = date('Y-m-d', strtotime($likes[$key]['created_at']));
                            $d1                         = strtotime($Date_1);
                            $d2                         = strtotime($Date_2);

                            // Calculate liked time
                            $Days                       = round(($d1-$d2)/3600/24);
                            $likes[$key]['created_at']  = $Days;
                        }

                        if ($allLike) {
                            return Response::json(
                                array(
                                    'status'    => 1,
                                    'data'      => $likes
                                )
                            );
                        } else {
                            return Response::json(
                                array(
                                    'status'    => 0
                                )
                            );
                        }
                    } else {

                        // First get data from App client and query last like id in database
                        $lastRecord = Like::where('sender_id', $user_id)->orderBy('id', 'desc')->first();

                        // Determin like exist
                        if (is_null($lastRecord)) {
                            return Response::json(
                                array(
                                    'status'    => 1,
                                    'data'      => array()
                                )
                            );
                        } else {

                            // Query all user liked users
                            $allLike    = Like::where('sender_id', $user_id)
                                ->orderBy('id', 'desc')
                                ->select('receiver_id', 'status', 'created_at', 'count')
                                ->where('id', '<=', $lastRecord->id)
                                ->take($per_page)
                                ->get()
                                ->toArray();

                            // Replace receiver_id key name to portrait
                            foreach ($allLike as $key1 => $val1) {
                                foreach ($val1 as $key => $val) {
                                    $new_key                = str_replace('receiver_id', 'portrait', $key);
                                    $new_array[$new_key]    = $val;
                                }
                                $likes[] = $new_array;
                            }

                            // Replace receiver ID to receiver portrait
                            foreach ($likes as $key => $field) {

                                // Retrieve receiver user
                                $user                       = User::where('id',  $likes[$key]['portrait'])->first();

                                // Receiver ID
                                $likes[$key]['id']          = e($user->id);

                                // Receiver avatar real storage path
                                $likes[$key]['portrait']    = route('home') . '/' . 'portrait/' . $user->portrait;

                                // Receiver school
                                $likes[$key]['school']      = e($user->school);

                                // Receiver nickname
                                $likes[$key]['name']        = app_out_filter($user->nickname);

                                // Receiver sex
                                $likes[$key]['sex']         = e($user->sex);

                                // Convert how long liked
                                // Current date and time
                                $Date_1                     = date('Y-m-d');
                                $Date_2                     = date('Y-m-d', strtotime($likes[$key]['created_at']));
                                $d1                         = strtotime($Date_1);
                                $d2                         = strtotime($Date_2);

                                // Calculate liked time
                                $Days                       = round(($d1-$d2)/3600/24);
                                $likes[$key]['created_at']  = $Days;
                            }

                            if ($allLike) {
                                return Response::json(
                                    array(
                                        'status'    => 1,
                                        'data'      => $likes
                                    )
                                );
                            } else {
                                return Response::json(
                                    array(
                                        'status'        => 0
                                    )
                                );
                            }
                        }
                    }

                    break;

                // Inbox

                case 'inbox' :

                    // Post last user id from App client
                    $last_id    = Input::get('lastid');

                    // Post count per query from App client
                    $per_page   = Input::get('perpage');

                    // Get user id
                    $user_id    = Input::get('id');

                    // If App have post last user id
                    if ($last_id != 'null') {
                        // Query all user liked users
                        $allLike    = Like::where('receiver_id', $user_id)
                            ->orderBy('id', 'desc')
                            ->select('sender_id', 'status', 'created_at', 'count')
                            ->where('id', '<', $last_id)
                            ->take($per_page)
                            ->get()
                            ->toArray();

                        // Replace sender_id key name to portrait
                        foreach ($allLike as $key1 => $val1) {
                            foreach ($val1 as $key => $val) {
                                $new_key                = str_replace('sender_id', 'portrait', $key);
                                $new_array[$new_key]    = $val;
                            }
                            $likes[] = $new_array;
                        }

                        // Replace receiver ID to receiver portrait
                        foreach ($likes as $key => $field) {

                            // Receiver ID
                            $likes[$key]['id']          = $likes[$key]['portrait'];

                            // Receiver avatar real storage path
                            $likes[$key]['portrait']    = route('home') . '/' . 'portrait/' . User::where('id', $likes[$key]['portrait'])->first()->portrait;

                            // Receiver school
                            $likes[$key]['school']      = User::where('id', $likes[$key]['id'])->first()->school;

                            // Receiver ID
                            $likes[$key]['name']        = app_out_filter(User::where('id', $likes[$key]['id'])->first()->nickname);

                            // Convert how long liked
                            $Date_1                     = date('Y-m-d');

                            // Current date and time
                            $Date_2                     = date('Y-m-d', strtotime($likes[$key]['created_at']));
                            $d1                         = strtotime($Date_1);
                            $d2                         = strtotime($Date_2);

                            // Calculate liked time
                            $Days                       = round(($d1-$d2)/3600/24);
                            $likes[$key]['created_at']  = $Days;
                        }

                        if ($allLike) {
                            return Response::json(
                                array(
                                    'status'    => 1,
                                    'data'      => $likes
                                )
                            );
                        } else {
                            return Response::json(
                                array(
                                    'status'        => 0
                                )
                            );
                        }
                    } else { // First get data from App client

                        // Query last like id in database
                        $lastRecord = Like::where('receiver_id', $user_id)->orderBy('id', 'desc')->first()->id;

                        // Query all user liked users
                        $allLike    = Like::where('receiver_id', $user_id)
                            ->orderBy('id', 'desc')
                            ->select('sender_id', 'status', 'created_at', 'count')
                            ->where('id', '<=', $lastRecord)
                            ->take($per_page)
                            ->get()
                            ->toArray();

                        // Replace receiver_id key name to portrait
                        foreach ($allLike as $key1 => $val1) {
                            foreach ($val1 as $key => $val) {
                                $new_key                = str_replace('sender_id', 'portrait', $key);
                                $new_array[$new_key]    = $val;
                            }
                            $likes[] = $new_array;
                        }

                        // Replace receiver ID to receiver portrait
                        foreach ($likes as $key => $field) {

                            // Receiver ID
                            $likes[$key]['id']          = $likes[$key]['portrait'];

                            // Receiver avatar real storage path
                            $likes[$key]['portrait']    = route('home') . '/' . 'portrait/' . User::where('id', $likes[$key]['portrait'])->first()->portrait;

                            // Receiver school
                            $likes[$key]['school']      = User::where('id', $likes[$key]['id'])->first()->school;

                            // Receiver ID
                            $likes[$key]['name']        = app_out_filter(User::where('id', $likes[$key]['id'])->first()->nickname);

                            // Convert how long liked
                            $Date_1                     = date('Y-m-d');

                            // Current date and time
                            $Date_2                     = date('Y-m-d', strtotime($likes[$key]['created_at']));
                            $d1                         = strtotime($Date_1);
                            $d2                         = strtotime($Date_2);

                            // Calculate liked time
                            $Days                       = round(($d1-$d2)/3600/24);
                            $likes[$key]['created_at']  = $Days;
                        }

                        if ($allLike) {
                            return Response::json(
                                array(
                                    'status'    => 1,
                                    'data'      => $likes
                                )
                            );
                        } else {
                            return Response::json(
                                array(
                                    'status'        => 0
                                )
                            );
                        }
                    }

                    break;

                // Accept

                case 'accept' :

                    // Get sender ID from client
                    $id             = Input::get('senderid');
                    $sender         = User::where('id', $id)->first();

                    // Get receiver ID from client
                    $receiver_id    = Input::get('receiverid');
                    $receiver       = User::where('id', $receiver_id)->first();
                    $like           = Like::where('sender_id', $id)->where('receiver_id', $receiver_id)->first();

                    // Receiver accept like
                    $like->status   = 1;

                    // Add friend relationship in chat system and start chat
                    Queue::push('AddFriendQueue', [
                                            'user_id'   => $receiver_id,
                                            'friend_id' => $id,
                                        ]);
                    Queue::push('AddFriendQueue', [
                                            'user_id'   => $id,
                                            'friend_id' => $receiver_id,
                                        ]);

                    if ($like->save()) {
                        // Save notification in database for website
                        $notification   = Notification(3, $receiver_id, $id); // Some user accept you like

                        // Add push notifications for App client to queue
                        Queue::push('LikeQueue', [
                                                    'target'    => $id,
                                                    'action'    => 3,
                                                    'from'      => $receiver_id,

                                                    // Notification ID
                                                    'id'        => e($notification->id),
                                                    'content'   => app_out_filter($receiver->nickname) . '接受了你的邀请，快去查看一下吧',
                                                    'sender_id' => e($receiver_id),
                                                    'portrait'  => route('home') . '/' . 'portrait/' . $receiver->portrait,
                                                    'nickname'  => app_out_filter($receiver->nickname),
                                                    'answer'    => null
                                                ]);

                        return Response::json(
                                array(
                                    'status'    => 1,
                                    'id'        => $id,
                                    'portrait'  => route('home') . '/' . 'portrait/' . $sender->portrait,
                                    'nickname'  => app_out_filter($sender->nickname)
                                )
                            );
                    } else {
                        return Response::json(
                                array(
                                    'status'        => 0
                                )
                            );
                    }

                    break;

                // Reject

                case 'reject' :

                    // Get sender ID from client
                    $id             = Input::get('senderid');

                    // Get receiver ID from client
                    $receiver_id    = Input::get('receiverid');
                    $receiver       = User::where('id', $receiver_id)->first();
                    $like           = Like::where('sender_id', $id)->where('receiver_id', $receiver_id)->first();

                    // Receiver reject user, remove friend relationship in chat system
                    $like->status   = 2;

                    if ($like->save()) {
                        // Save notification in database for website
                        $notification   = Notification(4, $receiver_id, $id); // Some user reject you like

                        // $easemob     = getEasemob();
                        // Push notifications to App client
                        // cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
                        //      'target_type'   => 'users',
                        //      'target'        => [$id],
                        //      'msg'           => ['type' => 'cmd', 'action' => '4'],
                        //      'from'          => $receiver_id,
                        //      'ext'           => [
                        //                              'content'   => $receiver->nickname.'拒绝了你的邀请',
                        //                              'id'        => $receiver_id,
                        //                              'portrait'  => route('home').'/'.'portrait/'.$receiver->portrait,
                        //                              'nickname'  => $receiver->nickname
                        //                          ]
                        //  ])
                        //      ->setHeader('content-type', 'application/json')
                        //      ->setHeader('Accept', 'json')
                        //      ->setHeader('Authorization', 'Bearer '.$easemob->token)
                        //      ->setOptions([CURLOPT_VERBOSE => true])
                        //      ->send();
                        return Response::json(
                                array(
                                    'status'        => 1
                                )
                            );
                    } else {
                        return Response::json(
                                array(
                                    'status'        => 0
                                )
                            );
                    }

                    break;

                // Block

                case 'block' :
                    $id             = Input::get('senderid');
                    $receiver_id    = Input::get('id');
                    $like           = Like::where('sender_id', $id)->where('receiver_id', $receiver_id)->first();

                    // Receiver block user, remove friend relationship in chat system
                    $like->status   = 3;

                    // Some user blocked you
                    $notification   = Notification(5, $id, $receiver_id);

                    // Remove friend relationship in chat system
                    Queue::push('DeleteFriendQueue', [
                                            'user_id'   => $receiver_id,
                                            'block_id'  => $id,
                                        ]);
                    Queue::push('DeleteFriendQueue', [
                                            'user_id'   => $id,
                                            'block_id'  => $receiver_id,
                                        ]);

                    // Push notifications to App client
                    // cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
                    //      'target_type'   => 'users',
                    //      'target'        => [$id],
                    //      'msg'           => ['type' => 'cmd', 'action' => '5'],
                    //      'from'          => $receiver_id,
                    //      'ext'           => ['content' => User::where('id', $receiver_id)->first()->nickname.'把你加入了黑名单', 'id' => $notification->id]
                    //  ])
                    //      ->setHeader('content-type', 'application/json')
                    //      ->setHeader('Accept', 'json')
                    //      ->setHeader('Authorization', 'Bearer '.$easemob->token)
                    //      ->setOptions([CURLOPT_VERBOSE => true])
                    //      ->send();

                    if ($like->save()) {
                        return Response::json(
                            array(
                                'status'        => 1
                            )
                        );
                    } else {
                        return Response::json(
                            array(
                                'status'        => 0
                            )
                        );
                    }

                    break;

                // Renew

                case 'renew' :
                    if (Input::get('dorenew') != 'null') {
                        $renew      = Input::get('renew');
                        $today      = Carbon::today();
                        $yesterday  = Carbon::yesterday();
                        $user       = Profile::where('user_id', Input::get('id'))->first();
                        $points     = User::where('id', Input::get('id'))->first();

                        if ($user->renew_at == '0000-00-00 00:00:00') { // First renew
                            $user->renew_at = Carbon::now();
                            $user->renew    = $user->renew + 1;
                            $user->crenew   = $user->crenew + 1;
                            $points->points = $points->points + 5;
                            $user->save();
                            $points->save();

                            return Response::json(
                                array(
                                    'status'        => 1,
                                    'renewdays'     => e($user->renew)
                                )
                            );
                        } elseif ($today >= $user->renew_at) {

                            // Check user whether or not renew yesterday
                            if ($yesterday <= $user->renew_at) {
                                // Keep renew
                                $user->crenew   = $user->crenew + 1;
                            } else {
                                // Not keep renew, reset renew count
                                $user->crenew   = 0;
                            }

                            // You haven't renew today
                            $user->renew_at = Carbon::now();
                            $user->renew    = $user->renew + 1;
                            $points->points = $points->points + 1;
                            $user->save();
                            $points->save();

                            return Response::json(
                                array(
                                    'status'        => 1,
                                    'renewdays'     => e($user->renew)
                                )
                            );
                        } else {
                            // You have renew today
                            return Response::json(
                                array(
                                    'status'        => 2
                                )
                            );
                        }
                    } else {
                        return Response::json(
                            array(
                                'status'    => 1,
                                'renewdays' => e(Profile::where('user_id', Input::get('id'))->first()->renew)
                            )
                        );
                    }

                    break;

                // Get user friends nickname

                case 'getnickname' :

                    // Get query ID from App client
                    $id      = Input::get('id');

                    // Get sender user data
                    $friends = Like::where('receiver_id', $id)->orWhere('sender_id', $id)
                                ->where('status', 1)
                                ->select('sender_id', 'receiver_id')
                                ->get()
                                ->toArray();

                    foreach ($friends as $key => $field) {

                            // Determine user is sender or receiver
                            if ($friends[$key]['sender_id'] == $id) {

                                // User is sender and retrieve receiver user
                                $user = User::where('id', $friends[$key]['receiver_id'])->first();

                                // Friend ID
                                $friends[$key]['friend_id'] = $user->id;

                                // Friend nickname
                                $friends[$key]['nickname']  = app_out_filter($user->nickname);

                                // Determine user portrait
                                if (is_null($user->portrait)) {

                                    // Friend portrait
                                    $friends[$key]['portrait']  = null;
                                } else {

                                    // Friend portrait
                                    $friends[$key]['portrait']  = route('home') . '/' . 'portrait/' . $user->portrait;
                                }
                            } else {

                                // User is receiver and retrieve sender user
                                $user = User::where('id', $friends[$key]['sender_id'])->first();

                                // Friend ID
                                $friends[$key]['friend_id'] = $user->id;

                                // Friend nickname
                                $friends[$key]['nickname']  = app_out_filter($user->nickname);

                                // Determine user portrait
                                if (is_null($user->portrait)) {

                                    // Friend portrait
                                    $friends[$key]['portrait']  = null;
                                } else {

                                    // Friend portrait
                                    $friends[$key]['portrait']  = route('home'). '/' . 'portrait/' . $user->portrait;
                                }
                            }
                        }

                    // Query successful
                    if ($friends) {
                        return Response::json(
                            array(
                                'status'    => 1,
                                'data'      => $friends
                            )
                        );
                    } else {
                        return Response::json(
                            array(
                                'status'    => 0
                            )
                        );
                    }

                    break;

                // Set avatar

                case 'setportrait' :

                    // Retrieve user
                    $user           = User::where('id', Input::get('id'))->first();

                    // Old portrait
                    $oldPortrait    = $user->portrait;

                    // Get user portrait name
                    $portrait       = Input::get('portrait');

                    // User not update portrait
                    if ($portrait == $oldPortrait) {
                        // Direct return success
                        return Response::json(
                            array(
                                'status'        => 1
                            )
                        );
                    } else {

                        // User update avatar
                        $portraitPath       = public_path('portrait/');

                        // Save file name to database
                        $user->portrait     = 'android/' . $portrait;

                        if ($user->save()) {
                            // Update success
                            $oldAndroidPortrait = strpos($oldPortrait, 'android');
                            if($oldAndroidPortrait === false) { // Must use ===
                                // Delete old poritait
                                File::delete($portraitPath . $oldPortrait);
                                return Response::json(
                                    array(
                                        'status'    => 1
                                    )
                                );
                            } else {
                                // Update success
                                return Response::json(
                                    array(
                                        'status'    => 1
                                    )
                                );
                            }
                        } else {
                            // Update fail
                            return Response::json(
                                array(
                                    'status'    => 0
                                )
                            );
                        }
                    }

                    break;

                // Upload portrait

                case 'uploadportrait' :

                    // Retrieve user
                    $user           = User::where('id', Input::get('id'))->first();

                    // Old pritrait
                    $oldPortrait    = $user->portrait;

                    // Portrait data
                    $portrait       = Input::get('portrait');

                    // Portrait MIME
                    $mime           = Input::get('mime');

                    // User update avatar
                    if ($portrait != null) {
                        $portrait           = str_replace('data:image/' . $mime . ';base64,', '', $portrait);
                        $portrait           = str_replace(' ', '+', $portrait);
                        $portraitData       = base64_decode($portrait);

                        // Decode string
                        $portraitPath       = public_path('portrait/');

                        // Portrait file name
                        $portraitFile       = uniqid() . '.' . $mime;

                        // Store file
                        $successPortrait    = file_put_contents($portraitPath . $portraitFile, $portraitData);

                        // Save file name to database
                        $user->portrait     = $portraitFile;

                        if ($user->save()) {
                            // Determine user portrait type
                            $asset = strpos($oldPortrait, 'android');

                            // Should to use !== false
                            if ($asset !== false) {
                                // No nothing
                            } else {
                                // User set portrait from web delete old poritait
                                File::delete($portraitPath . $oldPortrait);
                            }
                            // Update success
                            return Response::json(
                                array(
                                    'status'    => 1
                                )
                            );
                        } else {
                            // Update fail
                            return Response::json(
                                array(
                                    'status'    => 0
                                )
                            );
                        }
                    }

                    break;

                /*
                |--------------------------------------------------------------------------
                | Forum Section
                |--------------------------------------------------------------------------
                |
                */

                // Get Forum Category

                case 'forum_getcat' :
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

                    break;

                // Forum Get to Show Post
                case 'forum_getpost' :
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

                    break;

                // Forum Post Comments

                case 'forum_postcomment' :

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

                    break;

                // Forum Post New

                case 'forum_postnew' :

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

                    break;

                // Upload Images

                case 'uploadimage' :

                    // Get all json format data from App client and json decode data
                    $items  = json_decode(Input::get('data'));

                    // Create an empty array to store path of upload image
                    $path   = array();

                    // Foreach upload data
                    foreach ($items as $key => $item) {
                        $image          = str_replace('data:image/' . $item['0'] . ';base64,', '', $item['1']);
                        $image          = str_replace(' ', '+', $image);

                        // Decode string
                        $imageData      = base64_decode($image);

                        // Define upload path
                        $imagePath      = public_path('upload/image/android_upload/');

                        // Portrait file name
                        $imageFile      = uniqid() . '.' . $item['0'];

                        // Store file
                        $successUpload  = file_put_contents($imagePath . $imageFile, $imageData);
                        $path[]['img']      = route('home') . '/upload/image/android_upload/' . $imageFile;
                    }
                    // Create success
                    return Response::json(
                        array(
                            'status'    => 1,
                            'path'      => $path
                        )
                    );

                    break;

                // Get Notifications
                case 'get_notifications' :

                    // Post number chars of items summary from App client
                    $numchars           = Input::get('numchars');

                    // Post number chars of original items summary from App client
                    $original_numchars  = Input::get('original_numchars');

                    // Get user ID from App client
                    $id                 = Input::get('id');

                    // Retrieve all user's notifications
                    $notifications = Notification::where('receiver_id', $id)
                                        ->whereIn('category', array(6, 7))
                                        ->where('status', 0) // Unread flag
                                        ->select('id', 'category', 'sender_id', 'receiver_id', 'category_id', 'post_id', 'comment_id', 'reply_id', 'created_at')
                                        ->orderBy('created_at' , 'desc')
                                        ->get()
                                        ->toArray();

                    // Build format
                    foreach ($notifications as $key => $notification) {

                        // Retrieve sender
                        $sender                     = User::where('id', $notifications[$key]['sender_id'])->first();

                        // Determine user set portrait
                        if ($sender->portrait) {

                            // Get user portrait
                            $notifications[$key]['portrait']    = route('home') . '/' . 'portrait/' . $sender->portrait;
                        } else {

                            // Return null
                            $notifications[$key]['portrait']    = null;
                        }

                        // Determine user set nuckname
                        if ($sender->nickname) {

                            // Get user nickname
                            $notifications[$key]['nickname']    = app_out_filter($sender->nickname);
                        } else {

                            // Return null
                            $notifications[$key]['nickname']    = null;
                        }

                        // Determine category
                        if ($notifications[$key]['category'] == 6) {

                            // Comment
                            $post                                       = ForumPost::where('id', $notifications[$key]['post_id'])->first();

                            // Retrieve comment
                            $comment                                    = ForumComments::where('id', $notifications[$key]['comment_id'])->first();

                            // Add comment content summary to content key
                            $notifications[$key]['content']             = app_out_filter(getplaintextintrofromhtml($comment->content, $numchars));

                            // Add post content summary to original_content key
                            $notifications[$key]['original_content']    = app_out_filter(getplaintextintrofromhtml($post->content, $numchars));

                        } else {

                            // Reply
                            $comment                                    = ForumComments::where('id', $notifications[$key]['comment_id'])->first();

                            // Retrieve reply
                            $reply                                      = ForumReply::where('id', $notifications[$key]['reply_id'])->first();

                            // Add reply content summary to content key
                            $notifications[$key]['content']             = app_out_filter(getplaintextintrofromhtml($reply->content, $numchars));

                            // Add post content summary to original_content key
                            $notifications[$key]['original_content']    = app_out_filter(getplaintextintrofromhtml($comment->content, $original_numchars));
                        }
                    }

                    Notification::where('receiver_id', $id)->whereIn('category', array(6, 7))->update(array('status' => 1));

                    // Build Json format
                    return Response::json(
                        array(
                            'status'    => 1,
                            'data'      => $notifications
                        )
                    );

                    break;

                // Forum Get Reply
                case 'forum_getreply' :

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

                    break;

                // Get user posts
                case 'get_userposts' :

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

                    break;

                // Delete forum post
                case 'delete_userpost';

                    // Get post ID in forum for delete
                    $postId     = Input::get('postid');

                    // Retrieve post
                    $forumPost  = ForumPost::where('id', $postId)->first();

                    // Using expression get all picture attachments (Only with pictures stored on this server.)
                    preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $forumPost->content, $match );

                    // Construct picture attachments list
                    $srcArray   = array_pop($match);

                    // This post have picture attachments
                    if (!empty( $srcArray )) {
                        // Foreach picture attachments list array
                        foreach ($srcArray as $key => $field) {

                            // Convert to correct real storage path
                            $srcArray[$key] = str_replace(route('home'), '', $srcArray[$key]);

                            // Destory upload picture attachments in this post
                            File::delete(public_path($srcArray[$key]));
                        }

                        // Delete post in forum
                        if ($forumPost->delete()) {
                            return Response::json(
                                array(
                                    'status'    => 1
                                )
                            );
                        } else {
                            return Response::json(
                                array(
                                    'status'    => 0
                                )
                            );
                        }
                    } else {

                        // Delete post in forum
                        if ($forumPost->delete()) {
                            return Response::json(
                                array(
                                    'status'    => 1
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

                    break;

                // Get User Portrait
                case 'get_portrait' :

                    // Retrieve
                    $user = User::find(Input::get('id'));

                    if ($user) {
                        // User exist
                        return Response::json(
                            array(
                                'status'    => 1,
                                'nickname'  => app_out_filter($user->nickname),
                                'portrait'  => route('home') . '/' . 'portrait/' . $user->portrait,
                                'points'    => $user->points,
                                'is_verify' => e($user->is_verify)
                            )
                        );
                    } else {
                        // User not exist
                        return Response::json(
                            array(
                                'status'    => 0
                            )
                        );
                    }

                    break;

                // Open university
                case 'open_university' :

                    // Retrieve opened and pending university
                    $universities       = University::whereIn('status', array(1, 2))->select('id', 'university', 'open_at', 'status')->orderBy('status', 'desc')->get()->toArray();

                    // Format pending time
                    foreach ($universities as $key => $value) {
                        $universities[$key]['open_at'] = e(date('m月d日', strtotime($universities[$key]['open_at'])));
                    }

                    array_push($universities, array(
                        'id'            => 0,
                        'university'    => '其他',
                        'open_at'       => 'none',
                        'status'        => 2
                    ));

                    // Build Json format
                    return Response::json(
                        array(
                            'status'    => 1,
                            'data'      => $universities
                        )
                    );

                    break;

                // Get forum unread notifications
                case 'get_forumunread' :

                    // Get user ID from App client and retrieve User
                    $user          = User::find(Input::get('id'));
                    $notifications = Notification::where('receiver_id', $user->id)
                                                ->whereIn('category', array(6, 7))
                                                ->where('status', 0)
                                                ->count();
                    if (is_null($notifications)) {

                        // No unread notifications, build Json format
                        return Response::json(
                            array(
                                'status'    => 1,
                                'num'       => 0
                            )
                        );
                    } else {

                        // Build Json format
                        return Response::json(
                            array(
                                'status'    => 1,
                                'num'       => $notifications
                            )
                        );
                    }

                    break;

                // Get open articles
                case 'get_openarticles' :

                    // Retrieve all open articles
                    $articles = Article::where('status', 1)->select('id', 'status', 'title', 'thumbnails', 'slug')->take(3)->get()->toArray();

                    // Add thumbnails images and article url to array
                    foreach ($articles as $key => $value) {
                        $articles[$key]['title']        = Str::limit($articles[$key]['title'], 15);
                        $articles[$key]['thumbnails']   = URL::to('/upload/thumbnails') . '/' . $articles[$key]['thumbnails'];
                        $articles[$key]['url']          = URL::to('/article') . '/' . $articles[$key]['slug'];
                    }

                    // Build Json format
                    return Response::json(
                        array(
                            'status'    => 1,
                            'data'      => $articles
                        )
                    );

                    break;

                // Recovery password
                case 'recovery_password' :

                    // Retrieve user
                    if ($user = User::where('phone', Input::get('phone'))->first()) {

                        // Update user password
                        $user->password = md5(Input::get('password'));

                        // Update successful
                        if ($user->save()) {

                            // Update user password in easemob
                            $easemob            = getEasemob();

                            // newRequest or newJsonRequest returns a Request object
                            $regChat            = cURL::newJsonRequest('put', 'https://a1.easemob.com/jinglingkj/pinai/users/' . $user->id . '/password', ['newpassword' => $user->password])
                                ->setHeader('content-type', 'application/json')
                                ->setHeader('Accept', 'json')
                                ->setHeader('Authorization', 'Bearer '.$easemob->token)
                                ->setOptions([CURLOPT_VERBOSE => true])
                                ->send();

                            return Response::json(
                                array(
                                    'status'    => 1
                                )
                            );
                        } else {

                            // Update fail
                            return Response::json(
                                array(
                                    'status'    => 0
                                )
                            );
                        }
                    } else {
                        // Can't find user
                        return Response::json(
                            array(
                                'status'    => 2
                            )
                        );
                    }

                    break;

                // Support
                case 'support' :

                    // Get feedback user id
                    $user_id            = Input::get('id');

                    // Format feedback content
                    $feedback           = app_input_filter(Input::get('content'));

                    // Check feedback exist
                    $feedback_exist     = Support::where('user_id', $user_id)
                                            ->where('content', $feedback)
                                            ->where('created_at', '>=', Carbon::today())
                                            ->count();

                    // User already send feedback today
                    if ($feedback_exist >= 1) {
                        return Response::json(
                            array(
                                'status'            => 0,
                                'empty'             => 0,
                                'repeat'            => 1
                            )
                        );
                    } else {
                        if ($feedback != "") {
                            $support            = new Support;
                            $support->user_id   = $user_id;
                            $support->content   = $feedback;
                            if ($support->save()) {
                                return Response::json(
                                    array(
                                        'status'        => 1,
                                        'empty'         => 0,
                                        'repeat'        => 0
                                    )
                                );
                            } else {
                                return Response::json(
                                    array(
                                        'status'        => 0,
                                        'empty'         => 0,
                                        'repeat'        => 0
                                    )
                                );
                            }
                        } else {
                            return Response::json(
                                array(
                                    'status'            => 0,
                                    'empty'             => 1,
                                    'repeat'            => 0
                                )
                            );
                        }
                    }

                    break;

                // Admin notifications
                case 'system_notifications' :
                    // Post user ID from App client
                    $id             = Input::get('id');

                    // Retrieve all system notifications
                    $notifications  = Notification::where('receiver_id', $id)
                                        ->whereIn('category', array(8, 9))
                                        ->orderBy('created_at' , 'desc')
                                        ->select('id', 'sender_id', 'created_at')
                                        ->where('status', 0)
                                        ->get()
                                        ->toArray();

                    $friend_notifications = Notification::where('receiver_id', $id)
                                        ->whereIn('category', array(1, 2))
                                        ->orderBy('created_at' , 'desc')
                                        ->select('id', 'sender_id', 'created_at', 'receiver_id', 'category')
                                        ->where('status', 0)
                                        ->get()
                                        ->toArray();

                    $accept_notifications = Notification::where('receiver_id', $id)
                                        ->whereIn('category', array(3))
                                        ->orderBy('created_at' , 'desc')
                                        ->select('id', 'sender_id', 'created_at', 'receiver_id', 'category')
                                        ->where('status', 0)
                                        ->get()
                                        ->toArray();

                    // Build array
                    foreach ($notifications as $key => $value) {
                        $notifications_content              = NotificationsContent::where('notifications_id', $notifications[$key]['id'])->first();
                        $notifications[$key]['content']     = $notifications_content->content;
                        $notifications[$key]['created_at']  = date('m-d G:i', strtotime($notifications_content->created_at));

                    }

                    foreach ($friend_notifications as $key => $value) {
                        switch ($friend_notifications[$key]['category']) {
                            case '1' :
                                $sender_user                            = User::find($friend_notifications[$key]['sender_id']);
                                $like                                   = Like::where('sender_id', $friend_notifications[$key]['sender_id'])->where('receiver_id', $friend_notifications[$key]['receiver_id'])->first();
                                $friend_notifications[$key]['content']  = app_out_filter($sender_user->nickname) . '追你了，快去看看吧';
                                $friend_notifications[$key]['nickname'] = app_out_filter($sender_user->nickname);
                                $friend_notifications[$key]['portrait'] = route('home') . '/' . 'portrait/' . $sender_user->portrait;
                                $friend_notifications[$key]['answer']   = $like->answer;
                                $friend_notifications[$key]['from']     = $friend_notifications[$key]['sender_id'];
                                break;

                            case '2' :
                                $sender_user                            = User::find($friend_notifications[$key]['sender_id']);
                                $like                                   = Like::where('sender_id', $friend_notifications[$key]['sender_id'])->where('receiver_id', $friend_notifications[$key]['receiver_id'])->first();
                                $friend_notifications[$key]['content']  = app_out_filter($sender_user->nickname) . '再次追你了，快去看看吧';
                                $friend_notifications[$key]['nickname'] = app_out_filter($sender_user->nickname);
                                $friend_notifications[$key]['portrait'] = route('home') . '/' . 'portrait/' . $sender_user->portrait;
                                $friend_notifications[$key]['answer']   = e($like->answer);
                                $friend_notifications[$key]['from']     = $friend_notifications[$key]['sender_id'];
                                break;
                        }
                    }

                    // Build notifications
                    foreach ($accept_notifications as $key => $value) {
                        $sender_user                            = User::find($accept_notifications[$key]['sender_id']);
                        $accept                                 = Like::where('sender_id', $accept_notifications[$key]['sender_id'])->where('receiver_id', $accept_notifications[$key]['receiver_id'])->first();
                        $accept_notifications[$key]['nickname'] = app_out_filter($sender_user->nickname);
                        $accept_notifications[$key]['portrait'] = route('home') . '/' . 'portrait/' . $sender_user->portrait;
                        $accept_notifications[$key]['from']     = $accept_notifications[$key]['sender_id'];
                    }

                    $data = array(
                            'system_notifications'  => $notifications,
                            'friend_notifications'  => $friend_notifications,
                            'accept_notifications'  => $accept_notifications
                        );

                    // Mark read for this user
                    Notification::where('receiver_id', $id)->update(array('status' => 1));

                    // Build Json format
                    return Response::json(
                        array(
                            'status'    => '1',
                            'data'      => $data
                        )
                    );

                    break;

                // Signout
                case 'signout' :

                    // USer ID
                    $id = Input::get('id');

                    if (User::find($id)) {
                        return Response::json(
                            array(
                                'status'        => 1
                            )
                        );
                    } else {
                        return Response::json(
                            array(
                                'status'        => 0
                            )
                        );
                    }

                    break;

                // Match Users
                case 'match_users':

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
                            // Calculate how long user last match time from now (more than 3 days)
                            if (strtotime(Carbon::now()) - strtotime($profile->match_at) >= 86400) {
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
                            // Calculate how long user last match time from now (more than 3 days)
                            if (strtotime(Carbon::now()) - strtotime($profile->match_at) >= 86400) {
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

                    break;

                // Macth user record
                case 'match_users_record':
                    // Get user ID
                    $user_id                          = Input::get('id');
                    // Get all match users ID
                    $match_users_id                   = Input::get('match_users_id');

                    // Determin user match if empty
                    if ($match_users_id != "") {
                        // Retrieve user profile
                        $profile                      =  Profile::where('user_id', $user_id)->first();

                        if (is_null($profile->match_users)) {
                            $profile->match_users     = $match_users_id;
                        } else {
                            if ($match_users_id != "") {
                                $profile->match_users = $profile->match_users . ',' . $match_users_id;
                            }
                        }

                        $profile->match               = $profile->match + count(explode(',', $match_users_id));
                        // Update last match time
                        $profile->match_at            = Carbon::now();

                        if ($profile->save()) {
                            // Add points
                            if ($profile->match = 3) {
                                User::find($user_id)->increment('points', 1);
                            }

                            return Response::json(
                                array(
                                    'status'    => 1 // Success
                                )
                            );
                        } else {
                            return Response::json(
                                array(
                                    'status'    => 0 // Throw exception
                                )
                            );
                        }
                    } else {
                        return Response::json(
                            array(
                                'status'        => 1 // Success
                            )
                        );
                    }

                    break;

                // Market
                case 'market' :

                    // Get user id from App client
                    $user_id            = Input::get('userid');

                    // Post last user id from App client
                    $last_id            = Input::get('lastid');

                    // Post count per query from App client
                    $per_page           = Input::get('perpage');

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

                        // University filter
                        if ($university_filter) {
                            if ($university_filter == '其他') {
                                $universities_list = University::where('status', 2)->select('university')->get()->toArray();
                                isset($university_filter) AND $query->whereNotIn('school', $universities_list);
                            } else {
                                isset($university_filter) AND $query->where('school', $university_filter);
                            }
                        }

                        $users = $query
                            ->orderBy('updated_at', 'desc')
                            ->where('block', 0)
                            ->select('id', 'nickname', 'school', 'sex', 'portrait', 'is_admin', 'is_verify', 'points')
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

                                // Retrieve tag_str with UTF8 encode
                                $users[$key]['tag_str']     = e(implode(',', array_slice(explode(',', trim($profile->tag_str,',')), 0, 2)));

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

                        // University filter
                        if ($university_filter) {
                            if ($university_filter == '其他') {
                                $universities_list = University::where('status', 2)->select('university')->get()->toArray();
                                isset($university_filter) AND $query->whereNotIn('school', $universities_list);
                            } else {
                                isset($university_filter) AND $query->where('school', $university_filter);
                            }
                        }

                        // Query last user id in database
                        $lastRecord = User::orderBy('updated_at', 'desc')->first()->updated_at;

                        $users      = $query
                                        ->orderBy('updated_at', 'desc')
                                        ->select('id', 'nickname', 'school', 'sex', 'portrait', 'is_admin', 'is_verify', 'points')
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

                                // Retrieve tag_str with UTF8 encode
                                $users[$key]['tag_str']     = e(implode(',', array_slice(explode(',', trim($profile->tag_str,',')), 0, 2)));

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

                    break;

                // Members rank
                case 'members_rank' :

                    // Get user id from App client
                    $user_id            = Input::get('userid');

                    // Post last user id from App client
                    $last_id            = Input::get('lastid');

                    // Post count per query from App client
                    $per_page           = Input::get('perpage');

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

                        $profile = Profile::where('user_id', $user->id)->first();
                    }

                    if ($last_id) {
                        // User last signin at time
                        $last_updated_at    = User::find($last_id)->points;

                        // App client have post last user id, retrieve and skip profile not completed user
                        $query              = User::whereNotNull('portrait')
                                                    ->whereNotNull('nickname')
                                                    ->whereNotNull('bio')
                                                    ->whereNotNull('school');

                        // University filter
                        if ($university_filter) {
                            if ($university_filter == '其他') {
                                $universities_list = University::where('status', 2)->select('university')->get()->toArray();
                                isset($university_filter) AND $query->whereNotIn('school', $universities_list);
                            } else {
                                isset($university_filter) AND $query->where('school', $university_filter);
                            }
                        }

                        $users = $query
                            ->orderBy('points', 'desc')
                            ->where('block', 0)
                            ->select('id', 'nickname', 'school', 'sex', 'portrait', 'is_admin', 'is_verify', 'points')
                            ->where('points', '<', $last_updated_at)
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

                        // First get data from App client, retrieve and skip profile not completed user
                        $query      = User::whereNotNull('portrait')
                                            ->whereNotNull('nickname')
                                            ->whereNotNull('bio')
                                            ->whereNotNull('school');

                        // University filter
                        if ($university_filter) {
                            if ($university_filter == '其他') {
                                $universities_list = University::where('status', 2)->select('university')->get()->toArray();
                                isset($university_filter) AND $query->whereNotIn('school', $universities_list);
                            } else {
                                isset($university_filter) AND $query->where('school', $university_filter);
                            }
                        }

                        // Query last user id in database
                        $lastRecord = User::orderBy('points', 'desc')->first()->points;

                        $users      = $query
                                        ->orderBy('points', 'desc')
                                        ->select('id', 'nickname', 'school', 'sex', 'portrait', 'is_admin', 'is_verify', 'points')
                                        ->where('block', 0)
                                        ->where('points', '<=', $lastRecord)
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
                                    'rank'      => $user->rank,
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

                    break;

                case 'post_like_job' :
                    // Get user id from App client
                    $user_id            = Input::get('userid');
                    // Job's title
                    $title              = Input::get('title');
                    // Job's content
                    $content            = Input::get('content');
                    // Job's first rule (require)
                    $rule_1             = Input::get('rule_1');
                    // Job's second rule (require)
                    $rule_2             = Input::get('rule_2');
                    // Job's third rule (optional)
                    $rule_3             = Input::get('rule_3');
                    // Job's forth rule (optional)
                    $rule_4             = Input::get('rule_4');
                    // Job's fifth rule (optional)
                    $rule_5             = Input::get('rule_5');
                    // Create new like jobs
                    $like_jobs          = new LikeJobs;
                    $like_jobs->user_id = $user_id;
                    $like_jobs->title   = $title;
                    $like_jobs->content = $content;
                    $like_jobs->rule_1  = $rule_1;
                    $like_jobs->rule_2  = $rule_2;

                    if (!is_null($rule_3)) {
                        $like_jobs->rule_3 = $rule_3;
                    }

                    if (!is_null($rule_4)) {
                        $like_jobs->rule_4 = $rule_4;
                    }

                    if (!is_null($rule_5)) {
                        $like_jobs->rule_5 = $rule_5;
                    }
                    // Determin create like jobs if successful
                    if ($like_jobs->save()) {
                        return Response::json(
                                array(
                                    'status'    => 1 // Success
                                )
                            );
                    } else {
                        return Response::json(
                                array(
                                    'status'    => 0 // Throw exception
                                )
                            );
                    }
                    break;

                // Like Jobs
                case 'like_jobs' :
                    // Post last user id from App client
                    $last_id  = Input::get('lastid');
                    // Post count per query from App client
                    $per_page = Input::get('perpage');
                    // Get user ID
                    $user_id  = Input::get('userid');
                    // Retrieve user
                    $user     = User::find($user_id);
                    // Determin user profile if complete
                    if (isset($user->nickname) && isset($user->school) && isset($user->bio) && isset($user->sex)) {
                        if ($last_id) {
                            // App client have post last like job id, retrieve like jobs
                            $query         = LikeJobs::select('id', 'title')
                                                ->orderBy('id', 'desc')
                                                ->where('id', '<', $last_id)
                                                ->take($per_page)
                                                ->get()
                                                ->toArray();

                            // Convert like job title in array
                            foreach ($query as $key => $value) {
                                // User ID
                                $user_id = $query[$key]['id'];
                                // Retrieve user
                                $user    = User::find($user_id);
                                switch ($user->sex) {
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
                            $query         = LikeJobs::select('id', 'title')
                                                ->orderBy('id', 'desc')
                                                ->take($per_page)
                                                ->get()
                                                ->toArray();

                            // Convert like job title in array
                            foreach ($query as $key => $value) {
                                // User ID
                                $user_id = $query[$key]['id'];
                                // Retrieve user
                                $user    = User::find($user_id);
                                switch ($user->sex) {
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
                            $query         = LikeJobs::select('id', 'title')
                                                ->orderBy('id', 'desc')
                                                ->where('id', '<', $last_id)
                                                ->take($per_page)
                                                ->get()
                                                ->toArray();

                            // Convert like job title in array
                            foreach ($query as $key => $value) {
                                // User ID
                                $user_id = $query[$key]['id'];
                                // Retrieve user
                                $user    = User::find($user_id);
                                switch ($user->sex) {
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
                            $query         = LikeJobs::select('id', 'title')
                                                ->orderBy('id', 'desc')
                                                ->take($per_page)
                                                ->get()
                                                ->toArray();

                            // Convert like job title in array
                            foreach ($query as $key => $value) {
                                // User ID
                                $user_id = $query[$key]['id'];
                                // Retrieve user
                                $user    = User::find($user_id);
                                switch ($user->sex) {
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

                    break;

                // Get Like Job
                case 'get_like_job':

                    // Retrieve like job ID
                    $like_job_id = Input::get('id');

                    // Retrieve like job
                    $like_job    = LikeJobs::find($like_job_id);

                    // Determin if like job exists
                    if ($like_job) {
                        // Retrieve user
                        $user     = User::find($like_job->user_id);
                        // Get user portrait
                        $portrait = route('home') . '/' . 'portrait/' . $user->portrait;

                        if ($like_job->rule_3 = "") {
                            $rule_3 = "\n3. " .  $like_job->rule_3;
                        } else {
                            $rule_3 = null;
                        }

                        if ($like_job->rule_4 = "") {
                            $rule_4 = "\n4. " .  $like_job->rule_4;
                        } else {
                            $rule_4 = null;
                        }

                        if ($like_job->rule_5 = "") {
                            $rule_5 = "\n5. " .  $like_job->rule_5;
                        } else {
                            $rule_5 = null;
                        }

                        return Response::json(
                                array(
                                    'status'   => 1, // Success
                                    'user_id'  => $user->id,
                                    'sex'      => $user->sex,
                                    'portrait' => $portrait,
                                    'title'    => e($like_job->title),
                                    'content'  => e($like_job->content . "\n\n 要求:\n1. " . $like_job->rule_1 . "\n2. " . $like_job->rule_2 . $rule_3 . $rule_4 . $rule_5),
                                )
                            );
                    } else {
                        return Response::json(
                                array(
                                    'status'    => 0 // Throw exception
                                )
                            );
                    }
                    break;
            }
        } else {
            return Response::json(
                array(
                    'status'        => 0
                )
            );
        }
    }
}
