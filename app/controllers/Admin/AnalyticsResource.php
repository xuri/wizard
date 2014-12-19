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
 * @since 		25th Nov, 2014
 * @license 	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	0.1
 */

class Admin_AnalyticsResource extends BaseResource
{
	/**
	 * Resource view directory
	 * @var string
	 */
	protected $resourceView = 'admin.analytics';

	/**
	 * Model name of the resource, after initialization to a model instance
	 * @var string|Illuminate\Database\Eloquent\Model
	 */
	protected $model = 'User';

	/**
	 * Resource identification
	 * @var string
	 */
	protected $resource = 'users';

	/**
	 * Resource database tables
	 * @var string
	 */
	protected $resourceTable = 'users';

	/**
	 * Resource name (Chinese)
	 * @var string
	 */
	protected $resourceName = '统计';

	/**
	 * Resource Analytics user form view
	 * GET         /resource
	 * @return Response
	 */
	public function userForm()
	{
		$analyticsUsers = AnalyticsUser::orderby('id', 'desc')->paginate(10);
		return View::make($this->resourceView.'.user-form')->with(compact('analyticsUsers'));
	}

	/**
	 * Resource Analytics forum form view
	 * GET         /resource
	 * @return Response
	 */
	public function forumForm()
	{
		$analyticsForums = AnalyticsForum::orderby('id', 'desc')->paginate(10);
		return View::make($this->resourceView.'.forum-form')->with(compact('analyticsForums'));
	}

	/**
	 * Resource Analytics likes form view
	 * GET         /resource
	 * @return Response
	 */
	public function likeForm()
	{
		$analyticsLikes = AnalyticsLike::orderby('id', 'desc')->paginate(10);
		return View::make($this->resourceView.'.like-form')->with(compact('analyticsLikes'));
	}

	/**
	 * Resource Analytics user charts view
	 * GET         /resource
	 * @return Response
	 */
	public function userCharts()
	{

		$analyticsUser = AnalyticsUser::select(
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

		/*
		|--------------------------------------------------------------------------
		| User Daily Active Analytics Section
		|--------------------------------------------------------------------------
		|
		*/

		$dailyActiveUser = array(); // Create daily active user array
		foreach($analyticsUser as $key){ // Structure array elements
			$dailyActiveUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['daily_active_user']);
		}

		$dailyActiveMaleUser = array(); // Create daily active male user array
		foreach($analyticsUser as $key){ // Structure array elements
			$dailyActiveMaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['daily_active_male_user']);
		}

		$dailyActiveFemaleUser = array(); // Create daily active female user array
		foreach($analyticsUser as $key){ // Structure array elements
			$dailyActiveFemaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['daily_active_female_user']);
		}

		// Build Json data (remove double quotes from Json return data)
		$dailyActiveUserAnalytics = '{
			"日活用户":'.preg_replace('/["]/', '' ,json_encode($dailyActiveUser)).
			', "日活男用户":'.preg_replace('/["]/', '' ,json_encode($dailyActiveMaleUser)).
			', "日活女用户":'.preg_replace('/["]/', '' ,json_encode($dailyActiveFemaleUser)).
			'}';

		/*
		|--------------------------------------------------------------------------
		| User Weekly Active Analytics Section
		|--------------------------------------------------------------------------
		|
		*/

		$weeklyActiveUser = array(); // Create weekly active user array
		foreach($analyticsUser as $key){ // Structure array elements
			$weeklyActiveUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['weekly_active_user']);
		}

		$weeklyActiveMaleUser = array(); // Create weekly active male user array
		foreach($analyticsUser as $key){ // Structure array elements
			$weeklyActiveMaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['weekly_active_male_user']);
		}

		$weeklyActiveFemaleUser = array(); // Create weekly active female user array
		foreach($analyticsUser as $key){ // Structure array elements
			$weeklyActiveFemaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['weekly_active_female_user']);
		}

		// Build Json data (remove double quotes from Json return data)
		$weeklyActiveUserAnalytics = '{
			"周活用户":'.preg_replace('/["]/', '' ,json_encode($weeklyActiveUser)).
			', "周活男用户":'.preg_replace('/["]/', '' ,json_encode($weeklyActiveMaleUser)).
			', "周活女用户":'.preg_replace('/["]/', '' ,json_encode($weeklyActiveFemaleUser)).
			'}';

		/*
		|--------------------------------------------------------------------------
		| User Monthly Active Analytics Section
		|--------------------------------------------------------------------------
		|
		*/

		$monthlyActiveUser = array(); // Create monthly active user array
		foreach($analyticsUser as $key){ // Structure array elements
			$monthlyActiveUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['monthly_active_user']);
		}

		$monthlyActiveMaleUser = array(); // Create monthly active male user array
		foreach($analyticsUser as $key){ // Structure array elements
			$monthlyActiveMaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['monthly_active_male_user']);
		}

		$monthlyActiveFemaleUser = array(); // Create monthly active female user array
		foreach($analyticsUser as $key){ // Structure array elements
			$monthlyActiveFemaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['monthly_active_female_user']);
		}

		// Build Json data (remove double quotes from Json return data)
		$monthlyActiveUserAnalytics = '{
			"月活用户":'.preg_replace('/["]/', '' ,json_encode($monthlyActiveUser)).
			', "月活男用户":'.preg_replace('/["]/', '' ,json_encode($monthlyActiveMaleUser)).
			', "月活女用户":'.preg_replace('/["]/', '' ,json_encode($monthlyActiveFemaleUser)).
			'}';

		/*
		|--------------------------------------------------------------------------
		| User Profile Complete Ratio Analytics Section
		|--------------------------------------------------------------------------
		|
		*/

		$completeProfileUserRatio = array();
		foreach($analyticsUser as $key){ // Structure array elements
			$completeProfileUserRatio[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['complete_profile_user_ratio']);
		}

		// Build Json data (remove double quotes from Json return data)
		$completeProfileUserRatioAnalytics = '{"完整用户资料比例":'.preg_replace('/["]/', '' ,json_encode($completeProfileUserRatio)).'}';

		return View::make($this->resourceView.'.user-charts')->with(compact('userBasicAnalytics', 'dailyActiveUserAnalytics', 'weeklyActiveUserAnalytics', 'monthlyActiveUserAnalytics', 'completeProfileUserRatioAnalytics'));
	}
}