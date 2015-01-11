<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @since  		25th Nov, 2014
 * @license   	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	0.1
 */

class AdminController extends BaseController
{
    /**
     * Admin index
     * @return Response
     */
    public function getIndex()
    {
		$totalUser		= User::get()->count();
		$maleUser		= User::where('sex', 'M')->get()->count();
		$femaleUser		= User::where('sex', 'F')->get()->count();
		$unreadSupport	= Support::where('status', false)->get()->count();
    	$analyticsUser  = AnalyticsUser::select(
							'all_user',
							'daily_active_user',
							'weekly_active_user',
							'monthly_active_user',
							'all_male_user',
							'daily_active_male_user',
							'weekly_active_male_user',
							'monthly_active_male_user',
							'all_female_user',
							'daily_active_female_user',
							'weekly_active_female_user',
							'monthly_active_female_user',
							'complete_profile_user_ratio',
							'from_web',
							'from_android',
							'from_ios',
							'created_at'
						)->orderBy('created_at')->take(31)->get()->toArray(); // Retrive analytics data

		/*
		|--------------------------------------------------------------------------
		| User Basic Analytics Section
		|--------------------------------------------------------------------------
		|
		*/

		$allUser = array(); // Create all user array
		foreach($analyticsUser as $key){ // Structure array elements
			$allUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['all_user']);
		}

		$fromWeb = array(); // Create all from web user array
		foreach($analyticsUser as $key){ // Structure array elements
			$fromWeb[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['from_web']);
		}

		$fromAndroid = array(); // Create all from Android user array
		foreach($analyticsUser as $key){ // Structure array elements
			$fromAndroid[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['from_android']);
		}

		$fromiOS = array(); // Create all from iOS user array
		foreach($analyticsUser as $key){ // Structure array elements
			$fromiOS[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['from_ios']);
		}

		$allMaleUser = array(); // Create all male user array
		foreach($analyticsUser as $key){ // Structure array elements
			$allMaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['all_male_user']);
		}

		$allFemaleUser = array(); // Create all female user array
		foreach($analyticsUser as $key){ // Structure array elements
			$allFemaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['all_female_user']);
		}

		// Build Json data (remove double quotes from Json return data)
		$userBasicAnalytics = '{
			"总用户":'.preg_replace('/["]/', '' ,json_encode($allUser)).
			', "男用户":'.preg_replace('/["]/', '' ,json_encode($allMaleUser)).
			', "女用户":'.preg_replace('/["]/', '' ,json_encode($allFemaleUser)).
			', "Web用户":'.preg_replace('/["]/', '' ,json_encode($fromWeb)).
			', "Android用户":'.preg_replace('/["]/', '' ,json_encode($fromAndroid)).
			', "iOS用户":'.preg_replace('/["]/', '' ,json_encode($fromiOS)).
			'}';
        return View::make('admin.index')->with(compact('unreadSupport', 'totalUser', 'maleUser', 'femaleUser', 'userBasicAnalytics'));
    }


}
