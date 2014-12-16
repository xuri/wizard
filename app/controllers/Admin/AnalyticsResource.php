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
	 * Resource list view
	 * GET         /resource
	 * @return Response
	 */
	public function form()
	{

		/*
		|--------------------------------------------------------------------------
		| User Analytics Section
		|--------------------------------------------------------------------------
		|
		*/

		// All register users
		$allUser					= User::get()->count();

		// Daily active user
		$dailyActiveUser			= User::where('signin_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

		// Weekly active user
		$weeklyActiveUser			= User::where('signin_at', '>', date('Y-m-d H:m:s', strtotime("-8 days")))->count();

		// Monthly active user
		$monthlyActiveUser			= User::where('signin_at', '>', date('Y-m-d H:m:s', strtotime("-32 days")))->count();

		// Male

		// All register male users
		$allMaleUser				= User::where('sex', 'M')->get()->count();

		// Daily active male user
		$dailyActiveMaleUser		= User::where('sex', 'M')->where('signin_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

		// Weekly active male user
		$weeklyActiveMaleUser		= User::where('sex', 'M')->where('signin_at', '>', date('Y-m-d H:m:s', strtotime("-8 days")))->count();

		// Monthly active male user
		$monthlyActiveMaleUser		= User::where('sex', 'M')->where('signin_at', '>', date('Y-m-d H:m:s', strtotime("-32 days")))->count();

		// Female

		// All register female users
		$allFemailUser				= User::where('sex', 'S')->get()->count();

		// Daily active female user
		$dailyActiveFemaleUser		= User::where('sex', 'S')->where('signin_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

		// Weekly active female user
		$weeklyActiveFemaleUser		= User::where('sex', 'S')->where('signin_at', '>', date('Y-m-d H:m:s', strtotime("-8 days")))->count();

		// Monthly active female user
		$monthlyActiveFemaleUser	= User::where('sex', 'S')->where('signin_at', '>', date('Y-m-d H:m:s', strtotime("-32 days")))->count();

		// Complete profile user
		$completeProfileUserRatio	= number_format((User::whereNotNull('portrait')->count() / $allUser) * 100, 2);

		// User register with client

		// Register from website
		$fromWeb					= User::where('from', 0)->count();

		// Register from Android App
		$fromAndroid				= User::where('from', 1)->count();

		// Register from iOS App
		$fromiOS					= User::where('from', 2)->count();

		/*
		|--------------------------------------------------------------------------
		| Form Analytics Stction
		|--------------------------------------------------------------------------
		|
		*/

		// Total posts

		// All posts
		$allPost					= ForumPost::get()->count();

		// Category 1 posts
		$cat1Post					= ForumPost::where('category_id', 1)->count();

		// Category 2 posts
		$cat2Post					= ForumPost::where('category_id', 2)->count();

		// Category 3 posts
		$cat2Post					= ForumPost::where('category_id', 3)->count();

		// Daily posts analytics

		// Daily posts
		$dailyPost					= ForumPost::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

		// Category 1 daily posts
		$cat1Post					= ForumPost::where('category_id', 1)->where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

		// Category 2 daily posts
		$cat2Post					= ForumPost::where('category_id', 2)->where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

		// Category 3 daily posts
		$cat3Post					= ForumPost::where('category_id', 3)->where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

		// Daily male posts
		$dailyMalePostArray			= ForumPost::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->select('user_id')->get()->toArray();
		$dailyMalePost				= 0;
		foreach ($dailyMalePostArray as $key => $value) {
			$user = User::where('id', $dailyMalePostArray[$key]['user_id'])->first();
			if($user->sex == 'M')
			{
				$dailyMalePost = $dailyMalePost + 1;
			}
		}

		// Daile female posts
		$dailyFemalePostArray	= $dailyPost - $dailyMalePost;

		/*
		|--------------------------------------------------------------------------
		| User Like Analytics Section
		|--------------------------------------------------------------------------
		|
		*/

		// Daily likes
		$dailyLike			= Like::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->count();

		// Weekly Like
		$weeklyLike			= Like::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-8 days")))->count();

		// Monthly Like
		$monthlyLike		= Like::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-32 days")))->count();

		// All male like female
		$allMaleLikeArray	= Like::select('sender_id')->get()->toArray();
		$allMaleLike		= 0;
		foreach ($allMaleLikeArray as $key => $value) {
			$user = User::where('id', $allMaleLikeArray[$key]['sender_id'])->first();
			if($user->sex == 'M')
			{
				$allMaleLike = $allMaleLike + 1;
			}
		}

		// All female like female
		$allFemaleLikeArray	= Like::select('sender_id')->get()->toArray();
		$allFemaleLike		= 0;
		foreach ($allFemaleLikeArray as $key => $value) {
			$user = User::where('id', $allFemaleLikeArray[$key]['sender_id'])->first();
			if($user->sex == 'F')
			{
				$allFemaleLike = $allFemaleLike + 1;
			}
		}

		// Daily male like female
		$dailyMaleLikeArray	= Like::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-1 days")))->select('sender_id')->get()->toArray();
		$dailyMaleLike = 0;
		foreach ($dailyMaleLikeArray as $key => $value) {
			$user = User::where('id', $dailyMaleLikeArray[$key]['sender_id'])->first();
			if($user->sex == 'M')
			{
				$dailyMaleLike = $dailyMaleLike + 1;
			}
		}

		// Weekly male like female
		$weeklyMaleLikeArray	= Like::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-8 days")))->select('sender_id')->get()->toArray();
		$weeklyMaleLike = 0;
		foreach ($weeklyMaleLikeArray as $key => $value) {
			$user = User::where('id', $weeklyMaleLikeArray[$key]['sender_id'])->first();
			if($user->sex == 'M')
			{
				$weeklyMaleLike = $weeklyMaleLike + 1;
			}
		}

		// Monthly male like female
		$monthlyMaleLikeArray	= Like::where('created_at', '>', date('Y-m-d H:m:s', strtotime("-32 days")))->select('sender_id')->get()->toArray();
		$monthlyMaleLike = 0;
		foreach ($monthlyMaleLikeArray as $key => $value) {
			$user = User::where('id', $monthlyMaleLikeArray[$key]['sender_id'])->first();
			if($user->sex == 'M')
			{
				$monthlyMaleLike = $monthlyMaleLike + 1;
			}
		}

		// Daily female like male
		$dailyFemaleLike	= $dailyLike - $dailyMaleLike;

		// Weekly female like male
		$weeklyFemaleLike	= $weeklyLike - $weeklyMaleLike;

		// Monthly female like male
		$monthlyFemaleLike	= $monthlyLike - $monthlyMaleLike;

		// Male accept ratio (Female like male)
		$allAccept			= Like::where('status', 1)->count();
		$allMaleAcceptArray	= Like::where('status', 1)->select('receiver_id')->get()->toArray();
		$allMaleAccept		= 0;
		foreach ($allMaleAcceptArray as $key => $value) {
			$user = User::where('id', $allMaleAcceptArray[$key]['receiver_id'])->first();
			if($user->sex == 'M')
			{
				$allMaleAccept = $allMaleAccept + 1;
			}
		}
		$allMaleAcceptRatio		= number_format(($allMaleAccept / $allAccept) * 100, 2);

		// Female accept ratio
		$allFemaleAccept		= $allAccept - $allMaleAccept;
		$allFemaleAcceptRatio	= number_format(($allFemaleAccept / $allAccept) * 100, 2);


		// Like duration
		$likeDurationArray = Like::where('status', 1)->select('created_at', 'updated_at')->get()->toArray(); // Retrieve all accept like as an array
		foreach($likeDurationArray as $key => $field){
			$likeDurationArray[$key]['duration'] = diffBetweenTwoDays(date("Y-m-d",strtotime($likeDurationArray[$key]['updated_at'])), date("Y-m-d",strtotime($likeDurationArray[$key]['created_at']))); // Calculate duration days
			$sumLikeDurationArray[] = $likeDurationArray[$key]['duration']; // Summary like duration to a new array
		}
		$averageLikeDuration = number_format(array_sum($sumLikeDurationArray) / count($sumLikeDurationArray), 2);
		return View::make($this->resourceView.'.form')->with(compact('averageLikeDuration'));
	}
}