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
     * WAP index
     * @return response
     */
    public function getIndex()
    {
        // Determin cookie
        if (Cookie::get('sex')) {
            return Redirect::route('wap.members');
        } else {
            // Determin user sex
            $sex = Input::get('sex');
            if ($sex) {
                switch ($sex) {
                    case 'M':
                        Cookie::queue('sex', 'M'); // Male
                        // Generate w_id and password
                        $w_id       = rand(100000,999999);
                        $password   = rand(100000,999999);
                        // Determin male user phone number exists
                        while (User::where('w_id', $w_id)->first()) {
                            // Generate w_id
                            $w_id = rand(100000,999999);
                        }

                        // Verification success, add user
                        $user               = new User;
                        $user->w_id         = $w_id;
                        $user->password     = md5($password);
                        $user->sex          = $sex;
                        $user->from         = 0; // Signup from website
                        $user->activated_at = date('Y-m-d H:m:s');
                        $user->save();

                        $profile            = new Profile;
                        $profile->user_id   = $user->id;
                        $profile->save();

                        Cookie::queue('w_id', $w_id);
                        Cookie::queue('password', $password);

                        Queue::push('AddUserQueue', [
                                        'username'  => $user->id,
                                        'password'  => $user->password,
                                    ]);

                        // Create floder to store chat record
                        File::makeDirectory(app_path('chatrecord/user_' . $user->id, 0777, true));

                       break;

                    default:
                        $cookie = Cookie::queue('sex', 'F'); // Male
                        // Generate w_id and password
                        $w_id       = rand(100000,999999);
                        $password   = rand(100000,999999);
                        // Determin male user phone number exists
                        while (User::where('w_id', $w_id)->first()) {
                            // Generate w_id
                            $w_id = rand(100000,999999);
                        }

                        // Verification success, add user
                        $user               = new User;
                        $user->w_id         = $w_id;
                        $user->password     = md5($password);
                        $user->sex          = $sex;
                        $user->from         = 0; // Signup from website
                        $user->activated_at = date('Y-m-d H:m:s');
                        $user->save();

                        $profile            = new Profile;
                        $profile->user_id   = $user->id;
                        $profile->save();

                        Cookie::queue('w_id', $w_id);
                        Cookie::queue('password', $password);

                        Queue::push('AddUserQueue', [
                                        'username'  => $user->id,
                                        'password'  => $user->password,
                                    ]);

                        // Create floder to store chat record
                        File::makeDirectory(app_path('chatrecord/user_' . $user->id, 0777, true));

                        break;
                }
                return Redirect::route('wap.members');
            } else {
                return View::make('wap.index');
            }
        }
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
