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

/**
 * Status Code Explanation
 *
 * from = 0 (default) 	Signup from Website
 * from = 1 			Signup from Android
 * from = 3 			Signup from iOS Client
 * from = 4 			Add user by administrator
 *
 */

class AndroidController extends BaseController
{

	/**
	 * View: Debug
	 * @return Response
	 */
	public function getDebug()
	{
		return View::make('android.index')->with(compact('test'));
	}

	/**
	 * Main Android API
	 * @return Json
	 */
	public function postAndroid()
	{

		$token  = Input::get('token');
		$action = Input::get('action');

		if($token == 'jciy9ldJ') // Define token
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
								'password'	=> $user->password
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
						$user->from			= 1; // Signup from Android
						$user->activated_at	= date('Y-m-d H:m:s');
						$user->password		= md5(Input::get('password'));
						if ($user->save()) {
							$profile			= new Profile;
							$profile->user_id	= $user->id;
							$profile->save();
							// Add user success
							// Chat Register

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
						'sex'           => Input::get('sex'),
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
						'sex'			=> 'required',
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
						'sex.required'				=> '请选择性别',
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
						if($portrait = 'null')
						{
							$user->portrait 	= $oldPortrait;  // User not update avatar
						} else{  // User update avatar
							$portraitPath		= public_path('portrait/');
							$user->portrait     = 'android/'.$portrait; // Save file name to database
						}
						if($user->sex == NULL)
						{
							$user->sex          = Input::get('sex');
						}
						if($user->born_year == NULL)
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
							if($portrait != 'null') // User update avatar
							{
								$oldAndroidPortrait = strpos($oldPortrait, 'android');
								if($oldAndroidPortrait === false) // Must use ===
								{
									File::delete($portraitPath.$oldPortrait); // Delete old poritait
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
								'status' 		=> 0
							)
						);
					}
				break;

				// Members

				case 'members_index' :
					$last_id  = Input::get('lastid'); // Post last user id from Android client
					$per_page = Input::get('perpage'); // Post count per query from Android client
					if($last_id) // If Android have post last user id
					{
						$users = User::whereNotNull('portrait') // Skip none portrait user
						->orderBy('id', 'desc')
						->select('id', 'nickname', 'school', 'sex', 'portrait')
						->where('id', '<', $last_id)
						->take($per_page)
						->get()
						->toArray();
						// Replace receiver ID to receiver portrait
						foreach($users as $key => $field){
							$users[$key]['portrait']	= route('home').'/'.'portrait/'.$users[$key]['portrait']; // Convert to real storage path
						}
						$users = json_encode($users); // Encode likes array to json format
						if($users) // If get query success
						{
							return '{ "status" : "1", "data" : '.$users.'}'; // Build Json format
						} else { // Get query fail
							return Response::json(
								array(
									'status' 		=> 0
								)
							);
						}
					} else { // First get data from Android client
						$lastRecord = User::orderBy('id', 'desc')->first()->id; // Query last user id in database
						$users      = User::whereNotNull('portrait') // Skip none portrait user
						->orderBy('id', 'desc')
						->select('id', 'nickname', 'school', 'sex', 'portrait')
						->where('id', '<=', $lastRecord)
						->take($per_page)
						->get()
						->toArray();
						// Replace receiver ID to receiver portrait
						foreach($users as $key => $field){
							$users[$key]['portrait']	= route('home').'/'.'portrait/'.$users[$key]['portrait']; // Convert to real storage path
						}
						$users = json_encode($users); // Encode likes array to json format
						if($users)
						{
							return '{ "status" : "1", "data" : '.$users.'}';
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
						'phone'   => Input::get('senderid'), // Current signin user phone number on android
						'user_id' => Input::get('userid'), // Which user want to see
					);
					if ($info)
					{
						$sender_id	= Input::get('senderid'); // Sender user ID
						$user_id	= Input::get('userid');
						$data		= User::where('id', $user_id)->first();
						$profile	= Profile::where('user_id', $user_id)->first();
						$like		= Like::where('sender_id', $sender_id)->where('receiver_id', $user_id)->first();
						if($like)
						{
							$likeCount = $like->count;
						} else {
							$likeCount = 0;
						}
						$constellationInfo = getConstellation($profile->constellation); // Get user's constellation
						$tag_str           = explode(',', substr($profile->tag_str, 1)); // Get user's tag
						return Response::json(
							array(
								'status'		=> 1,
								'sex'			=> $data->sex,
								'bio'			=> $data->bio,
								'nickname'		=> $data->nickname,
								'born_year'		=> $data->born_year,
								'school'		=> $data->school,
								'portrait'		=> route('home').'/'.'portrait/'.$data->portrait,
								'constellation'	=> $constellationInfo['name'],
								'tag_str'		=> $tag_str,
								'hobbies'		=> $profile->hobbies,
								'grade'			=> $profile->grade,
								'question'		=> $profile->question,
								'self_intro'	=> $profile->self_intro,
								'like'			=> $likeCount,
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
						$constellationInfo	= getConstellation($profile->constellation); // Get user's constellation
						$tag_str			= explode(',', substr($profile->tag_str, 1)); // Get user's tag
						return Response::json(
							array(
								'status'		=> 1,
								'sex'			=> $user->sex,
								'bio'			=> $user->bio,
								'nickname'		=> $user->nickname,
								'born_year'		=> $user->born_year,
								'school'		=> $user->school,
								'portrait'		=> route('home').'/'.'portrait/'.$user->portrait,
								'constellation'	=> $constellationInfo['name'],
								'tag_str'		=> $tag_str,
								'hobbies'		=> $profile->hobbies,
								'grade'			=> $profile->grade,
								'question'		=> $profile->question,
								'self_intro'	=> $profile->self_intro,
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

				// Like

				case 'like' :
					// Get all form data.
					$data	= Input::all();
					// Create validation rules
					$rules	= array(
						'id'			=> 'required',
						'receiverid'	=> 'required',
						'answer'		=> 'required|min:3',
					);
					// Custom validation message
					$messages = array(
						'answer.required'     => '请回答爱情考验问题。',
						'answer.min'          => '至少要写:min个字哦。',
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
							if($have_like) // This user already sent like
							{
								$have_like->answer	= Input::get('answer');
								$have_like->count	= $have_like->count + 1;
								$user->points		= $user->points - 1;
								if($have_like->save() && $user->save())
								{
									Notification(2, $user->id, $receiver_id); // Some user re-liked you
									$easemob		= getEasemob();
									// Push notifications to App client
									cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
											'target_type'	=> 'users',
											'target'		=> [$receiver_id],
											'msg'			=> ['type' => 'cmd', 'action' => '2'],
											'from'			=> $user->id,
											'ext'			=> [
																	'content'	=> $user->nickname.'又追你了，快去查看一下吧',
																	'id'		=> Input::get('id'),
																	'portrait'	=> route('home').'/'.'portrait/'.$user->portrait,
																	'nickname'	=> $user->nickname,
																	'answer'	=> Input::get('answer')
																]
										])
											->setHeader('content-type', 'application/json')
											->setHeader('Accept', 'json')
											->setHeader('Authorization', 'Bearer '.$easemob->token)
											->setOptions([CURLOPT_VERBOSE => true])
											->send();
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
									Notification(1, $user->id, $receiver_id); // Some user first like you
									$easemob		= getEasemob();
									// Push notifications to App client
									cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
											'target_type'	=> 'users',
											'target'		=> [$receiver_id],
											'msg'			=> ['type' => 'cmd', 'action' => '1'],
											'from'			=> $user->id,
											'ext'			=> [
																	'content'	=> $user->nickname.'追你了，快去查看一下吧',
																	'id'		=> Input::get('id'),
																	'portrait'	=> route('home').'/'.'portrait/'.$user->portrait,
																	'nickname'	=> $user->nickname,
																	'answer'	=> Input::get('answer')
																]
										])
											->setHeader('content-type', 'application/json')
											->setHeader('Accept', 'json')
											->setHeader('Authorization', 'Bearer '.$easemob->token)
											->setOptions([CURLOPT_VERBOSE => true])
											->send();
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
					$last_id	= Input::get('lastid'); // Post last user id from Android client
					$per_page	= Input::get('perpage'); // Post count per query from Android client
					$user_id	= Input::get('id'); // Get user id
					if($last_id) // If Android have post last user id
					{
						$allLike    = Like::where('sender_id', $user_id) // Query all user liked users
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
							$likes[$key]['id']			= $likes[$key]['portrait']; // Receiver ID
							$likes[$key]['portrait']	= route('home').'/'.'portrait/'.User::where('id', $likes[$key]['portrait'])->first()->portrait; // Receiver avatar real storage path
							$likes[$key]['school']		= User::where('id', $likes[$key]['id'])->first()->school; // Receiver school
							$likes[$key]['name']		= User::where('id', $likes[$key]['id'])->first()->nickname; // Receiver ID
							// Convert how long liked
							$Date_1						= date("Y-m-d"); // Current date and time
							$Date_2						= date("Y-m-d",strtotime($likes[$key]['created_at']));
							$d1							= strtotime($Date_1);
							$d2							= strtotime($Date_2);
							$Days						= round(($d1-$d2)/3600/24); // Calculate liked time
							$likes[$key]['created_at']	= $Days;
						}
						$like = json_encode($likes); // Encode likes array to json format
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
					} else { // First get data from Android client
						$lastRecord = Like::where('sender_id', $user_id)->orderBy('id', 'desc')->first()->id; // Query last like id in database
						$allLike    = Like::where('sender_id', $user_id) // Query all user liked users
							->orderBy('id', 'desc')
							->select('receiver_id', 'status', 'created_at', 'count')
							->where('id', '<=', $lastRecord)
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
							$likes[$key]['id']			= $likes[$key]['portrait']; // Receiver ID
							$likes[$key]['portrait']	= route('home').'/'.'portrait/'.User::where('id', $likes[$key]['portrait'])->first()->portrait; // Receiver avatar real storage path
							$likes[$key]['school']		= User::where('id', $likes[$key]['id'])->first()->school; // Receiver school
							$likes[$key]['name']		= User::where('id', $likes[$key]['id'])->first()->nickname; // Receiver ID
							// Convert how long liked
							$Date_1						= date("Y-m-d"); // Current date and time
							$Date_2						= date("Y-m-d",strtotime($likes[$key]['created_at']));
							$d1							= strtotime($Date_1);
							$d2							= strtotime($Date_2);
							$Days						= round(($d1-$d2)/3600/24); // Calculate liked time
							$likes[$key]['created_at']	= $Days;
						}
						$like = json_encode($likes); // Encode likes array to json format
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

				// Inbox

				case 'inbox' :
					$last_id	= Input::get('lastid'); // Post last user id from Android client
					$per_page	= Input::get('perpage'); // Post count per query from Android client
					$user_id	= Input::get('id'); // Get user id
					if($last_id != 'null') // If Android have post last user id
					{
						$allLike	= Like::where('receiver_id', $user_id) // Query all user liked users
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
							$likes[$key]['id']			= $likes[$key]['portrait']; // Receiver ID
							$likes[$key]['portrait']	= route('home').'/'.'portrait/'.User::where('id', $likes[$key]['portrait'])->first()->portrait; // Receiver avatar real storage path
							$likes[$key]['school']		= User::where('id', $likes[$key]['id'])->first()->school; // Receiver school
							$likes[$key]['name']		= User::where('id', $likes[$key]['id'])->first()->nickname; // Receiver ID
							// Convert how long liked
							$Date_1						= date("Y-m-d"); // Current date and time
							$Date_2						= date("Y-m-d",strtotime($likes[$key]['created_at']));
							$d1							= strtotime($Date_1);
							$d2							= strtotime($Date_2);
							$Days						= round(($d1-$d2)/3600/24); // Calculate liked time
							$likes[$key]['created_at']	= $Days;
						}
						$like = json_encode($likes); // Encode likes array to json format
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
					} else { // First get data from Android client
						$lastRecord = Like::where('receiver_id', $user_id)->orderBy('id', 'desc')->first()->id; // Query last like id in database
						$allLike    = Like::where('receiver_id', $user_id) // Query all user liked users
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
							$likes[$key]['id']			= $likes[$key]['portrait']; // Receiver ID
							$likes[$key]['portrait']	= route('home').'/'.'portrait/'.User::where('id', $likes[$key]['portrait'])->first()->portrait; // Receiver avatar real storage path
							$likes[$key]['school']		= User::where('id', $likes[$key]['id'])->first()->school; // Receiver school
							$likes[$key]['name']		= User::where('id', $likes[$key]['id'])->first()->nickname; // Receiver ID
							// Convert how long liked
							$Date_1						= date("Y-m-d"); // Current date and time
							$Date_2						= date("Y-m-d",strtotime($likes[$key]['created_at']));
							$d1							= strtotime($Date_1);
							$d2							= strtotime($Date_2);
							$Days						= round(($d1-$d2)/3600/24); // Calculate liked time
							$likes[$key]['created_at']	= $Days;
						}
						$like = json_encode($likes); // Encode likes array to json format
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
					$id				= Input::get('senderid'); // Get sender ID from client
					$sender 		= User::where('id', $id)->first();
					$receiver_id	= Input::get('receiverid'); // Get receiver ID from client
					$receiver		= User::where('id', $receiver_id)->first();
					$like			= Like::where('sender_id', $id)->where('receiver_id', $receiver_id)->first();
					$like->status	= 1; // Receiver accept like

					$easemob		= getEasemob();
					// Add friend relationship in chat system and start chat
					cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users/'.$receiver_id.'/contacts/users/'.$id)
							->setHeader('content-type', 'application/json')
							->setHeader('Accept', 'json')
							->setHeader('Authorization', 'Bearer '.$easemob->token)
							->setOptions([CURLOPT_VERBOSE => true])
							->send();
					cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users/'.$id.'/contacts/users/'.$receiver_id)
							->setHeader('content-type', 'application/json')
							->setHeader('Accept', 'json')
							->setHeader('Authorization', 'Bearer '.$easemob->token)
							->setOptions([CURLOPT_VERBOSE => true])
							->send();
					if($like->save())
					{
						// Save notification in database for website
						$notification	= Notification(3, $receiver_id, $id); // Some user accept you like
						$easemob		= getEasemob();
						// Push notifications to App client
						cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
								'target_type'	=> 'users',
								'target'		=> [$id],
								'msg'			=> ['type' => 'cmd', 'action' => '3'],
								'from'			=> $receiver_id,
								'ext'			=> [
														'content'	=> $receiver->nickname.'接受了你的邀请',
														'id'		=> $receiver_id,
														'portrait'	=> route('home').'/'.'portrait/'.$receiver->portrait,
														'nickname'	=> $receiver->nickname
													]
							])
								->setHeader('content-type', 'application/json')
								->setHeader('Accept', 'json')
								->setHeader('Authorization', 'Bearer '.$easemob->token)
								->setOptions([CURLOPT_VERBOSE => true])
								->send();
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
					$id				= Input::get('senderid'); // Get sender ID from client
					$receiver_id	= Input::get('receiverid'); // Get receiver ID from client
					$receiver		= User::where('id', $receiver_id)->first();
					$like			= Like::where('sender_id', $id)->where('receiver_id', $receiver_id)->first();
					$like->status	= 2; // Receiver reject user, remove friend relationship in chat system
					if($like->save())
					{
						// Save notification in database for website
						$notification	= Notification(4, $receiver_id, $id); // Some user reject you like
						$easemob		= getEasemob();
						// Push notifications to App client
						cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
								'target_type'	=> 'users',
								'target'		=> [$id],
								'msg'			=> ['type' => 'cmd', 'action' => '4'],
								'from'			=> $receiver_id,
								'ext'			=> [
														'content'	=> $receiver->nickname.'拒绝了你的邀请',
														'id'		=> $receiver_id,
														'portrait'	=> route('home').'/'.'portrait/'.$receiver->portrait,
														'nickname'	=> $receiver->nickname
													]
							])
								->setHeader('content-type', 'application/json')
								->setHeader('Accept', 'json')
								->setHeader('Authorization', 'Bearer '.$easemob->token)
								->setOptions([CURLOPT_VERBOSE => true])
								->send();
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
					$like->status	= 3; // Receiver block user, remove friend relationship in chat system

					$easemob		= getEasemob();
					$notification	= Notification(5, $id, $receiver_id); // Some user blocked you
					// Remove friend relationship in chat system
					cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users/'.$receiver_id.'/contacts/users/'.$id)
							->setHeader('content-type', 'application/json')
							->setHeader('Accept', 'json')
							->setHeader('Authorization', 'Bearer '.$easemob->token)
							->setOptions([CURLOPT_VERBOSE => true])
							->setOptions([CURLOPT_CUSTOMREQUEST => 'DELETE'])
							->send();
					cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users/'.$id.'/contacts/users/'.$receiver_id)
							->setHeader('content-type', 'application/json')
							->setHeader('Accept', 'json')
							->setHeader('Authorization', 'Bearer '.$easemob->token)
							->setOptions([CURLOPT_VERBOSE => true])
							->setOptions([CURLOPT_CUSTOMREQUEST => 'DELETE'])
							->send();
					// Push notifications to App client
					cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
							'target_type'	=> 'users',
							'target'		=> [$id],
							'msg'			=> ['type' => 'cmd', 'action' => '5'],
							'from'			=> $receiver_id,
							'ext'			=> ['content' => User::where('id', $receiver_id)->first()->nickname.'把你加入了黑名单', 'id' => $notification->id]
						])
							->setHeader('content-type', 'application/json')
							->setHeader('Accept', 'json')
							->setHeader('Authorization', 'Bearer '.$easemob->token)
							->setOptions([CURLOPT_VERBOSE => true])
							->send();
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
						$renew	= Input::get('renew');
						$today	= Carbon::today();
						$user	= Profile::where('user_id', Input::get('id'))->first();
						$points	= User::where('id', Input::get('id'))->first();

						if($user->renew_at == '0000-00-00 00:00:00'){ // First renew
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
						} else if ($today >= $user->renew_at){ // You haven't renew today
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
						} else { // You have renew today
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

				// Get Username

				case 'getnickname' :

					// Get query ID from App client
					$id		= Input::get('id');

					// Get sender user data
					$sender = Like::where('receiver_id', $id)
								->where('status', 1)
								->select('sender_id')
								->get()
								->toArray();

					foreach($sender as $key => $field){

							// Sender nickname
							$sender[$key]['nickname']	= User::where('id', $sender[$key]['sender_id'])->first()->nickname;

							// Sender portrait
							$sender[$key]['portrait']	= route('home').'/'.'portrait/'.User::where('id', $sender[$key]['sender_id'])->first()->portrait;
						}

					// Convert array to json format
					$sender = json_encode($sender);

					// Query successful
					if($sender)
					{
						return '{ "status" : "1", "data" : ' . $sender . '}';
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

					// Retrive user
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
						$user->portrait     = 'android/'.$portrait;

						if ($user->save()) {
							// Update success
							$oldAndroidPortrait = strpos($oldPortrait, 'android');
							if($oldAndroidPortrait === false) // Must use ===
							{
								File::delete($portraitPath.$oldPortrait); // Delete old poritait
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

				/*
				|--------------------------------------------------------------------------
				| Forum Section
				|--------------------------------------------------------------------------
				|
				*/

				// Get Forum Category

				case 'forum_getcat' :
					// Post last user ID from Android client
					$last_id	= Input::get('lastid');

					// Post count per query from Android client
					$per_page	= Input::get('perpage');

					// Post category ID from Android client
					$cat_id		= Input::get('catid');

					// Post number chars of post summary from Android client
					$numchars	= Input::get('numchars');

					// If Android have post last user id
					if($last_id != 'null') {

						// Query all items from database
						$items	= ForumPost::where('category_id', $cat_id)
									->orderBy('created_at' , 'desc')
									->where('id', '<', $last_id)
									->select('id', 'user_id', 'title', 'content', 'created_at')
									->take('10')
									->get()
									->toArray();

						// Replace receiver ID to receiver portrait
						foreach($items as $key => $field){

							// Retrive user
							$post_user						= User::where('id', $items[$key]['user_id'])->first();

							// Get post user portrait real storage path and user porirait key to array
							$items[$key]['portrait']		= route('home') . '/' . 'portrait/' . $post_user->portrait;

							// Get post user sex (M, F or null) and add user sex key to array
							$items[$key]['sex']				= $post_user->sex;

							// Count how many comments of this post and add comments_count key to array
							$items[$key]['comments_count']	= ForumComments::where('post_id', $items[$key]['id'])->count();

							// Get post user portrait and add portrait key to array
							$items[$key]['nickname']		= $post_user->nickname;

							// Using expression get all picture attachments (Only with pictures stored on this server.)
							preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $items[$key]['content'], $match );

							// Construct picture attachments list and add thumbnails (array format) to array
							$items[$key]['thumbnails']		= join(',', array_pop($match));

							// Get plain text from post content HTML code and replace to content value in array
							$items[$key]['content']			= getplaintextintrofromhtml($items[$key]['content'], $numchars);
						}

						// Build Json format
						return '{ "status" : "1", "data" : ' . json_encode($items) . '}';
					} else { // First get data from Android client

						// Query last user id in database
						$lastRecord = ForumPost::orderBy('id', 'desc')->first()->id;

						// Query all items from database
						$items	= ForumPost::where('category_id', $cat_id)
									->orderBy('created_at' , 'desc')
									->where('id', '<=', $lastRecord)
									->select('id', 'user_id', 'title', 'content', 'created_at')
									->take('10')
									->get()
									->toArray();

						// Replace receiver ID to receiver portrait
						foreach($items as $key => $field){

							// Retrive user
							$post_user						= User::where('id', $items[$key]['user_id'])->first();

							// Get post user portrait real storage path and user porirait key to array
							$items[$key]['portrait']		= route('home') . '/' . 'portrait/' . $post_user->portrait;

							// Get post user sex (M, F or null) and add user sex key to array
							$items[$key]['sex']				= $post_user->sex;

							// Count how many comments of this post and add comments_count key to array
							$items[$key]['comments_count']	= ForumComments::where('post_id', $items[$key]['id'])->count();

							// Get post user portrait and add portrait key to array
							$items[$key]['nickname']		= $post_user->nickname;

							// Using expression get all picture attachments (Only with pictures stored on this server.)
							preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $items[$key]['content'], $match );

							// Construct picture attachments list and add thumbnails (array format) to array
							$items[$key]['thumbnails']		= join(',', array_pop($match));

							// Get plain text from post content HTML code and replace to content value in array
							$items[$key]['content']			= getplaintextintrofromhtml($items[$key]['content'], $numchars);
						}

						// Build Json format
						return '{ "status" : "1", "data" : ' . json_encode($items) . '}';
					}

				break;

				// Forum Get to Show Post
				case 'forum_getpost' :
					$postid		= Input::get('postid');

					$lastid		= Input::get('lastid');

					$perpage	= Input::get('perpage', 10);

					// If Android have post last user id
					if($lastid == NULL) {

						// First get data from Android client

						// Retrive post data
						$post		= ForumPost::where('id', $postid)->first();

						// Retrive user data of this post
						$author		= User::where('id', $post->user_id)->first();

						// Get last record from database
						$lastRecord	= ForumComments::orderBy('id', 'desc')->first()->id;

						// Query all comments of this post
						$comments	= ForumComments::where('post_id', $postid)
											->orderBy('created_at' , 'asc')
											->where('id', '<=', $lastRecord)
											->select('id', 'user_id', 'content', 'created_at')
											->take($perpage)
											->get()
											->toArray();
						// Build comments array and include reply information
						foreach($comments as $key => $field) {

							// Retrive comments user
							$comments_user						= User::where('id', $comments[$key]['user_id'])->first();

							// Comments user ID
							$comments[$key]['user_id']			= $comments_user->id;

							// Removing contents html tags except image and text string
							$comments[$key]['content']			= strip_tags($comments[$key]['content'], '<img>');
							// Comments user portrait
							$comments[$key]['user_portrait']	= route('home') . '/' . 'portrait/' . $comments_user->portrait;

							// Comments user sex
							$comments[$key]['user_sex']			= $comments_user->sex;

							// Comments user nickname
							$comments[$key]['user_nickname']	= $comments_user->nickname;

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

								// Retrive reply user
								$reply_user					= User::where('id', $replies[$keys]['user_id'])->first();

								// Reply user sex
								$replies[$keys]['sex']		= $reply_user->sex;

								// Reply user portrait
								$replies[$keys]['portrait']	= route('home') . '/' . 'portrait/' . $reply_user->portrait;

							}

							// Add comments replies array to post comments_reply array
							$comments[$key]['comment_reply'] = $replies;

						}

						// Build Data Array
						$data = array(
							'portrait'		=> route('home') . '/' . 'portrait/' . $author->portrait, // Post user portrait
							'sex'			=> $author->sex, // Post user sex
							'nickname'		=> $author->nickname, // Post user nickname
							'user_id'		=> $author->id, // Post user ID
							'comment_count'	=> ForumComments::where('post_id', $postid)->get()->count(), // Post comments count
							'created_at'	=> $post->created_at->toDateTimeString(), // Post created date
							'content'		=> strip_tags($post->content, '<img>'), // Post content (removing contents html tags except image and text string)
							'comments'		=> $comments // Post comments (array format and include reply)

						);

						// Build Json format
						return '{ "status" : "1", "data" : ' . json_encode($data) . '}';

					} else {

						// Retrive post data
						$post		= ForumPost::where('id', $postid)->first();

						// Retrive user data of this post
						$author		= User::where('id', $post->user_id)->first();

						// Query all comments of this post
						$comments	= ForumComments::where('post_id', $postid)
											->orderBy('created_at' , 'desc')
											->where('id', '<', $lastid)
											->select('id', 'user_id', 'content', 'created_at')
											->take($perpage)
											->get()
											->toArray();
						// Build comments array and include reply information
						foreach($comments as $key => $field) {

							// Retrive comments user
							$comments_user						= User::where('id', $comments[$key]['user_id'])->first();

							// Comments user ID
							$comments[$key]['user_id']			= $comments_user->id;

							// Comments user portrait
							$comments[$key]['user_portrait']	= route('home') . '/' . 'portrait/' . $comments_user->portrait;

							// Comments user sex
							$comments[$key]['user_sex']			= $comments_user->sex;

							// Comments user nickname
							$comments[$key]['user_nickname']	= $comments_user->nickname;

							// Removing contents html tags except image and text string
							$comments[$key]['content']			= strip_tags($comments[$key]['content'], '<img>');
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

								// Retrive reply user
								$reply_user					= User::where('id', $replies[$keys]['user_id'])->first();

								// Reply user sex
								$replies[$keys]['sex']		= $reply_user->sex;

								// Reply user portrait
								$replies[$keys]['portrait']	= route('home') . '/' . 'portrait/' . $reply_user->portrait;

							}

							// Add comments replies array to post comments_reply array
							$comments[$key]['comment_reply'] = $replies;

						}

						// Build Data Array
						$data = array(
							'portrait'		=> route('home') . '/' . 'portrait/' . $author->portrait, // Post user portrait
							'sex'			=> $author->sex, // Post user sex
							'nickname'		=> $author->nickname, // Post user nickname
							'user_id'		=> $author->id, // Post user ID
							'comment_count'	=> ForumComments::where('post_id', $postid)->get()->count(), // Post comments count
							'created_at'	=> $post->created_at->toDateTimeString(), // Post created date
							'content'		=> strip_tags($post->content, '<img>'), // Post content (removing contents html tags except image and text string)
							'comments'		=> $comments // Post comments (array format and include reply)

						);

						// Build Json format
						return '{ "status" : "1", "data" : ' . json_encode($data) . '}';
					}

				break;

				// Forum Post Comments
				case 'forum_postcomment' :
					$user_id = Input::get('userid');
					$post_id = Input::get('postid');
					$content = Input::get('content');
					// Select post type
					if(Input::get('type') == 'comments') // Post comments
					{
						$comment			= new ForumComments;
						$comment->post_id	= $post_id;
						$comment->content	= $content;
						$comment->user_id	= $user_id;
						$comment->floor		= ForumComments::where('post_id', $post_id)->count() + 2; // Calculate this comment in which floor
						if($comment->save())
						{
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
					} else { // Post reply

						// Create comments reply
						$reply				= new ForumReply;
						$reply->content		= $content;
						$reply->reply_id	= Input::get('replyid');
						$reply->comments_id	= Input::get('commentid');
						$reply->user_id		= $user_id;
						$reply->floor		= ForumReply::where('comments_id', Input::get('commentid'))->count() + 1; // Calculate this reply in which floor
						if($reply->save())
						{
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
					$post->content		= Input::get('content');
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
					// Get all json format data from Android client and json decode data
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