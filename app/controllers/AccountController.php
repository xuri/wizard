<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Class for sign-in user account management.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @license 	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	Release: 0.1 2014-12-25
 */

class AccountController extends BaseController
{
	/**
	 * Show sign-in profile index view.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$profile           = Profile::where('user_id', Auth::user()->id)->first(); // Get user's profile
		$constellationInfo = getConstellation($profile->constellation); // Get user's constellation
		$constellationIcon = $constellationInfo['icon'];
		$constellationName = $constellationInfo['name'];
		$tag_str           = explode(',', substr($profile->tag_str, 1));
		return View::make('account.index')->with(compact('profile', 'constellationIcon', 'constellationName', 'tag_str'));
	}

	/**
	 * Show edit sign-in user profile view
	 *
	 * @return Reponse
	 */
	public function getComplete()
	{
		// Retrieve user profile
		$profile			= Profile::where('user_id', Auth::user()->id)->first();

		// Get all province
		$provinces			= Province::get();

		// Get user's constellation
		$constellationInfo	= getConstellation($profile->constellation);
		return View::make('account.complete')->with(compact('profile', 'constellationInfo', 'provinces'));
	}

	/**
	 * Ajax get university list for sign-in user choose school
	 *
	 * @return json $school
	 */
	public function postUniversity()
	{
		$province    = Province::where('province', Input::get('province'))->first();

		$universites = University::where('province_id', $province->id)->get();
		$school = array();
		foreach ($universites as $university) {
			$elements = explode(':', $university->university);
			$school[] = $elements[0];
		}
		return Response::json(
			$school
		);
	}

	/**
	 * Ajax daly post renew to get point
	 *
	 * @return json success true or false
	 */
	public function postRenew()
	{
		$today	= Carbon::today();
		$user	= Profile::where('user_id', Auth::user()->id)->first();
		$points	= User::where('id', Auth::user()->id)->first();

		// First renew
		if($user->renew_at == '0000-00-00 00:00:00'){
			$user->renew_at	= Carbon::now();
			$user->renew	= $user->renew + 1;
			$points->points	= $points->points + 5;
			$user->save();
			$points->save();
			return Response::json(
				array(
					'success' => true
				)
			);
		} else if ($today >= $user->renew_at){

			// Sign-in user haven't renew today
			$user->renew_at	= Carbon::now();
			$user->renew	= $user->renew + 1;
			$points->points	= $points->points + 2;
			$user->save();
			$points->save();
			return Response::json(
				array(
					'success' => true
				)
			);
		} else {

			// Sign-in user have renew today
			return Response::json(
				array(
					'success' => false
				)
			);
		}
	}

	/**
	 * Sign-in user update profile information
	 *
	 * @return Response
	 */
	public function postComplete()
	{
		// Get all form data

		$info = array(
			'nickname'      => Input::get('nickname'),
			'constellation' => Input::get('constellation'),
			'portrait'      => Input::get('portrait'),
			'tag_str'       => Input::get('tag_str'),
			'sex'           => Input::get('sex'),
			'born_year'     => Input::get('born_year'),
			'grade'         => Input::get('grade'),
			'hobbies'       => Input::get('hobbies'),
			'self_intro'    => Input::get('self_intro'),
			'bio'           => Input::get('bio'),
			'question'      => Input::get('question'),
			'school'        => Input::get('school'),
		);

		//Create validation rules

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
			'nickname.required'      => '请输入昵称',
			'nickname.between'       => '昵称长度请保持在:min到:max字之间',
			'constellation.required' => '请选择星座',
			'tag_str.required'       => '给自己贴个标签吧',
			'born_year.required'     => '请选择出生年',
			'grade.required'         => '请选择入学年',
			'hobbies.required'       => '填写你的爱好',
			'self_intro.required'    => '请填写个人简介',
			'bio.required'           => '请填写你的真爱寄语',
			'question.required'      => '记得填写爱情考验哦',
			'school.required'        => '请选择所在学校',

		);

		// Begin verification

		$validator = Validator::make($info, $rules, $messages);
		if ($validator->passes()) {

			// Verification success
			// Update account
			$user                   = Auth::user();
			$oldPortrait			= $user->portrait;
			$user->nickname         = Input::get('nickname');

			// Protrait section
			$portrait               = Input::get('portrait');
			if($portrait != NULL) // User update avatar
			{
				$portrait           = str_replace('data:image/png;base64,', '', $portrait);
				$portrait           = str_replace(' ', '+', $portrait);
				$portraitData       = base64_decode($portrait); // Decode string
				$portraitPath		= public_path('portrait/');
				$portraitFile       = uniqid() . '.png'; // Portrait file name
				$successPortrait    = file_put_contents($portraitPath.$portraitFile, $portraitData); // Store file
				$user->portrait     = $portraitFile; // Save file name to database
			}
			if(Auth::user()->born_year == NULL)
			{
				$user->born_year    = Input::get('born_year');
			}
			$user->bio              = Input::get('bio');
			$user->school           = Input::get('school');

			// Update profile information
			$profile                = Profile::where('user_id', Auth::user()->id)->first();
			$profile->tag_str       = Input::get('tag_str');
			$profile->grade         = Input::get('grade');
			$profile->hobbies       = Input::get('hobbies');
			$profile->constellation = Input::get('constellation');
			$profile->self_intro    = Input::get('self_intro');
			$profile->question      = Input::get('question');

			if ($user->save() && $profile->save()) {
				// Update success
				if($portrait != NULL) // User update avatar
				{
					// Determine user portrait type
					$asset = strpos($oldPortrait, 'android');

					// Should to use !== false
					if($asset !== false){
						// No nothing
					} else {
						// User set portrait from web delete old poritait
						File::delete($portraitPath . $oldPortrait);
					}

				}
				return Redirect::route('account')
					->with('success', '<strong>基本资料更新成功。</strong>');

			} else {
				// Update fail
				return Redirect::back()
					->withInput()
					->with('error', '<strong>基本资料更新失败。</strong>');
			}
		} else {
			// Verification fail, redirect back
			return Redirect::back()->withInput()->withErrors($validator);
		}

	}

	/**
	 * Like other user
	 * @return Response
	 */
	public function getSent()
	{
		$datas		= Like::where('sender_id', Auth::user()->id)->get();

		// Get user's profile
		$profile	= Profile::where('user_id', Auth::user()->id)->first();
		return View::make('account.sent.index')->with(compact('datas', 'profile'));
	}

	/**
	 * Other user like me
	 * @return Response
	 */
	public function getInbox()
	{
		$datas		= Like::where('receiver_id', Auth::user()->id)->get();

		// Get user's profile
		$profile	= Profile::where('user_id', Auth::user()->id)->first();
		return View::make('account.inbox.index')->with(compact('datas', 'profile'));
	}

	/**
	 * getPosts all posts in forum
	 * @return Response View
	 */
	public function getPosts()
	{
		$posts		= ForumPost::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);

		// Get user's profile
		$profile	= Profile::where('user_id', Auth::user()->id)->first();

		if (Request::ajax()) {
			return Response::json(View::make('account.posts.load-ajax')->with(compact('posts'))->render());
		}
		return View::make('account.posts.index')->with(compact('posts', 'profile'));
	}

	/**
	 * getNotifications User notifications list
	 * @return Response View
	 */
	public function getNotifications()
	{
		// Get user's profile
		$profile	= Profile::where('user_id', Auth::user()->id)->first();

		$items_per_page = Input::get('per_pg', 5);

		$friendNotifications		= Notification::where('receiver_id', Auth::user()->id)->whereIn('category', array(1, 2, 3, 4, 5, 10))->paginate($items_per_page);
		$forumNotifications			= Notification::where('receiver_id', Auth::user()->id)->whereIn('category', array(6, 7))->paginate($items_per_page);
		$systemNotifications		= Notification::where('receiver_id', Auth::user()->id)->whereIn('category', array(8, 9))->paginate($items_per_page);
		$friendNotificationsCount	= Notification::where('receiver_id', Auth::user()->id)->whereIn('category', array(1, 2, 3, 4, 5, 10))->where('status', 0)->count();
		$forumNotificationsCount	= Notification::where('receiver_id', Auth::user()->id)->whereIn('category', array(6, 7))->where('status', 0)->count();
		$systemNotificationsCount	= Notification::where('receiver_id', Auth::user()->id)->whereIn('category', array(8, 9))->where('status', 0)->count();

		return View::make('account.notifications.index')->with(compact('friendNotifications', 'forumNotifications', 'systemNotifications', 'friendNotificationsCount', 'forumNotificationsCount', 'systemNotificationsCount', 'profile'));
	}

	/**
	 * getNotificationsType Multi Pagination in a Single Page
	 * @param  string $type Category kind
	 * @return Response Json
	 */
	public function getNotificationsType($type)
	{
		$items_per_page = Input::get('per_pg', 5);

		if ($type == 'first') {
			$friendNotifications		= Notification::where('receiver_id', Auth::user()->id)->whereIn('category', array(1, 2, 3, 4, 5, 10))->orderBy('created_at' , 'desc')->where('status', 0)->paginate($items_per_page);
			$view = View::make('account.notifications.first-ajax')->with(compact('friendNotifications'));
			return $view;
			exit;
		} else if ($type == 'second'){
			$forumNotifications			= Notification::where('receiver_id', Auth::user()->id)->whereIn('category', array(6, 7))->orderBy('created_at' , 'desc')->where('status', 0)->paginate($items_per_page);
			$view = View::make('account.notifications.second-ajax')->with(compact('forumNotifications'));
			return $view;
			exit;
		} else {
			$systemNotifications		= Notification::where('receiver_id', Auth::user()->id)->whereIn('category', array(8, 9))->orderBy('created_at' , 'desc')->where('status', 0)->paginate($items_per_page);
			$view = View::make('account.notifications.third-ajax')->with(compact('systemNotifications'));
			return $view;
			exit;
		}
	}

	/**
	 * Ajax delete forum post
	 * @return Response Json
	 */
	public function postDeleteForumPost()
	{
		// Get post ID in forum for delete
		$postId		= Input::get('post_id');

		// Retrieve post
		$forumPost	= ForumPost::where('id', $postId)->first();

		// Using expression get all picture attachments (Only with pictures stored on this server.)
		preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $forumPost->content, $match );

		// Construct picture attachments list
		$srcArray 	= array_pop($match);

		if(!empty( $srcArray )) // This post have picture attachments
		{
			// Foreach picture attachments list array
			foreach($srcArray as $key => $field){
				$srcArray[$key]	= str_replace(route('home'), '', $srcArray[$key]); // Convert to correct real storage path
				File::delete(public_path($srcArray[$key])); // Destory upload picture attachments in this post
			}
			// Delete post in forum
			if($forumPost->delete()) {
				return Response::json(
					array(
						'success'		=> true,
						'success_info'	=> '帖子删除成功！'
					)
				);
			} else {
				return Response::json(
					array(
						'fail'		=> true,
						'fail_info'	=> '帖子删除失败！'
					)
				);
			}
		} else {

			// Delete post in forum
			if($forumPost->delete()) {
				return Response::json(
					array(
						'success'		=> true,
						'success_info'	=> '帖子删除成功！'
					)
				);
			} else {
				return Response::json(
					array(
						'fail'		=> true,
						'fail_info'	=> '帖子删除失败！'
					)
				);
			}

		}
	}

}