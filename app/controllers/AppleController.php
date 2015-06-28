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

                    return include_once(app_path('controllers/Api/login.php'));

                    break;

                // Signup

                case 'signup' :

                    return include_once(app_path('controllers/Api/iOS/signup.php'));

                    break;

                // Profile complete

                case 'complete' :

                    return include_once(app_path('controllers/Api/complete.php'));

                    break;

                // Members

                case 'members_index' :

                    return include_once(app_path('controllers/Api/members_index.php'));

                    break;

                // Members show profile

                case 'members_show' :

                    return include_once(app_path('controllers/Api/members_show.php'));

                    break;

                // Profile

                case 'account' :

                    return include_once(app_path('controllers/Api/account.php'));

                    break;

                // Like

                case 'like' :

                    return include_once(app_path('controllers/Api/like.php'));

                    break;

                // Sent

                case 'sent' :

                    return include_once(app_path('controllers/Api/sent.php'));

                    break;

                // Inbox

                case 'inbox' :

                    return include_once(app_path('controllers/Api/inbox.php'));

                    break;

                // Accept

                case 'accept' :

                    return include_once(app_path('controllers/Api/accept.php'));

                    break;

                // Reject

                case 'reject' :

                    return include_once(app_path('controllers/Api/reject.php'));

                    break;

                // Block

                case 'block' :

                    return include_once(app_path('controllers/Api/block.php'));

                    break;

                // Renew

                case 'renew' :

                    return include_once(app_path('controllers/Api/renew.php'));

                    break;

                // Get user friends nickname

                case 'getnickname' :

                    return include_once(app_path('controllers/Api/getnickname.php'));

                    break;

                // Set avatar

                case 'setportrait' :

                    return include_once(app_path('controllers/Api/setportrait.php'));

                    break;

                // Upload portrait

                case 'uploadportrait' :

                    return include_once(app_path('controllers/Api/uploadportrait.php'));

                    break;

                /*
                |--------------------------------------------------------------------------
                | Forum Section
                |--------------------------------------------------------------------------
                |
                */

                // Get Forum Category

                case 'forum_getcat' :

                    return include_once(app_path('controllers/Api/forum_getcat.php'));

                    break;

                // Forum Get to Show Post
                case 'forum_getpost' :

                    return include_once(app_path('controllers/Api/iOS/forum_getpost.php'));

                    break;

                // Forum Post Comments

                case 'forum_postcomment' :

                    return include_once(app_path('controllers/Api/forum_postcomment.php'));

                    break;

                // Forum Post New

                case 'forum_postnew' :

                    return include_once(app_path('controllers/Api/forum_postnew.php'));

                    break;

                // Upload Images

                case 'uploadimage' :

                    return include_once(app_path('controllers/Api/uploadimage.php'));

                    break;

                // Get Notifications
                case 'get_notifications' :

                    return include_once(app_path('controllers/Api/get_notifications.php'));

                    break;

                // Forum Get Reply
                case 'forum_getreply' :

                    return include_once(app_path('controllers/Api/forum_getreply.php'));

                    break;

                // Get user posts
                case 'get_userposts' :

                    return include_once(app_path('controllers/Api/get_userposts.php'));

                    break;

                // Delete forum post
                case 'delete_userpost';

                    return include_once(app_path('controllers/Api/delete_userpost.php'));

                    break;

                // Get User Portrait
                case 'get_portrait' :

                    return include_once(app_path('controllers/Api/get_portrait.php'));

                    break;

                // Open university
                case 'open_university' :

                    return include_once(app_path('controllers/Api/open_university.php'));

                    break;

                // Get forum unread notifications
                case 'get_forumunread' :

                    return include_once(app_path('controllers/Api/get_forumunread.php'));

                    break;

                // Get open articles
                case 'get_openarticles' :

                    return include_once(app_path('controllers/Api/get_openarticles.php'));

                    break;

                // Recovery password
                case 'recovery_password' :

                    return include_once(app_path('controllers/Api/recovery_password.php'));

                    break;

                // Support
                case 'support' :

                    return include_once(app_path('controllers/Api/support.php'));

                    break;

                // Admin notifications
                case 'system_notifications' :

                    return include_once(app_path('controllers/Api/iOS/system_notifications.php'));

                    break;

                // Signout
                case 'signout' :

                    return include_once(app_path('controllers/Api/signout.php'));

                    break;

                // Match Users
                case 'match_users':

                    return include_once(app_path('controllers/Api/match_users.php'));

                    break;

                // Macth user record
                case 'match_users_record':

                    return include_once(app_path('controllers/Api/match_users_record.php'));

                    break;

                // Market
                case 'market' :

                    return include_once(app_path('controllers/Api/market.php'));

                    break;

                // Members rank
                case 'members_rank' :

                    return include_once(app_path('controllers/Api/members_rank.php'));

                    break;

                // Post Like Job
                case 'post_like_job' :

                    return include_once(app_path('controllers/Api/post_like_job.php'));

                    break;

                // Like Jobs
                case 'like_jobs' :

                    return include_once(app_path('controllers/Api/like_jobs.php'));

                    break;

                // Get Like Job
                case 'get_like_job':

                    return include_once(app_path('controllers/Api/get_like_job.php'));

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
