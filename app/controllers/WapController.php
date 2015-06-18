<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Wap Version Controller.
 *
 * @uses        Laravel The PHP frameworks for web artisans http://laravel.com
 * @author      Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright   Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link        http://www.jinglingkj.com
 * @license     Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version     Release: 0.1 2015-04-26
 */

class WapController extends BaseController
{
    /**
     * Sign-up use WeChat ID with OAuth
     * @return resopnse
     */
    public function getWechatAuth()
    {
        // Initial WeChat Application
        $wechat_app = System::where('name', 'wechat')->first();
        // App ID
        $app_id     = $wechat_app->sid;
        // App Secret
        $app_secret = $wechat_app->secret;
        // Authority URL
        $auth_url   = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $app_id . '&redirect_uri=' . urlencode(route('wap.auth')) . '&response_type=code&scope=snsapi_userinfo&state=' . time() . '#wechat_redirect';
        // code from WeChat
        $code       = Input::get('code');
        // User authority
        if (!is_null($code)) {
            // Send cURL
            $auth_response   = cURL::newRequest('get', 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $app_id . '&secret=' . $app_secret . '&code=' . $code . '&grant_type=authorization_code')->send();
            // Json decode response body
            $auth_response   = json_decode($auth_response->body, true);
            $access_token    = $auth_response['access_token'];
            $openid          = $auth_response['openid'];
            // Send cURL
            $signup_response = cURL::newRequest('get', 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN')->send();
            // Json decode response body
            $signup_response = json_decode($signup_response->body, true);
            // User Open ID
            $openid          = $signup_response['openid'];
            // User nickname
            $nickname        = $signup_response['nickname'];
            // User sex 1 - male 2 - female 0 - no set
            $sex             = $signup_response['sex'];
            // User language preference
            $language        = $signup_response['language'];
            // User avatar URL
            $headimgurl      = urldecode($signup_response['headimgurl']);
            // Retrieve user
            $user_exist      = User::where('openid', $openid)->first();
            // Cookie control
            Cookie::forget('openid');
            Cookie::queue('openid', $openid, 60);
            // Determin user already exist
            if (is_null($user_exist)) {
                switch ($sex) {
                    case '1':
                        // Male user generate w_id and password
                        $w_id       = rand(100000, 999999);
                        $password   = rand(100000, 999999);
                        // Determin male user phone number exists
                        while (User::where('w_id', $w_id)->first()) {
                            // Generate w_id
                            $w_id   = rand(100000, 999999);
                        }

                        // Verification success, add user
                        $user               = new User;
                        $user->w_id         = $w_id;
                        $user->openid       = $openid;
                        $user->nickname     = $nickname;
                        $user->passcode     = $password;
                        $user->password     = md5($password);
                        $user->portrait     = uniqid();
                        $user->sex          = 'M';
                        $user->from         = 0; // Signup from website
                        $user->activated_at = date('Y-m-d H:m:s');
                        $user->save();
                        // Create user's profile
                        $profile            = new Profile;
                        $profile->user_id   = $user->id;
                        $profile->save();
                        // Storage remote images to local server
                        Image::make($headimgurl)->save(public_path('portrait/') . $user->portrait);
                        // Register Easemob IM system
                        Queue::push('AddUserQueue', [
                                        'username'  => $user->id,
                                        'password'  => $user->password,
                                    ]);

                        // Create floder to store chat record
                        File::makeDirectory(app_path('chatrecord/user_' . $user->id, 0777, true));
                    break;

                    default:
                        // Female user generate w_id and password
                        $w_id       = rand(100000, 999999);
                        $password   = rand(100000, 999999);
                        // Determin male user w_id number exists
                        while (User::where('w_id', $w_id)->first()) {
                            // Generate w_id
                            $w_id = rand(100000,999999);
                        }

                        // Verification success, add user
                        $user               = new User;
                        $user->w_id         = $w_id;
                        $user->openid       = $openid;
                        $user->nickname     = $nickname;
                        $user->passcode     = $password;
                        $user->password     = md5($password);
                        $user->portrait     = uniqid() . '.jpg';
                        $user->sex          = 'M';
                        $user->from         = 0; // Signup from website
                        $user->activated_at = date('Y-m-d H:m:s');
                        $user->save();
                        // Create user's profile
                        $profile            = new Profile;
                        $profile->user_id   = $user->id;
                        $profile->save();
                        // Storage remote images to local server
                        Image::make($headimgurl)->save(public_path('portrait/') . $user->portrait);
                        // Register Easemob IM system
                        Queue::push('AddUserQueue', [
                                        'username'  => $user->id,
                                        'password'  => $user->password,
                                    ]);

                        // Create floder to store chat record
                        File::makeDirectory(app_path('chatrecord/user_' . $user->id, 0777, true));
                    break;
                }
                // User ID
                $id         = $user->id;
                $provinces  = Province::select('id', 'province')->get();
                return View::make('wap.set_province')->with(compact('provinces', 'id'));
            } else {
                Cookie::forget('openid');
                Cookie::queue('openid', $user_exist->openid, 60);
                $profile    = Profile::where('id', $user_exist->id)->first();
                $id         = $user_exist->id;

                // Determin user if complete school information
                if ($user_exist->school == "") {
                    $provinces = Province::select('id', 'province')->get();
                    return View::make('wap.set_province')->with(compact('provinces', 'id'));
                } elseif ($profile->tag_str == "") {
                    // Determin user if complete tags information
                    return View::make('wap.set_tag')->with(compact('id'));
                } elseif ($profile->grade == "") {
                    // Determin user if complete grade information
                    return View::make('wap.data')->with(compact('id'));
                } else {
                    // All information complete
                    return Redirect::route('wap.get_like_jobs', $id)->with(compact('id'));
                }

            }
        } else {
            // WeChat sharing URL
            return Redirect::to($auth_url);
        }

    }

    /**
     * User select province step
     * @param  int $id User ID
     * @return response
     */
    public function getSetProvince($id)
    {
        // Retrieve user
        $user = User::find($id);
        // Determin cookie
        if (Cookie::get('openid') == $user->openid) {
            $province_id = Input::get('province_id');
            // Determin user exists
            if (!is_null($user)) {
                $universities = University::where('province_id', $province_id)->get();
                return View::make('wap.set_university')->with(compact('universities', 'id'));
            }
        } else {
            // Need to re-authority
            return Redirect::route('wap.auth');
        }
    }

    /**
     * User set university step
     * @param  int $id User ID
     * @return response     redirect
     */
    public function getSetUniversity($id)
    {
        // Retrieve user
        $user = User::find($id);
        // Determin cookie
        if (Cookie::get('openid') == $user->openid) {
            $university_id = Input::get('university_id');
            if (!is_null($user)) {
                // Determin user if set school
                if (is_null($user->school)) {
                    // First set school
                    University::find($university_id)->increment('count');
                } else {
                    if ($user->school != University::find($university_id)->university) {
                        University::find($university_id)->increment('count');
                        University::where('university', $user->school)->decrement('count');
                    }
                }

                $user->school = University::find($university_id)->university;
                $user->save();
                return View::make('wap.set_tag')->with(compact('id'));
            }

        } else {
            return Redirect::route('wap.auth');
        }
    }

    /**
     * User select tags step
     * @param  int $id User ID
     * @return response
     */
    public function getSetTag($id)
    {
        // Retrieve user
        $user = User::find($id);
        // Determin cookie
        if (Cookie::get('openid') == $user->openid) {
            $profile          = Profile::where('user_id', $id)->first();
            $profile->tag_str = e(Input::get('tag_str'));
            $profile->save();
            return View::make('wap.data')->with(compact('id'));
        } else {
            return Redirect::route('wap.auth');
        }
    }

    /**
     * User set other information include grade, constellation and bio
     * @param  int $id User IS
     * @return response
     */
    public function getSetData($id)
    {
        // Retrieve user
        $user = User::find($id);
        // Determin cookie
        if (Cookie::get('openid') == $user->openid) {
            if (!is_null($user)) {
                $born_year              = e(Input::get('born_year'));
                $grade                  = e(Input::get('grade'));
                $bio                    = e(app_input_filter(Input::get('bio')));
                $constellation          = e(Input::get('constellation'));
                // Set user's information
                $user->born_year        = $born_year;
                $user->bio              = $bio;
                $user->save();
                // Set user's profile
                $profile                = Profile::where('user_id', $id)->first();
                $profile->grade         = $grade;
                $profile->constellation = $constellation;
                $profile->save();

                return Redirect::route('wap.get_like_jobs', $id)->with(compact('id'));
            }
        } else {
            return Redirect::route('wap.auth');
        }
    }

    /**
     * WAP index
     * @return response
     */
    public function getIndex()
    {
        // Determin cookie
        if (Cookie::get('openid')) {
            // Retrieve user
            $user = User::where('openid', Cookie::get('openid'))->first();
            return Redirect::route('wap.get_like_jobs', $user->id);
        } else {
            // Initial WeChat Application
            $wechat_app = System::where('name', 'wechat')->first();
            // App ID
            $app_id     = $wechat_app->sid;
            // App Secret
            $app_secret = $wechat_app->secret;
            // Authority URL
            $auth_url   = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $app_id . '&redirect_uri=' . urlencode(route('wap.auth')) . '&response_type=code&scope=snsapi_userinfo&state=' . time() . '#wechat_redirect';
            return Redirect::to($auth_url);
        }

        // // Determin cookie
        // if (Cookie::get('sex')) {
        //     return Redirect::route('wap.members');
        // } else {
        //     // Determin user sex
        //     $sex = Input::get('sex');
        //     if ($sex) {
        //         switch ($sex) {
        //             case 'M':
        //                 // Male
        //                 Cookie::queue('sex', 'M');
        //                 // Generate w_id and password
        //                 $w_id       = rand(100000,999999);
        //                 $password   = rand(100000,999999);
        //                 // Determin male user phone number exists
        //                 while (User::where('w_id', $w_id)->first()) {
        //                     // Generate w_id
        //                     $w_id = rand(100000,999999);
        //                 }

        //                 // Verification success, add user
        //                 $user               = new User;
        //                 $user->w_id         = $w_id;
        //                 $user->password     = md5($password);
        //                 $user->sex          = $sex;
        //                 $user->from         = 0; // Signup from website
        //                 $user->activated_at = date('Y-m-d H:m:s');
        //                 $user->save();
        //                 // Create user's profile
        //                 $profile            = new Profile;
        //                 $profile->user_id   = $user->id;
        //                 $profile->save();

        //                 Cookie::queue('w_id', $w_id);
        //                 Cookie::queue('password', $password);

        //                 Queue::push('AddUserQueue', [
        //                                 'username'  => $user->id,
        //                                 'password'  => $user->password,
        //                             ]);

        //                 // Create floder to store chat record
        //                 File::makeDirectory(app_path('chatrecord/user_' . $user->id, 0777, true));

        //                break;

        //             default :
        //                 // Female
        //                 $cookie   = Cookie::queue('sex', 'F');
        //                 // Generate w_id and password
        //                 $w_id     = rand(100000,999999);
        //                 $password = rand(100000,999999);
        //                 // Determin male user phone number exists
        //                 while (User::where('w_id', $w_id)->first()) {
        //                     // Generate w_id
        //                     $w_id = rand(100000,999999);
        //                 }

        //                 // Verification success, add user
        //                 $user               = new User;
        //                 $user->w_id         = $w_id;
        //                 $user->password     = md5($password);
        //                 $user->sex          = $sex;
        //                 $user->from         = 0; // Signup from website
        //                 $user->activated_at = date('Y-m-d H:m:s');
        //                 $user->save();

        //                 $profile            = new Profile;
        //                 $profile->user_id   = $user->id;
        //                 $profile->save();

        //                 Cookie::queue('w_id', $w_id);
        //                 Cookie::queue('password', $password);

        //                 Queue::push('AddUserQueue', [
        //                                 'username'  => $user->id,
        //                                 'password'  => $user->password,
        //                             ]);

        //                 // Create floder to store chat record
        //                 File::makeDirectory(app_path('chatrecord/user_' . $user->id, 0777, true));

        //                 break;
        //         }
        //         return Redirect::route('wap.members');
        //     } else {
        //         return View::make('wap.index');
        //     }
        // }
    }

    /**
     * Wechat advertising index
     * @return response
     */
    public function getMembers()
    {
        // Determin cookie
        if (Cookie::get('sex')) {
            $users = array(8,2724,758,15,2346,1730,1341,77,319,2708,1745,1533,419,1621,2321,1,2563,1591,1774,317);
            return View::make('wap.members')->with(compact('users', 'categories'));
        } else {
            return Redirect::route('wap.index');
        }
    }

    /**
     * Like jobs list
     * @param  int $id User ID
     * @return response
     */
    public function getLikeJobs($id)
    {
        // Retrieve user
        $user = User::find($id);
        // Determin cookie
        if (Cookie::get('openid') == $user->openid) {
            $query      = LikeJobs::select('id', 'title', 'user_id')
                        ->orderBy('id', 'desc');
            $datas      = $query->paginate(20);
            return View::make('wap.jobs')->with(compact('datas', 'id'));
        } else {
            return Redirect::route('wap.auth');
        }
    }

    /**
     * Show like job
     * @param  int $id USer ID
     * @return response
     */
    public function getShowLikeJob($id)
    {
        // Retrieve user
        $user = User::find($id);
        // Determin cookie
        if (Cookie::get('openid') == $user->openid) {
            $job_id = Input::get('job_id');
            $job    = LikeJobs::find($job_id);
            $user   = User::find($job->user_id);
            return View::make('wap.re_detail')->with(compact('id', 'job', 'user'));

        } else {
            return Redirect::route('wap.auth');

        }
    }

    /**
     * Members index
     * @param  int $id User ID
     * @return response
     */
    public function getMembersIndex($id)
    {
        // Retrieve user
        $user = User::find($id);
        // Determin cookie
        if (Cookie::get('openid') == $user->openid) {
            switch ($user->sex) {
                case 'M':
                    $query  = User::whereNotNull('portrait')
                                ->where('sex', 'F')
                                ->where('block', 0)
                                ->whereNotNull('nickname')
                                ->orderBy('updated_at', 'desc');
                    $datas  = $query->paginate(20);
                    break;

                default:
                    $query  = User::whereNotNull('portrait')
                                ->where('sex', 'F')
                                ->where('block', 0)
                                ->whereNotNull('nickname')
                                ->orderBy('updated_at', 'desc');
                    $datas  = $query->paginate(20);
                    break;
            }
            return View::make('wap.members_index')->with(compact('id', 'datas', 'user'));
        } else {
            return Redirect::route('wap.auth');
        }
    }

    /**
     * Show members profile
     * @param  int $id User ID
     * @return response view
     */
    public function getMembersShow($id)
    {
        // Retrieve user
        $user = User::find($id);
        // Determin cookie
        if (Cookie::get('openid') == $user->openid) {
            $user_id           = Input::get('user_id');
            $data              = User::find($user_id);
            $profile           = Profile::where('user_id', $user_id)->first();
            // Get user's constellation
            $constellationInfo = getConstellation($profile->constellation);
            $tag_str           = array_unique(explode(',', substr($profile->tag_str, 1)));
            return View::make('wap.show')->with(compact('id', 'data', 'profile', 'constellationInfo', 'tag_str'));
        } else {
            return Redirect::route('wap.auth');

        }
    }

    /**
     * Download mobile client application
     * @param  int $id User ID
     * @return response     view
     */
    public function getDownloadApp($id)
    {
        // Retrieve user
        $user = User::find($id);
        // Determin cookie
        if (Cookie::get('openid') == $user->openid) {
            $type = Input::get('type');
            switch ($type) {
                case 'recruit':
                    $user = User::find($id);
                    return View::make('wap.download_recruit')->with(compact('id', 'user'));
                    break;

                case 'tab':
                    $user = User::find($id);
                    return View::make('wap.download_tab')->with(compact('id', 'user'));
                    break;

                default:
                    $user      = User::find($id);
                    $friend_id = Input::get('friend_id');
                    $friend    = User::find($friend_id);
                    return View::make('wap.download_default')->with(compact('id', 'user', 'friend'));
                    break;
            }
        } else {
            return Redirect::route('wap.auth');

        }

    }

    /**
     * Show members profile
     * @param  int $id user ID
     * @return response view
     */
    public function getOffice($id)
    {
        // Retrieve user
        $user = User::find($id);
        // Determin cookie
        if (Cookie::get('openid') == $user->openid) {
            return View::make('wap.office')->with(compact('id', 'user'));
        } else {
            return Redirect::route('wap.auth');

        }
    }

    /**
     * Match user dairy
     * @param  int $id User ID
     * @return response     view
     */
    public function getFate($id)
    {
        // Retrieve user
        $user = User::find($id);
        // Determin cookie
        if (Cookie::get('openid') == $user->openid) {
            // Retrieve user sex
            $sex     = $user->sex;
            // Retrieve user profile
            $profile =  Profile::where('user_id', $id)->first();
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
                                            ->get();
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
                                            ->get();
                    }
                    return View::make('wap.fate')->with(compact('id', 'match_users', 'user'));
                    break;

                default :
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
                                            ->get();
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
                                            ->get();
                    }
                    return View::make('wap.fate')->with(compact('id', 'match_users', 'user'));
                    break;
            }
            return View::make('wap.fate')->with(compact('id', 'job', 'user'));
        } else {
            return Redirect::route('wap.auth');

        }
    }

    /**
     * Show members profile
     * @param  int $id user ID
     * @return response view
     */
    public function getShow($id)
    {
        // Determin user sex
        $cookie = Cookie::get('sex');
        if ($cookie) {
            $data              = User::where('id', $id)->first();
            $profile           = Profile::where('user_id', $id)->first();
            // Get user's constellation
            $constellationInfo = getConstellation($profile->constellation);
            $tag_str           = array_unique(explode(',', substr($profile->tag_str, 1)));
            return View::make('wap.show')->with(compact('data', 'profile', 'constellationInfo', 'tag_str'));
        } else {
            return Redirect::route('wap.index');
        }
    }

    /**
     * Show more members
     * @return response view
     */
    public function getMore()
    {
        // Determin user sex
        $cookie = Cookie::get('sex');
        if ($cookie) {
            $query  = User::whereNotNull('portrait')
                        ->where('block', 0)
                        ->whereNotNull('nickname')
                        ->orderBy('updated_at', 'desc');
            $datas = $query->paginate(20);

            return View::make('wap.more')->with(compact('datas'));
        } else {
            return Redirect::route('wap.index');
        }
    }

    /**
     * Send add friend qequest success (fake)
     * @return response
     */
    public function getSuccess()
    {
        // Determin user sex
        $cookie = Cookie::get('sex');
        if ($cookie) {
            return View::make('wap.success');
        } else {
            return Redirect::route('wap.index');
        }
    }

    /**
     * Show a tip when download something at wechat app
     * @return response
     */
    public function getRedirect()
    {
        return View::make('wap.redirect');
    }
}
