<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Class include main Android client API
 *
 * Code Explanation
 *
 * Status Code Explanation
 *
 * - from = 0 (default) 	Signup from Website
 * - from = 1 				Signup from Android
 * - from = 3 				Signup from iOS Client
 * - from = 4 				Add user by administrator
 *
 * Forum Code Explanation
 *
 * - status = 0 			Response fail
 * - status = 1 			Forum is open and response success
 * - status = 2 			Forum is closed and response success
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @license   	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	Release: 0.1 2014-12-25
 */

class AndroidController extends BaseController
{

	/**
	 * View: Debug
	 * @return Response Application debug view.
	 */
	public function getDebug()
	{
		return View::make('app.index')->with(compact('test'));
	}

	/**
	 * Main Android API
	 *
	 * @api
	 *
	 * @return Json All response are in JSON format.
	 */
	public function postAndroid()
	{

		$token  = Input::get('token');
		$action = Input::get('action');

		// Define token
		if($token == 'jciy9ldJ')
		{
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
						'email'		=> Input::get('phone'),
						'password'	=> md5(Input::get('password')
					));
					$phone_credentials = array(
						'phone'		=> Input::get('phone'),
						'password'	=> md5(Input::get('password')
					));
					if (Auth::attempt($credentials) || Auth::attempt($phone_credentials)) {

						// Retrieve user
						$user = User::where('phone', Input::get('phone'))->orWhere('email', Input::get('phone'))->first();

						// Signin success, redirect to the previous page that was blocked
						return Response::json(
							array(
								'status'	=> 1,
								'id'		=> $user->id,
								'password'	=> $user->password,
								'sex' 		=> $user->sex,
								'portrait'	=> route('home') . '/' . 'portrait/' . $user->portrait
							)
						);
					} else {
						return Response::json(
							array(
								'status' 		=> 0
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
						'password'            => 'required|alpha_dash|between:6,16'
					);
					// Custom validation message
					$messages = array(
						'phone.required'      => '请输入手机号码。',
						'phone.digits'        => '请输入正确的手机号码。',
						'phone.unique'        => '此手机号码已被使用。',
						'password.required'   => '请输入密码。',
						'password.alpha_dash' => '密码格式不正确。',
						'password.between'    => '密码长度请保持在:min到:max位之间。'
					);
					// Begin verification
					$validator   = Validator::make($data, $rules, $messages);
					$phone       = Input::get('phone');
					if ($validator->passes()) {
						// Verification success, add user
						$user				= new User;
						$user->phone		= $phone;

						// Signup from 1 - Android, 2 - iOS
						$user->from			= Input::get('from');
						$user->activated_at	= date('Y-m-d G:i:s');

						$user->sex			= e(Input::get('sex'));
						$user->password		= md5(Input::get('password'));
						if ($user->save()) {
							$profile			= new Profile;
							$profile->user_id	= $user->id;
							$profile->save();

							// Add user success and chat Register
							$easemob			= getEasemob();

							// newRequest or newJsonRequest returns a Request object
							$regChat			= cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users', ['username' => $user->id, 'password' => $user->password])
								->setHeader('content-type', 'application/json')
								->setHeader('Accept', 'json')
								->setHeader('Authorization', 'Bearer '.$easemob->token)
								->setOptions([CURLOPT_VERBOSE => true])
								->send();

							// Create floder to store chat record
							File::makeDirectory(app_path('chatrecord/user_' . $user->id, 0777, true));

							// Redirect to a registration page, prompts user to activate
							// Signin success, redirect to the previous page that was blocked
							return Response::json(
								array(
									'status'	=> 1,
									'id'		=> $user->id,
									'password'	=> $user->password
								)
							);
						} else {
							// Signin success, redirect to the previous page that was blocked
							return Response::json(
								array(
									'status' 		=> 0
								)
							);
						}
					} else {
						// Add user fail
						return Response::json(
							array(
								'status' 		=> 0
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
						'nickname'		=> 'required|between:1,30',
						'constellation'	=> 'required',
						'tag_str'		=> 'required',
						'born_year'		=> 'required',
						'grade'			=> 'required',
						'hobbies'		=> 'required',
						'self_intro'	=> 'required',
						'bio'			=> 'required',
						'question'		=> 'required',
						'school'		=> 'required',
					);

					// Custom validation message
					$messages = array(
						'nickname.required'			=> '请输入昵称',
						'nickname.between'			=> '昵称长度请保持在:min到:max字之间',
						'constellation.required'	=> '请选择星座',
						'tag_str.required'			=> '给自己贴个标签吧',
						'born_year.required'		=> '请选择出生年',
						'grade.required'			=> '请选择入学年',
						'hobbies.required'			=> '填写你的爱好',
						'self_intro.required'		=> '请填写个人简介',
						'bio.required'				=> '请填写你的真爱寄语',
						'question.required'			=> '记得填写爱情考验哦',
						'school.required'			=> '请选择所在学校',
					);

					// Begin verification
					$validator = Validator::make($info, $rules, $messages);
					if ($validator->passes()) {

						// Verification success
						// Update account
						$user                   = User::where('id', Input::get('id'))->first();
						$oldPortrait			= $user->portrait;
						$user->nickname         = Input::get('nickname');

						// Protrait section
						$portrait               = Input::get('portrait');
						if($portrait == null)
						{
							$user->portrait 	= $oldPortrait;  // User not update avatar
						} else{
							// User update avatar
							$portraitPath		= public_path('portrait/');
							$user->portrait     = 'android/' . $portrait; // Save file name to database
						}
						if(is_null($user->sex))
						{
							$user->sex          = Input::get('sex');
						}
						if(is_null($user->born_year))
						{
							$user->born_year    = Input::get('born_year');
						}
						$user->bio              = Input::get('bio');
						$user->school           = Input::get('school');

						// Update profile information
						$profile                = Profile::where('user_id', $user->id)->first();
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
							return Response::json(
								array(
									'status' 	=> 1
								)
							);

						} else {
							// Update fail
							return Response::json(
								array(
									'status' 	=> 0
								)
							);
						}
					} else {
						// Verification fail, redirect back
						return Response::json(
							array(
								'status' 		=> 0,
								'info'			=> $validator
							)
						);
					}
				break;

				// Members

				case 'members_index' :

					// Post last user id from App client
					$last_id			= Input::get('lastid');

					// Post count per query from App client
					$per_page			= Input::get('perpage');

					// Post sex filter from App client
					$sex_filter			= Input::get('sex');

					// Post university filter from App client
					$university_filter	= Input::get('university');

					// Grade filter
					$grade				= Input::get('grade');

					if($last_id){
						// User last signin at time
						$last_updated_at	= User::find($last_id)->updated_at;

						//  App client have post last user id, retrieve and skip profile not completed user
						$query				= User::whereNotNull('portrait')->whereNotNull('nickname');

						// Sex filter
						if($sex_filter){
							isset($sex_filter) AND $query->where('sex', $sex_filter);
						}

						// University filter
						if($university_filter){
							if($university_filter == '其他') {
								$universities_list = University::where('status', 2)->select('university')->get()->toArray();
								isset($university_filter) AND $query->whereNotIn('school', $universities_list);
							} else {
								isset($university_filter) AND $query->where('school', $university_filter);
							}
						}

						// Grade filter
						if($grade) {
							isset($grade) AND $query->whereHas('hasOneProfile', function($profileQuery){
								$profileQuery->where('grade', '=', Input::get('grade'));
							});
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
						foreach($users as $key => $field){

							// Retrieve user profile
							$profile	= Profile::where('user_id', $users[$key]['id'])->first();

							// Determine user renew status
							if($profile->crenew >= 30){
								$users[$key]['crenew'] = 1;
							} else {
								$users[$key]['crenew'] = 0;
							}

							// Convert to real storage path
							$users[$key]['portrait']	= route('home') . '/' . 'portrait/' . $users[$key]['portrait'];

							// Retrieve sex with UTF8 encode
							$users[$key]['sex']			= e($users[$key]['sex']);

							// Retrieve nickname with UTF8 encode
							$users[$key]['nickname']	= e($users[$key]['nickname']);

							// Retrieve school with UTF8 encode
							$users[$key]['school']		= e($users[$key]['school']);
						}

						// Encode likes array to json format
						$users = json_encode($users);

						// If get query success
						if($users)
						{
							// Build Json format
							return '{ "status" : "1", "data" : ' . $users . '}';
						} else {
							// Get query fail
							return Response::json(
								array(
									'status' 		=> 0
								)
							);
						}
					} else {

						//  First get data from App client, retrieve and skip profile not completed user
						$query      = User::whereNotNull('portrait')->whereNotNull('nickname');

						// Sex filter
						if($sex_filter){
							isset($sex_filter) AND $query->where('sex', $sex_filter);
						}

						// University filter
						if($university_filter){
							if($university_filter == '其他') {
								$universities_list = University::where('status', 2)->select('university')->get()->toArray();
								isset($university_filter) AND $query->whereNotIn('school', $universities_list);
							} else {
								isset($university_filter) AND $query->where('school', $university_filter);
							}
						}

						// Grade filter
						if($grade) {
							isset($grade) AND $query->whereHas('hasOneProfile', function($profileQuery){
								$profileQuery->where('grade', '=', Input::get('grade'));
							});
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
						foreach($users as $key => $field){

							// Retrieve user profile
							$profile	= Profile::where('user_id', $users[$key]['id'])->first();

							// Determine user renew status
							if($profile->crenew >= 30){
								$users[$key]['crenew'] = 1;
							} else {
								$users[$key]['crenew'] = 0;
							}

							// Convert to real storage path
							$users[$key]['portrait']	= route('home') . '/' . 'portrait/' . $users[$key]['portrait'];

							// Retrieve sex with UTF8 encode
							$users[$key]['sex']			= e($users[$key]['sex']);

							// Retrieve nickname with UTF8 encode
							$users[$key]['nickname']	= e($users[$key]['nickname']);

							// Retrieve school with UTF8 encode
							$users[$key]['school']		= e($users[$key]['school']);
						}

						// Encode likes array to json format
						$users = json_encode($users);

						if($users)
						{
							return '{ "status" : "1", "data" : ' . $users . '}';
						} else {
							return Response::json(
								array(
									'status' 		=> 0
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
					if ($info)
					{
						// Sender user ID
						$sender_id	= Input::get('senderid');
						$user_id	= Input::get('userid');
						$data		= User::where('id', $user_id)->first();
						$profile	= Profile::where('user_id', $user_id)->first();
						$like		= Like::where('sender_id', $sender_id)->where('receiver_id', $user_id)->first();
						$like_me	= Like::where('sender_id', $user_id)->where('receiver_id', $sender_id)->first();
						if($like) {
							$likeCount = $like->count;
						} else {
							$likeCount = 0;
						}

						// Determine user renew status
						if($profile->crenew >= 30){
							$crenew = 1;
						} else {
							$crenew = 0;
						}

						if(is_null($like_me)) {

							// This user never liked you
							$user_like_me	= 5;
							$answer			= null;

						} else {

							// Determine users relationship, see code explanation in MembersController
							switch ($like_me->status) {
								case '0':
									// status = 0 User send like, pending accept
									$user_like_me	= 0;
								break;

								case '1':

									// status = 1 Receiver accept like, add friend relationship in chat system and start chat
									$user_like_me	= 1;
								break;

								default:

									// status = 1 Receiver accept like, add friend relationship in chat system and start chat
									$user_like_me	= 1;
								break;
							}

							// User liked answer
							$answer			= $like_me->answer;
						}

						// Get user's constellation
						$constellationInfo = getConstellation($profile->constellation);

						// Get user's tag
						if(is_null($profile->tag_str)){
							$tag_str = e(null);
						} else {
							$tag_str = explode(',', substr($profile->tag_str, 1));
						}

						return Response::json(
							array(
								'status'		=> 1,
								'user_id'		=> e($data->id),
								'sex'			=> e($data->sex),
								'bio'			=> e($data->bio),
								'nickname'		=> e($data->nickname),
								'born_year'		=> e($data->born_year),
								'school'		=> e($data->school),
								'is_admin'		=> e($data->is_admin),
								'is_verify'		=> e($data->is_verify),
								'portrait'		=> route('home') . '/' . 'portrait/' . $data->portrait,
								'constellation'	=> $constellationInfo['name'],
								'tag_str'		=> $tag_str,
								'hobbies'		=> e($profile->hobbies),
								'grade'			=> e($profile->grade),
								'question'		=> e($profile->question),
								'self_intro'	=> e($profile->self_intro),
								'like'			=> e($likeCount),
								'user_like_me'	=> e($user_like_me),
								'answer'		=> e($answer),
								'crenew'		=> e($crenew),
							)
						);
					} else {
						return Response::json(
							array(
								'status' 		=> 0
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

					if ($info)
					{
						// Retrieve user
						$user				= User::where('id', Input::get('id'))->first();
						$profile			= Profile::where('user_id', $user->id)->first();

						// Get user's constellation
						$constellationInfo	= getConstellation($profile->constellation);

						// Get user's tag
						if(is_null($profile->tag_str)){
							$tag_str = e(null);
						} else {
							$tag_str = explode(',', substr($profile->tag_str, 1));
						}

						$data = array(
								'status'		=> 1,
								'sex'			=> e($user->sex),
								'bio'			=> e($user->bio),
								'nickname'		=> e($user->nickname),
								'born_year'		=> e($user->born_year),
								'school'		=> e($user->school),
								'portrait'		=> route('home') . '/' . 'portrait/' . $user->portrait,
								'constellation'	=> $constellationInfo['name'],
								'hobbies'		=> e($profile->hobbies),
								'tag_str'		=> $tag_str,
								'grade'			=> e($profile->grade),
								'question'		=> e($profile->question),
								'self_intro'	=> e($profile->self_intro)
							);
						return Response::json($data);
					} else {
						return Response::json(
							array(
								'status' 		=> 0
							)
						);
					}
				break;

				// Like

				case 'like' :

					// Get all form data.
					$data	= Input::all();

					// Create validation rules
					$rules	= array(
						'id'			=> 'required',
						'receiverid'	=> 'required',
						//'answer'		=> 'required|min:3',
					);
					// Custom validation message
					$messages = array(
						'answer.required'     => '请回答爱情考验问题。',
						//'answer.min'          => '至少要写:min个字哦。',
					);

					// Begin verification
					$validator   = Validator::make($data, $rules, $messages);
					if ($validator->passes())
					{
						$user			= User::where('id', Input::get('id'))->first();
						$receiver_id	= Input::get('receiverid');
						if($user->points > 0)
						{
							$have_like = Like::where('sender_id', $user->id)->where('receiver_id', $receiver_id)->first();

							// This user already sent like
							if($have_like)
							{
								$have_like->answer	= Input::get('answer');
								$have_like->count	= $have_like->count + 1;
								$user->points		= $user->points - 1;
								if($have_like->save() && $user->save())
								{
									// Some user re-liked you
									$notification = Notification(2, $user->id, $receiver_id);

									// Add push notifications for App client to queue
									Queue::push('LikeQueue', [
																'target'	=> $receiver_id,
																'action'	=> 2,
																'from'		=> $user->id,

																// Notification ID
																'id' 		=> e($notification->id),
																'content'	=> $user->nickname . '再次追你了，快去查看一下吧',
																'sender_id'	=> e(Input::get('id')),
																'portrait'	=> route('home') . '/' . 'portrait/' . $user->portrait,
																'nickname'	=> $user->nickname,
																'answer'	=> Input::get('answer')
															]);

									return Response::json(
										array(
											'status' 		=> 1
										)
									);
								}
							} else { // First like
								$like				= new Like();
								$like->sender_id	= $user->id;
								$like->receiver_id	= $receiver_id;
								$like->status		= 0; // User send like, pending accept
								$like->answer		= Input::get('answer');
								$like->count		= 1;
								$user->points		= $user->points - 1;
								if($like->save() && $user->save())
								{
									$notification = Notification(1, $user->id, $receiver_id); // Some user first like you

									// Add push notifications for App client to queue
									Queue::push('LikeQueue', [
																'target'	=> $receiver_id,
																'action'	=> 1,
																'from'		=> $user->id,
																'content'	=> $user->nickname.'追你了，快去查看一下吧',

																// Notification ID
																'id'		=> e($notification->id),
																'sender_id'	=> e(Input::get('id')),
																'portrait'	=> route('home') . '/' . 'portrait/' . $user->portrait,
																'nickname'	=> $user->nickname,
																'answer'	=> Input::get('answer')
															]);

									return Response::json(
										array(
											'status' 		=> 1
										)
									);
								}
							}
						} else {
							return Response::json(
								array(
									'status' 	=> 2 // User's point required
								)
							);
						}
					} else {
						return Response::json(
							array(
								'status' 		=> 0
							)
						);
					}
				break;

				// Sent

				case 'sent' :
					// Post last user id from App client
					$last_id	= Input::get('lastid');

					// Post count per query from App client
					$per_page	= Input::get('perpage');

					// Get user id
					$user_id	= Input::get('id');

					// If App have post last user id
					if($last_id)
					{
						// Query all user liked users
						$allLike    = Like::where('sender_id', $user_id)
							->orderBy('id', 'desc')
							->select('receiver_id', 'status', 'created_at', 'count')
							->where('id', '<', $last_id)
							->take($per_page)
							->get()
							->toArray();

						// Replace receiver_id key name to portrait
						foreach($allLike as $key1 => $val1){
							foreach($val1 as $key => $val){
								$new_key				= str_replace('receiver_id', 'portrait', $key);
								$new_array[$new_key]	= $val;
							}
							$likes[] = $new_array;
						}

						// Replace receiver ID to receiver portrait
						foreach($likes as $key => $field){

							// Retrieve receiver user
							$user						= User::where('id',  $likes[$key]['portrait'])->first();

							// Receiver ID
							$likes[$key]['id']			= e($user->id);

							// Receiver avatar real storage path
							$likes[$key]['portrait']	= route('home') . '/' . 'portrait/' . $user->portrait;

							// Receiver school
							$likes[$key]['school']		= e($user->school);

							// Receiver nickname
							$likes[$key]['name']		= e($user->nickname);

							// Receiver sex
							$likes[$key]['sex']			= e($user->sex);

							// Convert how long liked
							$Date_1						= date("Y-m-d"); // Current date and time
							$Date_2						= date("Y-m-d",strtotime($likes[$key]['created_at']));
							$d1							= strtotime($Date_1);
							$d2							= strtotime($Date_2);

							// Calculate liked time
							$Days						= round(($d1-$d2)/3600/24);
							$likes[$key]['created_at']	= $Days;
						}

						// Encode likes array to json format
						$like = json_encode($likes);
						if($allLike)
						{
							return '{ "status" : "1", "data" : ' . $like . '}';
						} else {
							return Response::json(
								array(
									'status' 		=> 0
								)
							);
						}
					} else {

						// First get data from App client and query last like id in database
						$lastRecord = Like::where('sender_id', $user_id)->orderBy('id', 'desc')->first();

						// Determin like exist
						if(is_null($lastRecord))
						{
							return '{ "status" : "1", "data" : []}';
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
							foreach($allLike as $key1 => $val1){
								foreach($val1 as $key => $val){
									$new_key				= str_replace('receiver_id', 'portrait', $key);
									$new_array[$new_key]	= $val;
								}
								$likes[] = $new_array;
							}

							// Replace receiver ID to receiver portrait
							foreach($likes as $key => $field){

								// Retrieve receiver user
								$user						= User::where('id',  $likes[$key]['portrait'])->first();

								// Receiver ID
								$likes[$key]['id']			= e($user->id);

								// Receiver avatar real storage path
								$likes[$key]['portrait']	= route('home') . '/' . 'portrait/' . $user->portrait;

								// Receiver school
								$likes[$key]['school']		= e($user->school);

								// Receiver nickname
								$likes[$key]['name']		= e($user->nickname);

								// Receiver sex
								$likes[$key]['sex']			= e($user->sex);

								// Convert how long liked
								// Current date and time
								$Date_1						= date("Y-m-d");
								$Date_2						= date("Y-m-d",strtotime($likes[$key]['created_at']));
								$d1							= strtotime($Date_1);
								$d2							= strtotime($Date_2);

								// Calculate liked time
								$Days						= round(($d1-$d2)/3600/24);
								$likes[$key]['created_at']	= $Days;
							}

							// Encode likes array to json format
							$like = json_encode($likes);
							if($allLike)
							{
								return '{ "status" : "1", "data" : ' . $like . '}';
							} else {
								return Response::json(
									array(
										'status' 		=> 0
									)
								);
							}
						}
					}
				break;

				// Inbox

				case 'inbox' :

					// Post last user id from App client
					$last_id	= Input::get('lastid');

					// Post count per query from App client
					$per_page	= Input::get('perpage');

					// Get user id
					$user_id	= Input::get('id');

					// If App have post last user id
					if($last_id != 'null')
					{
						// Query all user liked users
						$allLike	= Like::where('receiver_id', $user_id)
							->orderBy('id', 'desc')
							->select('sender_id', 'status', 'created_at', 'count')
							->where('id', '<', $last_id)
							->take($per_page)
							->get()
							->toArray();

						// Replace sender_id key name to portrait
						foreach($allLike as $key1 => $val1){
							foreach($val1 as $key => $val){
								$new_key				= str_replace('sender_id', 'portrait', $key);
								$new_array[$new_key]	= $val;
							}
							$likes[] = $new_array;
						}

						// Replace receiver ID to receiver portrait
						foreach($likes as $key => $field){

							// Receiver ID
							$likes[$key]['id']			= $likes[$key]['portrait'];

							// Receiver avatar real storage path
							$likes[$key]['portrait']	= route('home') . '/' . 'portrait/' . User::where('id', $likes[$key]['portrait'])->first()->portrait;

							// Receiver school
							$likes[$key]['school']		= User::where('id', $likes[$key]['id'])->first()->school;

							// Receiver ID
							$likes[$key]['name']		= User::where('id', $likes[$key]['id'])->first()->nickname;

							// Convert how long liked
							$Date_1						= date("Y-m-d");

							// Current date and time
							$Date_2						= date("Y-m-d",strtotime($likes[$key]['created_at']));
							$d1							= strtotime($Date_1);
							$d2							= strtotime($Date_2);

							// Calculate liked time
							$Days						= round(($d1-$d2)/3600/24);
							$likes[$key]['created_at']	= $Days;
						}

						// Encode likes array to json format
						$like = json_encode($likes);
						if($allLike)
						{
							return '{ "status" : "1", "data" : '.$like.'}';
						} else {
							return Response::json(
								array(
									'status' 		=> 0
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
						foreach($allLike as $key1 => $val1){
							foreach($val1 as $key => $val){
								$new_key				= str_replace('sender_id', 'portrait', $key);
								$new_array[$new_key]	= $val;
							}
							$likes[] = $new_array;
						}

						// Replace receiver ID to receiver portrait
						foreach($likes as $key => $field){

							// Receiver ID
							$likes[$key]['id']			= $likes[$key]['portrait'];

							// Receiver avatar real storage path
							$likes[$key]['portrait']	= route('home') . '/' . 'portrait/' . User::where('id', $likes[$key]['portrait'])->first()->portrait;

							// Receiver school
							$likes[$key]['school']		= User::where('id', $likes[$key]['id'])->first()->school;

							// Receiver ID
							$likes[$key]['name']		= User::where('id', $likes[$key]['id'])->first()->nickname;

							// Convert how long liked
							$Date_1						= date("Y-m-d");

							// Current date and time
							$Date_2						= date("Y-m-d",strtotime($likes[$key]['created_at']));
							$d1							= strtotime($Date_1);
							$d2							= strtotime($Date_2);

							// Calculate liked time
							$Days						= round(($d1-$d2)/3600/24);
							$likes[$key]['created_at']	= $Days;
						}

						// Encode likes array to json format
						$like = json_encode($likes);
						if($allLike)
						{
							return '{ "status" : "1", "data" : '.$like.'}';
						} else {
							return Response::json(
								array(
									'status' 		=> 0
								)
							);
						}
					}
				break;

				// Accept

				case 'accept' :

					// Get sender ID from client
					$id				= Input::get('senderid');
					$sender 		= User::where('id', $id)->first();

					// Get receiver ID from client
					$receiver_id	= Input::get('receiverid');
					$receiver		= User::where('id', $receiver_id)->first();
					$like			= Like::where('sender_id', $id)->where('receiver_id', $receiver_id)->first();

					// Receiver accept like
					$like->status	= 1;

					// Add friend relationship in chat system and start chat
					Queue::push('AddFriendQueue', [
											'user_id'	=> $receiver_id,
											'friend_id'	=> $id,
										]);
					Queue::push('AddFriendQueue', [
											'user_id'	=> $id,
											'friend_id'	=> $receiver_id,
										]);

					if($like->save())
					{
						// Save notification in database for website
						$notification	= Notification(3, $receiver_id, $id); // Some user accept you like

						// Add push notifications for App client to queue
						Queue::push('LikeQueue', [
													'target'	=> $id,
													'action'	=> 3,
													'from'		=> $receiver_id,

													// Notification ID
													'id'		=> e($notification->id),
													'content'	=> $receiver->nickname . '接受了你的邀请，快去查看一下吧',
													'sender_id'	=> e($receiver_id),
													'portrait'	=> route('home') . '/' . 'portrait/' . $receiver->portrait,
													'nickname'	=> $receiver->nickname,
													'answer'	=> null
												]);

						return Response::json(
								array(
									'status'	=> 1,
									'id'		=> $id,
									'portrait'	=> route('home').'/'.'portrait/'.$sender->portrait,
									'nickname'	=> $sender->nickname
								)
							);
					} else {
						return Response::json(
								array(
									'status' 		=> 0
								)
							);
					}
				break;

				// Reject

				case 'reject' :

					// Get sender ID from client
					$id				= Input::get('senderid');

					// Get receiver ID from client
					$receiver_id	= Input::get('receiverid');
					$receiver		= User::where('id', $receiver_id)->first();
					$like			= Like::where('sender_id', $id)->where('receiver_id', $receiver_id)->first();

					// Receiver reject user, remove friend relationship in chat system
					$like->status	= 2;

					if($like->save())
					{
						// Save notification in database for website
						$notification	= Notification(4, $receiver_id, $id); // Some user reject you like

						// $easemob		= getEasemob();
						// Push notifications to App client
						// cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
						// 		'target_type'	=> 'users',
						// 		'target'		=> [$id],
						// 		'msg'			=> ['type' => 'cmd', 'action' => '4'],
						// 		'from'			=> $receiver_id,
						// 		'ext'			=> [
						// 								'content'	=> $receiver->nickname.'拒绝了你的邀请',
						// 								'id'		=> $receiver_id,
						// 								'portrait'	=> route('home').'/'.'portrait/'.$receiver->portrait,
						// 								'nickname'	=> $receiver->nickname
						// 							]
						// 	])
						// 		->setHeader('content-type', 'application/json')
						// 		->setHeader('Accept', 'json')
						// 		->setHeader('Authorization', 'Bearer '.$easemob->token)
						// 		->setOptions([CURLOPT_VERBOSE => true])
						// 		->send();
						return Response::json(
								array(
									'status' 		=> 1
								)
							);
					} else {
						return Response::json(
								array(
									'status' 		=> 0
								)
							);
					}
				break;

				// Block

				case 'block' :
					$id				= Input::get('senderid');
					$receiver_id	= Input::get('id');
					$like			= Like::where('sender_id', $id)->where('receiver_id', $receiver_id)->first();

					// Receiver block user, remove friend relationship in chat system
					$like->status	= 3;

					// Some user blocked you
					$notification	= Notification(5, $id, $receiver_id);

					// Remove friend relationship in chat system
					Queue::push('DeleteFriendQueue', [
											'user_id'	=> $receiver_id,
											'block_id'	=> $id,
										]);
					Queue::push('DeleteFriendQueue', [
											'user_id'	=> $id,
											'block_id'	=> $receiver_id,
										]);

					// Push notifications to App client
					// cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
					// 		'target_type'	=> 'users',
					// 		'target'		=> [$id],
					// 		'msg'			=> ['type' => 'cmd', 'action' => '5'],
					// 		'from'			=> $receiver_id,
					// 		'ext'			=> ['content' => User::where('id', $receiver_id)->first()->nickname.'把你加入了黑名单', 'id' => $notification->id]
					// 	])
					// 		->setHeader('content-type', 'application/json')
					// 		->setHeader('Accept', 'json')
					// 		->setHeader('Authorization', 'Bearer '.$easemob->token)
					// 		->setOptions([CURLOPT_VERBOSE => true])
					// 		->send();

					if($like->save())
					{
						return Response::json(
							array(
								'status' 		=> 1
							)
						);
					} else {
						return Response::json(
							array(
								'status' 		=> 0
							)
						);
					}
				break;

				// Renew

				case 'renew' :
					if (Input::get('dorenew') != 'null') {
						$renew		= Input::get('renew');
						$today		= Carbon::today();
						$yesterday	= Carbon::yesterday();
						$user		= Profile::where('user_id', Input::get('id'))->first();
						$points		= User::where('id', Input::get('id'))->first();

						if($user->renew_at == '0000-00-00 00:00:00'){ // First renew
							$user->renew_at	= Carbon::now();
							$user->renew	= $user->renew + 1;
							$user->crenew	= $user->crenew + 1;
							$points->points	= $points->points + 5;
							$user->save();
							$points->save();
							return Response::json(
								array(
									'status' 		=> 1,
									'renewdays' 	=> $user->renew
								)
							);
						} else if ($today >= $user->renew_at){

							// Check user whether or not renew yesterday
							if($yesterday <= $user->renew_at){

								// Keep renew
								$user->crenew	= $user->crenew + 1;
							} else {

								// Not keep renew, reset renew count
								$user->crenew	= 0;
							}

							// You haven't renew today
							$user->renew_at	= Carbon::now();
							$user->renew	= $user->renew + 1;
							$points->points	= $points->points + 2;
							$user->save();
							$points->save();
							return Response::json(
								array(
									'status' 		=> 1,
									'renewdays' 	=> $user->renew
								)
							);
						} else {

							// You have renew today
							return Response::json(
								array(
									'status' 		=> 2
								)
							);
						}
					} else {
						return Response::json(
							array(
								'status'	=> 1,
								'renewdays'	=> Profile::where('user_id', Input::get('id'))->first()->renew
							)
						);
					}
				break;

				// Get user friends nickname

				case 'getnickname' :

					// Get query ID from App client
					$id		= Input::get('id');

					// Get sender user data
					$friends = Like::where('receiver_id', $id)->orWhere('sender_id', $id)
								->where('status', 1)
								->select('sender_id', 'receiver_id')
								->get()
								->toArray();

					foreach($friends as $key => $field){

							// Determine user is sender or receiver
							if($friends[$key]['sender_id'] == $id) {

								// User is sender and retrieve receiver user
								$user = User::where('id', $friends[$key]['receiver_id'])->first();

								// Friend ID
								$friends[$key]['friend_id']	= $user->id;

								// Friend nickname
								$friends[$key]['nickname']	= e($user->nickname);

								// Determine user portrait
								if(is_null($user->portrait)){

									// Friend portrait
									$friends[$key]['portrait']	= null;
								} else {

									// Friend portrait
									$friends[$key]['portrait']	= route('home'). '/' . 'portrait/' . $user->portrait;
								}
							} else {

								// User is receiver and retrieve sender user
								$user = User::where('id', $friends[$key]['sender_id'])->first();

								// Friend ID
								$friends[$key]['friend_id']	= $user->id;

								// Friend nickname
								$friends[$key]['nickname']	= e($user->nickname);

								// Determine user portrait
								if(is_null($user->portrait)){

									// Friend portrait
									$friends[$key]['portrait']	= null;
								} else {

									// Friend portrait
									$friends[$key]['portrait']	= route('home'). '/' . 'portrait/' . $user->portrait;
								}
							}
						}

					// Convert array to json format
					$friend = json_encode($friends);

					// Query successful
					if($friend)
					{
						return '{ "status" : "1", "data" : ' . $friend . '}';
					} else {
						return Response::json(
							array(
								'status' 	=> 0
							)
						);
					}
				break;

				// Set avatar

				case 'setportrait' :

					// Retrieve user
					$user			= User::where('id', Input::get('id'))->first();

					// Old portrait
					$oldPortrait	= $user->portrait;

					// Get user portrait name
					$portrait		= Input::get('portrait');

					// User not update portrait
					if($portrait == $oldPortrait)
					{
						// Direct return success
						return Response::json(
							array(
								'status' 		=> 1
							)
						);
					} else{

						// User update avatar
						$portraitPath		= public_path('portrait/');

						// Save file name to database
						$user->portrait     = 'android/' . $portrait;

						if ($user->save()) {
							// Update success
							$oldAndroidPortrait = strpos($oldPortrait, 'android');
							if($oldAndroidPortrait === false) // Must use ===
							{
								// Delete old poritait
								File::delete($portraitPath . $oldPortrait);
								return Response::json(
									array(
										'status' 	=> 1
									)
								);
							} else {
								// Update success
								return Response::json(
									array(
										'status' 	=> 1
									)
								);
							}
						} else {
							// Update fail
							return Response::json(
								array(
									'status' 	=> 0
								)
							);
						}
					}
				break;

				// Upload portrait

				case 'uploadportrait' :

					// Retrieve user
					$user			= User::where('id', Input::get('id'))->first();

					// Old pritrait
					$oldPortrait	= $user->portrait;

					// Portrait data
					$portrait		= Input::get('portrait');

					// Portrait MIME
					$mime			= Input::get('mime');

					// User update avatar
					if($portrait != NULL)
					{
						$portrait           = str_replace('data:image/' . $mime . ';base64,', '', $portrait);
						$portrait           = str_replace(' ', '+', $portrait);
						$portraitData       = base64_decode($portrait);

						// Decode string
						$portraitPath		= public_path('portrait/');

						// Portrait file name
						$portraitFile       = uniqid() . '.' . $mime;

						// Store file
						$successPortrait    = file_put_contents($portraitPath . $portraitFile, $portraitData);

						// Save file name to database
						$user->portrait     = $portraitFile;

						if ($user->save()){
							// Determine user portrait type
							$asset = strpos($oldPortrait, 'android');

							// Should to use !== false
							if($asset !== false){
								// No nothing
							} else {
								// User set portrait from web delete old poritait
								File::delete($portraitPath . $oldPortrait);
							}
							// Update success
							return Response::json(
								array(
									'status' 	=> 1
								)
							);
						} else {
							// Update fail
							return Response::json(
								array(
									'status' 	=> 0
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
					$user_id			= Input::get('userid');

					// Post last user ID from App client
					$last_id			= Input::get('lastid');

					// Post count per query from App client
					$per_page			= Input::get('perpage');

					// Post category ID from App client
					$cat_id				= Input::get('catid');

					// Post number chars of post summary from App client
					$numchars			= Input::get('numchars');

					// If App have post last user id
					if($last_id != 'null') {

						// Get last post updated at
						$last_updated_at	= ForumPost::where('id', $last_id)->first()->updated_at;

						// Query all items from database
						$items	= ForumPost::where('category_id', $cat_id)
									->orderBy('updated_at' , 'desc')
									->where('updated_at', '<', $last_updated_at)
									->where('top', 0)
									->select('id', 'user_id', 'title', 'content', 'created_at')
									->take($per_page)
									->get()
									->toArray();

						// Replace receiver ID to receiver portrait
						foreach($items as $key => $field){

							// Retrieve user
							$post_user						= User::where('id', $items[$key]['user_id'])->first();

							// Get post user portrait real storage path and user porirait key to array
							$items[$key]['portrait']		= route('home') . '/' . 'portrait/' . $post_user->portrait;

							// Get post user sex (M, F or null) and add user sex key to array
							$items[$key]['sex']				= e($post_user->sex);

							// Count how many comments of this post and add comments_count key to array
							$items[$key]['comments_count']	= e(ForumComments::where('post_id', $items[$key]['id'])->count());

							// Get post user portrait and add portrait key to array
							$items[$key]['nickname']		= e($post_user->nickname);

							// Using expression get all picture attachments (Only with pictures stored on this server.)
							preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $items[$key]['content'], $match );

							// Construct picture attachments list and add thumbnails (array format) to array
							$items[$key]['thumbnails']		= join(',', array_pop($match));

							// Get plain text from post content HTML code and replace to content value in array
							$items[$key]['content']			= str_ireplace("\n", '', getplaintextintrofromhtml($items[$key]['content'], $numchars));

							// Get forum title
							$items[$key]['title']			= e(Str::limit($items[$key]['title'], 35));
						}

						// Build Json format
						return '{ "status" : "1", "data" : {"top":[], "items" : ' . json_encode($items) . '}}';

					} else { // First get data from App client

						// Determine forum open status
						if(ForumCategories::where('id', 1)->first()->open == 1) {

							// Forum is opening query last user id in database
							$lastRecord = ForumPost::orderBy('updated_at', 'desc')->first();

							// Post not exists
							if(is_null($lastRecord)) {

								// Build Json format
								return '{ "status" : "1", "data" : []}';
							} else {

								// Post exists

								// Query all items from database
								$top	= ForumPost::where('category_id', $cat_id)
											->orderBy('updated_at' , 'desc')
											->where('updated_at', '<=', $lastRecord->updated_at)
											->where('top', 1)
											->select('id', 'user_id', 'title', 'content', 'created_at')
											->take('5')
											->get()
											->toArray();

								// Replace receiver ID to receiver portrait
								foreach($top as $key => $field){

									// Retrieve user
									$post_user						= User::where('id', $top[$key]['user_id'])->first();

									// Get post user portrait real storage path and user porirait key to array
									$top[$key]['portrait']			= route('home') . '/' . 'portrait/' . $post_user->portrait;

									// Get post user sex (M, F or null) and add user sex key to array
									$top[$key]['sex']				= e($post_user->sex);

									// Count how many comments of this post and add comments_count key to array
									$top[$key]['comments_count']	= ForumComments::where('post_id', $top[$key]['id'])->count();

									// Get post user portrait and add portrait key to array
									$top[$key]['nickname']			= e($post_user->nickname);

									// Using expression get all picture attachments (Only with pictures stored on this server.)
									preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $top[$key]['content'], $match );

									// Construct picture attachments list and add thumbnails (array format) to array
									$top[$key]['thumbnails']		= join(',', array_pop($match));

									// Get plain text from post content HTML code and replace to content value in array
									$top[$key]['content']			= getplaintextintrofromhtml($top[$key]['content'], $numchars);

									// Get forum top post title
									$top[$key]['title']				= e(Str::limit($top[$key]['title'], 35));
								}

								// Query all items from database
								$items	= ForumPost::where('category_id', $cat_id)
											->orderBy('updated_at' , 'desc')
											->where('updated_at', '<=', $lastRecord->updated_at)
											->where('top', 0)
											->select('id', 'user_id', 'title', 'content', 'created_at')
											->take($per_page)
											->get()
											->toArray();

								// Replace receiver ID to receiver portrait
								foreach($items as $key => $field){

									// Retrieve user
									$post_user						= User::where('id', $items[$key]['user_id'])->first();

									// Get post user portrait real storage path and user porirait key to array
									$items[$key]['portrait']		= route('home') . '/' . 'portrait/' . $post_user->portrait;

									// Get post user sex (M, F or null) and add user sex key to array
									$items[$key]['sex']				= e($post_user->sex);

									// Count how many comments of this post and add comments_count key to array
									$items[$key]['comments_count']	= ForumComments::where('post_id', $items[$key]['id'])->count();

									// Get post user portrait and add portrait key to array
									$items[$key]['nickname']		= e($post_user->nickname);

									// Using expression get all picture attachments (Only with pictures stored on this server.)
									preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $items[$key]['content'], $match );

									// Construct picture attachments list and add thumbnails (array format) to array
									$items[$key]['thumbnails']		= join(',', array_pop($match));

									// Get plain text from post content HTML code and replace to content value in array
									$items[$key]['content']			= str_ireplace("\n", '',getplaintextintrofromhtml($items[$key]['content'], $numchars));

									// Get forum title
									$items[$key]['title']			= e(Str::limit($items[$key]['title'], 35));
								}

								$data = array(
										'top'	=> $top,
										'items'	=> $items
									);

								// Build Json format
								echo '{ "status" : "1", "data" : ' . json_encode($data) . '}';

							}
						} else {

							// Retrieve user
							$user = User::find($user_id);

							// Determine user sex
							if($user->sex == 'M') {

								// Male user and determine category
								if($cat_id == 3) {

									// Forum is closed and build Json format
									echo '{ "status" : "2" }';

								} else {

									// Forum is opening query last user id in database
									$lastRecord = ForumPost::orderBy('updated_at', 'desc')->first();

									// Post not exists
									if(is_null($lastRecord)) {

										// Build Json format
										return '{ "status" : "1", "data" : []}';
									} else {

										// Post exists and query all items from database
										$top	= ForumPost::where('category_id', $cat_id)
													->orderBy('updated_at' , 'desc')
													->where('updated_at', '<=', $lastRecord->updated_at)
													->where('top', 1)
													->select('id', 'user_id', 'title', 'content', 'created_at')
													->take('5')
													->get()
													->toArray();

										// Replace receiver ID to receiver portrait
										foreach($top as $key => $field){

											// Retrieve user
											$post_user						= User::where('id', $top[$key]['user_id'])->first();

											// Get post user portrait real storage path and user porirait key to array
											$top[$key]['portrait']			= route('home') . '/' . 'portrait/' . $post_user->portrait;

											// Get post user sex (M, F or null) and add user sex key to array
											$top[$key]['sex']				= e($post_user->sex);

											// Count how many comments of this post and add comments_count key to array
											$top[$key]['comments_count']	= ForumComments::where('post_id', $top[$key]['id'])->count();

											// Get post user portrait and add portrait key to array
											$top[$key]['nickname']			= e($post_user->nickname);

											// Using expression get all picture attachments (Only with pictures stored on this server.)
											preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $top[$key]['content'], $match );

											// Construct picture attachments list and add thumbnails (array format) to array
											$top[$key]['thumbnails']		= join(',', array_pop($match));

											// Get plain text from post content HTML code and replace to content value in array
											$top[$key]['content']			= str_ireplace("\n", '',getplaintextintrofromhtml($top[$key]['content'], $numchars));

											// Get forum top post title
											$top[$key]['title']				= e(Str::limit($top[$key]['title'], 35));
										}

										// Query all items from database
										$items	= ForumPost::where('category_id', $cat_id)
													->orderBy('updated_at' , 'desc')
													->where('updated_at', '<=', $lastRecord->updated_at)
													->where('top', 0)
													->select('id', 'user_id', 'title', 'content', 'created_at')
													->take($per_page)
													->get()
													->toArray();

										// Replace receiver ID to receiver portrait
										foreach($items as $key => $field){

											// Retrieve user
											$post_user						= User::where('id', $items[$key]['user_id'])->first();

											// Get post user portrait real storage path and user porirait key to array
											$items[$key]['portrait']		= route('home') . '/' . 'portrait/' . $post_user->portrait;

											// Get post user sex (M, F or null) and add user sex key to array
											$items[$key]['sex']				= e($post_user->sex);

											// Count how many comments of this post and add comments_count key to array
											$items[$key]['comments_count']	= ForumComments::where('post_id', $items[$key]['id'])->count();

											// Get post user portrait and add portrait key to array
											$items[$key]['nickname']		= e($post_user->nickname);

											// Using expression get all picture attachments (Only with pictures stored on this server.)
											preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $items[$key]['content'], $match );

											// Construct picture attachments list and add thumbnails (array format) to array
											$items[$key]['thumbnails']		= join(',', array_pop($match));

											// Get plain text from post content HTML code and replace to content value in array
											$items[$key]['content']			= getplaintextintrofromhtml($items[$key]['content'], $numchars);

											// Get forum title
											$items[$key]['title']			= e(Str::limit($items[$key]['title'], 35));
										}

										$data = array(
												'top'	=> $top,
												'items'	=> $items
											);

										// Build Json format
										echo '{ "status" : "1", "data" : ' . json_encode($data) . '}';
									}
								}
							} else {

								// Female user and determine category
								if($cat_id == 2) {

									// Forum is closed and build Json format
									echo '{ "status" : "2" }';

								} else {

									// Forum is opening query last user id in database
									$lastRecord = ForumPost::orderBy('updated_at', 'desc')->first();

									// Post not exists
									if(is_null($lastRecord)) {

										// Build Json format
										return '{ "status" : "1", "data" : []}';
									} else {

										// Post exists

										// Query all items from database
										$top	= ForumPost::where('category_id', $cat_id)
													->orderBy('updated_at' , 'desc')
													->where('updated_at', '<=', $lastRecord->updated_at)
													->where('top', 1)
													->select('id', 'user_id', 'title', 'content', 'created_at')
													->take('5')
													->get()
													->toArray();

										// Replace receiver ID to receiver portrait
										foreach($top as $key => $field){

											// Retrieve user
											$post_user						= User::where('id', $top[$key]['user_id'])->first();

											// Get post user portrait real storage path and user porirait key to array
											$top[$key]['portrait']			= route('home') . '/' . 'portrait/' . $post_user->portrait;

											// Get post user sex (M, F or null) and add user sex key to array
											$top[$key]['sex']				= e($post_user->sex);

											// Count how many comments of this post and add comments_count key to array
											$top[$key]['comments_count']	= ForumComments::where('post_id', $top[$key]['id'])->count();

											// Get post user portrait and add portrait key to array
											$top[$key]['nickname']			= e($post_user->nickname);

											// Using expression get all picture attachments (Only with pictures stored on this server.)
											preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $top[$key]['content'], $match );

											// Construct picture attachments list and add thumbnails (array format) to array
											$top[$key]['thumbnails']		= join(',', array_pop($match));

											// Get plain text from post content HTML code and replace to content value in array
											$top[$key]['content']			= getplaintextintrofromhtml($top[$key]['content'], $numchars);

											// Get forum top post title
											$top[$key]['title']				= e(Str::limit($top[$key]['title'], 35));
										}

										// Query all items from database
										$items	= ForumPost::where('category_id', $cat_id)
													->orderBy('updated_at' , 'desc')
													->where('updated_at', '<=', $lastRecord->updated_at)
													->where('top', 0)
													->select('id', 'user_id', 'title', 'content', 'created_at')
													->take($per_page)
													->get()
													->toArray();

										// Replace receiver ID to receiver portrait
										foreach($items as $key => $field){

											// Retrieve user
											$post_user						= User::where('id', $items[$key]['user_id'])->first();

											// Get post user portrait real storage path and user porirait key to array
											$items[$key]['portrait']		= route('home') . '/' . 'portrait/' . $post_user->portrait;

											// Get post user sex (M, F or null) and add user sex key to array
											$items[$key]['sex']				= e($post_user->sex);

											// Count how many comments of this post and add comments_count key to array
											$items[$key]['comments_count']	= ForumComments::where('post_id', $items[$key]['id'])->count();

											// Get post user portrait and add portrait key to array
											$items[$key]['nickname']		= e($post_user->nickname);

											// Using expression get all picture attachments (Only with pictures stored on this server.)
											preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $items[$key]['content'], $match );

											// Construct picture attachments list and add thumbnails (array format) to array
											$items[$key]['thumbnails']		= join(',', array_pop($match));

											// Get plain text from post content HTML code and replace to content value in array
											$items[$key]['content']			= getplaintextintrofromhtml($items[$key]['content'], $numchars);

											// Get forum title
											$items[$key]['title']			= e(Str::limit($items[$key]['title'], 35));
										}

										$data = array(
												'top'	=> $top,
												'items'	=> $items
											);

										// Build Json format
										echo '{ "status" : "1", "data" : ' . json_encode($data) . '}';
									}
								}
							}
						}
					}

				break;

				// Forum Get to Show Post
				case 'forum_getpost' :
					$postid		= Input::get('postid');

					$lastid		= Input::get('lastid');

					$perpage	= Input::get('perpage', 10);

					// Define breaks convert rules
					$breaks		= array("<br />","<br>","<br/>","</p>");

					// If App have post last user id
					if($lastid == null) {

						// First get data from App client and Retrieve post data
						$post		= ForumPost::where('id', $postid)->first();

						// Determine forum post exist
						if(is_null($post)) {

							// Build Json format
							return '{ "status" : "2" }';

						} else {

							// Retrieve user data of this post
							$author		= User::where('id', $post->user_id)->first();

							// Get last record from database
							$lastRecord	= ForumComments::orderBy('id', 'desc')->first();

							// Determine forum comments exist
							if(is_null($lastRecord)){

								// Build Data Array
								$data = array(

									// Post user portrait
									'portrait'		=> route('home') . '/' . 'portrait/' . $author->portrait,

									// Post user sex
									'sex'			=> e($author->sex),

									// Post user nickname
									'nickname'		=> e($author->nickname),

									// Post user ID
									'user_id'		=> $author->id,

									// Post comments count
									'comment_count'	=> ForumComments::where('post_id', $postid)->get()->count(),

									// Post created date
									'created_at'	=> $post->created_at->toDateTimeString(),

									// Post content (removing contents html tags except image and text string)
									'content'		=> strip_tags(str_replace("&nbsp;", " ", str_ireplace($breaks, "\\n", $post->content)), '<img>'),

									// Post comments (array format and include reply)
									'comments'		=> array(),

									// Post title
									'title'			=> str_replace("&nbsp;", " ", $post->title)

								);

								// Build Json format
								return '{ "status" : "1", "data" : ' . json_encode($data) . '}';

							} else {

								// Query all comments of this post
								$comments	= ForumComments::where('post_id', $postid)
													->orderBy('created_at' , 'asc')
													->where('id', '<=', $lastRecord->id)
													->select('id', 'user_id', 'content', 'created_at')
													->take($perpage)
													->get()
													->toArray();

								// Build comments array and include reply information
								foreach($comments as $key => $field) {

									// Retrieve comments user
									$comments_user						= User::where('id', $comments[$key]['user_id'])->first();

									// Comments user ID
									$comments[$key]['user_id']			= $comments_user->id;

									// Removing contents html tags except image and text string
									$comments[$key]['content']			= strip_tags(str_ireplace($breaks, "\\n", $comments[$key]['content']), '<img>');
									// Comments user portrait
									$comments[$key]['user_portrait']	= route('home') . '/' . 'portrait/' . $comments_user->portrait;

									// Comments user sex
									$comments[$key]['user_sex']			= e($comments_user->sex);

									// Comments user nickname
									$comments[$key]['user_nickname']	= e($comments_user->nickname);

									// Query all replies of this post
									$replies = ForumReply::where('comments_id', $comments[$key]['id'])
												->select('id', 'user_id', 'content', 'created_at')
												->orderBy('created_at' , 'asc')
												->take(3)
												->get()
												->toArray();

									// Calculate total replies of this post
									$comments[$key]['reply_count'] = ForumReply::where('comments_id', $comments[$key]['id'])->count();

									// Build reply array
									foreach($replies as $keys => $field) {

										// Retrieve reply user
										$reply_user					= User::where('id', $replies[$keys]['user_id'])->first();

										// Reply user sex
										$replies[$keys]['sex']		= $reply_user->sex;

										$replies[$keys]['content'] = str_ireplace($breaks, '\\n', $replies[$keys]['content']);

										// Reply user portrait
										$replies[$keys]['portrait']	= route('home') . '/' . 'portrait/' . $reply_user->portrait;

									}

									// Add comments replies array to post comments_reply array
									$comments[$key]['comment_reply'] = $replies;

								}

								// Build Data Array
								$data = array(

									// Post user portrait
									'portrait'		=> route('home') . '/' . 'portrait/' . $author->portrait,

									// Post user sex
									'sex'			=> e($author->sex),

									// Post user nickname
									'nickname'		=> e($author->nickname),

									// Post user ID
									'user_id'		=> $author->id,

									// Post comments count
									'comment_count'	=> ForumComments::where('post_id', $postid)->get()->count(),

									// Post created date
									'created_at'	=> $post->created_at->toDateTimeString(),

									// Post content (removing contents html tags except image and text string)
									'content'		=> strip_tags(str_replace('&nbsp;', " ", str_ireplace($breaks, "\\n", $post->content)), '<img>'),

									// Post comments (array format and include reply)
									'comments'		=> $comments,
									// Post title
									'title'			=> str_replace("&nbsp;", " ", $post->title)

								);

								// Build Json format
								return '{ "status" : "1", "data" : ' . json_encode($data) . '}';
							}
						}

					} else {

						// First get data from App client and Retrieve post data
						$post		= ForumPost::where('id', $postid)->first();

						// Determine forum post exist
						if(is_null($post)) {

							// Build Json format
							return '{ "status" : "2" }';

						} else {

							// Query all comments of this post
							$comments	= ForumComments::where('post_id', $postid)
												->orderBy('id' , 'asc')
												->where('id', '>', $lastid)
												->select('id', 'user_id', 'content', 'created_at')
												->take($perpage)
												->get()
												->toArray();

							// Build comments array and include reply information
							foreach($comments as $key => $field) {

								// Retrieve comments user
								$comments_user						= User::where('id', $comments[$key]['user_id'])->first();

								// Comments user ID
								$comments[$key]['user_id']			= $comments_user->id;

								// Comments user portrait
								$comments[$key]['user_portrait']	= route('home') . '/' . 'portrait/' . $comments_user->portrait;

								// Comments user sex
								$comments[$key]['user_sex']			= e($comments_user->sex);

								// Comments user nickname
								$comments[$key]['user_nickname']	= e($comments_user->nickname);

								// Removing contents html tags except image and text string
								$comments[$key]['content']			= strip_tags(str_ireplace($breaks, "\\n", $comments[$key]['content']), '<img>');

								// Query all replies of this post
								$replies = ForumReply::where('comments_id', $comments[$key]['id'])
											->select('id', 'user_id', 'content', 'created_at')
											->orderBy('created_at' , 'desc')
											->take(3)
											->get()
											->toArray();

								// Calculate total replies of this post
								$comments[$key]['reply_count'] = ForumReply::where('comments_id', $comments[$key]['id'])->count();

								// Build reply array
								foreach($replies as $keys => $field) {

									// Retrieve reply user
									$reply_user					= User::where('id', $replies[$keys]['user_id'])->first();

									// Reply user sex
									$replies[$keys]['sex']		= e($reply_user->sex);

									$replies[$keys]['content'] = str_ireplace($breaks, '\\n', $replies[$keys]['content']);

									// Reply user portrait
									$replies[$keys]['portrait']	= route('home') . '/' . 'portrait/' . $reply_user->portrait;
								}

								// Add comments replies array to post comments_reply array
								$comments[$key]['comment_reply'] = $replies;
							}

							// Build Data Array
							$data = array(

								// Post comments (array format and include reply)
								'comments'		=> $comments
							);

							// Build Json format
							return '{ "status" : "1", "data" : ' . json_encode($data) . '}';
						}
					}

				break;

				// Forum Post Comments
				case 'forum_postcomment' :
					$user_id	= Input::get('userid');
					$post_id	= Input::get('postid');
					$content	= nl2br(Input::get('content'), true);
					$forum_post	= ForumPost::where('id', $post_id)->first();

					// Select post type
					if(Input::get('type') == 'comments')
					{
						$forum_post->updated_at	= Carbon::now();
						$forum_post->save();
						// Post comments
						$comment				= new ForumComments;
						$comment->post_id		= $post_id;
						$comment->content		= $content;
						$comment->user_id		= $user_id;

						// Calculate this comment in which floor
						$comment->floor			= ForumComments::where('post_id', $post_id)->count() + 2;

						if($comment->save())
						{
							// Determine sender and receiver
							if($user_id != $forum_post->user_id) {

								// Retrieve author of post
								$post_author				= ForumPost::where('id', $post_id)->first();

								// Retrieve forum notifications of post author
								$post_author_notifications	= Notification::where('receiver_id', $post_author->user_id)->whereIn('category', array(6, 7))->where('status', 0);

								$unread = $post_author_notifications->count() + 1;

								// Add push notifications for App client to queue
								Queue::push('ForumQueue', [
															'target'	=> $post_author->user_id,
															'action'	=> 6,
															'from'		=> $user_id,

															// Notification content
															'content'	=> '有人评论了你的帖子，快去看看吧',

															// Sender user ID
															'id'		=> $user_id,

															// Count unread notofications of receiver user
															'unread'	=> $unread
														]);

								// Create notifications
								Notifications(6, $user_id, $forum_post->user_id, $forum_post->category_id, $post_id, $comment->id, null);
							}
							return Response::json(
								array(
									'status'	=> 1
								)
							);
						} else {
							return Response::json(
								array(
									'status'	=> 0
								)
							);
						}
					} else {

						// Post reply
						$reply_id			= Input::get('replyid');
						$comments_id		= nl2br(Input::get('commentid'), true);

						// Create comments reply
						$reply				= new ForumReply;
						$reply->content		= $content;
						$reply->reply_id	= $reply_id;
						$reply->comments_id	= $comments_id;
						$reply->user_id		= $user_id;

						// Calculate this reply in which floor
						$reply->floor		= ForumReply::where('comments_id', Input::get('commentid'))->count() + 1;

						if($reply->save())
						{

							// Retrieve comments
							$comment						= ForumComments::where('id', $comments_id)->first();

							// Retrieve author of comment
							$comment_author					= User::where('id', $comment->user_id)->first();

							// Retrieve forum notifications of comment author
							$comment_author_notifications	= Notification::where('receiver_id', $comment_author->id)->whereIn('category', array(6, 7))->where('status', 0);

							$unread = $comment_author_notifications->count() + 1;

							// Determine sender and receiver
							if($user_id != $comment_author->id) {

								// Add push notifications for App client to queue
								Queue::push('ForumQueue', [
															'target'	=> $comment_author->id,
															// category = 7 Some user reply your comments in forum (Get more info from app/controllers/MemberController.php)

															'action'	=> 7,
															// Sender user ID

															'from'		=> $user_id,
															// Notification content

															'content'	=> '有人回复了你的评论，快去看看吧',
															// Sender user ID

															'id'		=> $user_id,
															// Count unread notofications of receiver user
															'unread'	=> $unread
														]);

								// Create notifications
								Notifications(7, $user_id, $comment_author->id, $forum_post->category_id, $post_id, $comment->id, $reply->id);
							}

							// Reply success
							return Response::json(
								array(
									'status'	=> 1
								)
							);
						} else {
							// Reply fail
							return Response::json(
								array(
									'status'	=> 0
								)
							);
						}

					} // End of select post type
				break;

				// Forum Post New

				case 'forum_postnew' :

					// Create new post
					$post				= new ForumPost;
					$post->category_id	= Input::get('catid');
					$post->title		= Input::get('title');
					$post->user_id		= Input::get('userid');
					$post->content		= nl2br(Input::get('content'), true);

					if($post->save()) {
						// Create successful
						return Response::json(
							array(
								'status' 		=> 1
							)
						);
					} else {
						// Create fail
						return Response::json(
							array(
								'status' 		=> 0
							)
						);
					}

				break;

				// Upload Images
				case 'uploadimage' :

					// Get all json format data from App client and json decode data
					$items	= json_decode(Input::get('data'));

					// Create an empty array to store path of upload image
					$path	= array();

					// Foreach upload data
					foreach($items as $key => $item) {
						$image			= str_replace('data:image/' . $item['0'] . ';base64,', '', $item['1']);
						$image			= str_replace(' ', '+', $image);

						// Decode string
						$imageData		= base64_decode($image);

						// Define upload path
						$imagePath		= public_path('upload/image/android_upload/');

						// Portrait file name
						$imageFile		= uniqid() . '.' . $item['0'];

						// Store file
						$successUpload	= file_put_contents($imagePath . $imageFile, $imageData);
						$path[]['img']		= route('home') . '/upload/image/android_upload/' . $imageFile;
					}
					// Create success
					return Response::json(
						array(
							'status'	=> 1,
							'path'		=> $path
						)
					);
				break;

				// Get Notifications
				case 'get_notifications' :

					// Post number chars of items summary from App client
					$numchars			= Input::get('numchars');

					// Post number chars of original items summary from App client
					$original_numchars	= Input::get('original_numchars');

					// Get user ID from App client
					$id					= Input::get('id');

					// Retrieve notifications
					$check_null			= Notification::get()->first();

					// Determine notifications exist
					if(is_null($check_null)) {

						// Build Json format
						return '{ "status" : "1", "data" :[]}';

					} else {

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
							$sender						= User::where('id', $notifications[$key]['sender_id'])->first();

							// Determine user set portrait
							if($sender->portrait){

								// Get user portrait
								$notifications[$key]['portrait']	= route('home') . '/' . 'portrait/' . $sender->portrait;
							} else {

								// Return null
								$notifications[$key]['portrait']	= null;
							}

							// Determine user set nuckname
							if($sender->nickname) {

								// Get user nickname
								$notifications[$key]['nickname'] 	= $sender->nickname;
							} else {

								// Return null
								$notifications[$key]['nickname'] 	= null;
							}

							// Determine category
							if($notifications[$key]['category'] == 6) {

								// Comment
								$post										= ForumPost::where('id', $notifications[$key]['post_id'])->first();

								// Retrieve comment
								$comment									= ForumComments::where('id', $notifications[$key]['comment_id'])->first();

								// Add comment content summary to content key
								$notifications[$key]['content']				= getplaintextintrofromhtml($comment->content, $numchars);

								// Add post content summary to original_content key
								$notifications[$key]['original_content']	= getplaintextintrofromhtml($post->content, $numchars);

							} else {

								// Reply
								$comment									= ForumComments::where('id', $notifications[$key]['comment_id'])->first();

								// Retrieve reply
								$reply										= ForumReply::where('id', $notifications[$key]['reply_id'])->first();

								// Add reply content summary to content key
								$notifications[$key]['content']				= getplaintextintrofromhtml($reply->content, $numchars);

								// Add post content summary to original_content key
								$notifications[$key]['original_content']	= getplaintextintrofromhtml($comment->content, $original_numchars);
							}
						}

						Notification::where('receiver_id', $id)->whereIn('category', array(6, 7))->update(array('status' => 1));

						// Build Json format
						return '{ "status" : "1", "data" : ' . json_encode($notifications) . '}';
					}
				break;

				// Forum Get Reply
				case 'forum_getreply' :

					// Post forum post ID from App client
					$post_id		= Input::get('postid');

					// Post comment ID from App client
					$comment_id		= Input::get('commentid');

					// Retrieve comment
					$comment		= ForumComments::where('id', $comment_id)->first();

					// Retrieve comment user
					$comment_author	= User::where('id', $comment->user_id)->first();

					// Retrieve reply
					$replies = ForumReply::where('comments_id', $comment_id)
									->select('id', 'user_id', 'content', 'created_at')
									->orderBy('created_at' , 'asc')
									->get()
									->toArray();

					// Build Data Array
					$data = array(

						// Post user portrait
						'user_portrait'		=> route('home') . '/' . 'portrait/' . $comment_author->portrait,

						// Comment user sex
						'user_sex'			=> $comment_author->sex,

						// Comment user nickname
						'user_nickname'		=> $comment_author->nickname,

						// Comment user ID
						'user_id'			=> $comment_author->id,

						// Comment ID
						'id'				=> $comment->id,

						// Comment title
						'title'				=> $comment->title,

						// Comment created date
						'created_at'		=> $comment->created_at->toDateTimeString(),

						// Comment content (removing contents html tags except image and text string)
						'content'			=> strip_tags($comment->content, '<img>'),

						// Post comments reply (array format and include reply)
						'comment_reply'		=> $replies
					);

					// Build Json format
					return '{ "status" : "1", "data" : ' . json_encode($data) . '}';
				break;

				// Get user posts
				case 'get_userposts' :

					// Post user ID from App client
					$user_id	= Input::get('id');

					// Retrieve user
					$user		= User::find($user_id);

					// Get all post of this user
					$posts		= ForumPost::where('user_id', $user_id)
									->orderBy('created_at', 'desc')
									->select('id', 'title', 'created_at')
									->get()
									->toArray();

					// Build format
					foreach ($posts as $key => $value) {

						// Query how many comment of this post
						$posts[$key]['comments_count'] = ForumComments::where('post_id', $posts[$key]['id'])->count();
					}

					// Build format
					$data = array(
							'portrait'		=> route('home') . '/' . 'portrait/' . $user->portrait,
							'nickname'		=> $user->nickname,
							'posts_count'	=> ForumPost::where('user_id', $user_id)->count(),
							'posts'			=> $posts
						);

					// Build Json format
					return '{ "status" : "1", "data" : ' . json_encode($data) . '}';
				break;

				// Delete forum post
				case 'delete_userpost';

					// Get post ID in forum for delete
					$postId		= Input::get('postid');

					// Retrieve post
					$forumPost	= ForumPost::where('id', $postId)->first();

					// Using expression get all picture attachments (Only with pictures stored on this server.)
					preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $forumPost->content, $match );

					// Construct picture attachments list
					$srcArray 	= array_pop($match);

					// This post have picture attachments
					if(!empty( $srcArray ))
					{
						// Foreach picture attachments list array
						foreach($srcArray as $key => $field){

							// Convert to correct real storage path
							$srcArray[$key]	= str_replace(route('home'), '', $srcArray[$key]);

							// Destory upload picture attachments in this post
							File::delete(public_path($srcArray[$key]));
						}

						// Delete post in forum
						if($forumPost->delete()) {
							return Response::json(
								array(
									'status'	=> 1
								)
							);
						} else {
							return Response::json(
								array(
									'status'	=> 0
								)
							);
						}
					} else {

						// Delete post in forum
						if($forumPost->delete()) {
							return Response::json(
								array(
									'status'	=> 1
								)
							);
						} else {
							return Response::json(
								array(
									'status'	=> 0
								)
							);
						}

					}
				break;

				// Get User Portrait
				case 'get_portrait' :

					// Retrieve
					$user = User::find(Input::get('id'));

					if($user) {
						// User exist
						return Response::json(
							array(
								'status'	=> 1,
								'nickname'	=> e($user->nickname),
								'portrait'	=> route('home') . '/' . 'portrait/' . $user->portrait
							)
						);
					} else {
						// User not exist
						return Response::json(
							array(
								'status' 	=> 0
							)
						);
					}

				break;

				// Open university
				case 'open_university' :

					// Retrieve opened and pending university
					$universities 		= University::whereIn('status', array(1, 2))->select('id', 'university', 'open_at', 'status')->orderBy('status', 'desc')->get()->toArray();

					// Format pending time
					foreach ($universities as $key => $value) {
						$universities[$key]['open_at'] = e(date('m月d日', strtotime($universities[$key]['open_at'])));
					}

					array_push($universities, array(
						'id'			=> 0,
						'university'	=> '其他',
						'open_at'		=> 'none',
						'status'		=> 2
					));

					// Build Json format
					return '{ "status" : "1", "data" : ' . json_encode($universities) . '}';
				break;

				// Get forum unread notifications
				case 'get_forumunread' :

					// Get user ID from App client and retrieve User
					$user 		   = User::find(Input::get('id'));
					$notifications = Notification::where('receiver_id', $user->id)
												->whereIn('category', array(6, 7))
												->where('status', 0)
												->count();
					if(is_null($notifications)) {

						// No unread notifications, build Json format
						return '{ "status" : "1", "num" : "0" }';
					} else {

						// Build Json format
						return '{ "status" : "1", "num" : ' . $notifications. '}';
					}
				break;

				// Get open articles
				case 'get_openarticles' :

					// Retrieve all open articles
					$articles = Article::where('status', 1)->select('id', 'status', 'title', 'thumbnails', 'slug')->take(3)->get()->toArray();

					// Add thumbnails images and article url to array
					foreach ($articles as $key => $value) {
						$articles[$key]['title']		= Str::limit($articles[$key]['title'], 15);
						$articles[$key]['thumbnails']	= URL::to('/upload/thumbnails') . '/' . $articles[$key]['thumbnails'];
						$articles[$key]['url']			= URL::to('/article') . '/' . $articles[$key]['slug'];
					}

					// Build Json format
					return '{ "status" : "1", "data" : ' . json_encode($articles) . '}';
				break;

				// Recovery password
				case 'recovery_password' :

					// Retrieve user
					if($user = User::where('phone', Input::get('phone'))->first()){

						// Update user password
						$user->password = md5(Input::get('password'));

						// Update successful
						if($user->save()) {

							// Update user password in easemob
							$easemob			= getEasemob();

							// newRequest or newJsonRequest returns a Request object
							$regChat			= cURL::newJsonRequest('put', 'https://a1.easemob.com/jinglingkj/pinai/users/' . $user->id . '/password', ['newpassword' => $user->password])
								->setHeader('content-type', 'application/json')
								->setHeader('Accept', 'json')
								->setHeader('Authorization', 'Bearer '.$easemob->token)
								->setOptions([CURLOPT_VERBOSE => true])
								->send();

							return Response::json(
								array(
									'status'	=> 1
								)
							);
						} else {

							// Update fail
							return Response::json(
								array(
									'status'	=> 0
								)
							);
						}
					} else {
						// Can't find user
						return Response::json(
							array(
								'status'	=> 2
							)
						);
					}
				break;

				// Support
				case 'support' :
					$support			= new Support;
					$support->user_id 	= Input::get('id');
					$support->content	= Input::get('content');
					if($support->save()) {
						return Response::json(
							array(
								'status' 		=> 1
							)
						);
					} else {
						return Response::json(
							array(
								'status' 		=> 0
							)
						);
					}
				break;

				// Admin notifications
				case 'system_notifications' :
					// Post user ID from App client
					$id				= Input::get('id');

					// Retrieve all system notifications
					$notifications	= Notification::where('receiver_id', $id)
										->whereIn('category', array(8, 9))
										->orderBy('created_at' , 'desc')
										->select('id', 'sender_id', 'created_at')
										->where('status', 0)
										->get()
										->toArray();

					$friend_notifications = Notification::where('receiver_id', $id)
										->whereIn('category', array(1, 2, 3))
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
						$notifications_content				= NotificationsContent::where('notifications_id', $notifications[$key]['id'])->first();
						$notifications[$key]['content']		= $notifications_content->content;
						$notifications[$key]['created_at']	= date('m-d G:i', strtotime($notifications_content->created_at));

					}

					foreach ($friend_notifications as $key => $value) {
						switch ($friend_notifications[$key]['category']) {
							case '1' :
								$sender_user							= User::find($friend_notifications[$key]['sender_id']);
								$like									= Like::where('sender_id', $friend_notifications[$key]['sender_id'])->where('receiver_id', $friend_notifications[$key]['receiver_id'])->first();
								$friend_notifications[$key]['content']	= $sender_user->nickname . '追你了，快去看看吧';
								$friend_notifications[$key]['nickname']	= $sender_user->nickname;
								$friend_notifications[$key]['portrait']	= route('home') . '/' . 'portrait/' . $sender_user->portrait;
								$friend_notifications[$key]['answer']	= $like->answer;
								$friend_notifications[$key]['from']		= $friend_notifications[$key]['sender_id'];
							break;

							case '2' :
								$sender_user							= User::find($friend_notifications[$key]['sender_id']);
								$like									= Like::where('sender_id', $friend_notifications[$key]['sender_id'])->where('receiver_id', $friend_notifications[$key]['receiver_id'])->first();
								$friend_notifications[$key]['content']	= $sender_user->nickname . '再次追你了，快去看看吧';
								$friend_notifications[$key]['nickname']	= e($sender_user->nickname);
								$friend_notifications[$key]['portrait']	= route('home') . '/' . 'portrait/' . $sender_user->portrait;
								$friend_notifications[$key]['answer']	= e($like->answer);
								$friend_notifications[$key]['from']		= $friend_notifications[$key]['sender_id'];
							break;
						}
					}

					// Build notifications
					foreach ($accept_notifications as $key => $value) {
						$sender_user							= User::find($accept_notifications[$key]['sender_id']);
						$accept									= Like::where('sender_id', $accept_notifications[$key]['sender_id'])->where('receiver_id', $accept_notifications[$key]['receiver_id'])->first();
						$accept_notifications[$key]['nickname']	= $sender_user->nickname;
						$accept_notifications[$key]['portrait']	= route('home') . '/' . 'portrait/' . $sender_user->portrait;
						$accept_notifications[$key]['from']		= $accept_notifications[$key]['sender_id'];
					}

					$data = array(
							'system_notifications'	=> $notifications,
							'friend_notifications'	=> $friend_notifications,
							'accept_notifications'	=> $accept_notifications
						);

					// Mark read for this user
					Notification::where('receiver_id', $id)->update(array('status' => 1));

					// Build Json format
					return '{ "status" : "1", "data" : ' . json_encode($data) . '}';
				break;

				// Signout
				case 'signout' :

					// USer ID
					$id = Input::get('id');

					if(User::find($id))
					{
						return Response::json(
							array(
								'status' 		=> 1
							)
						);
					} else {
						return Response::json(
							array(
								'status' 		=> 0
							)
						);
					}

				break;
			}
		} else {
			return Response::json(
				array(
					'status' 		=> 0
				)
			);
		}
	}
}