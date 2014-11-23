<?php

class AndroidController extends BaseController
{
	/**
	 * Action: Signout
	 * @return Response
	 */
	public function getSignout()
	{
		Auth::logout();
		return Redirect::to('/');
	}

	/**
	 * View: Signin
	 * @return Response
	 */
	public function getDebug()
	{
		$lastRecord = User::orderBy('id', 'DESC')->first()->id;
		$users = User::whereNotNull('portrait')->orderBy('created_at', 'desc')->select('id', 'nickname', 'school', 'sex')->where('id', '<', $lastRecord)->take(5)->get()->toJson();
		return View::make('android.index')->with(compact('users'));
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
			if($action == 'login') // Signin
			{
				// Credentials
				$credentials = array(
					'email' => Input::get('phone'),
					'password' => md5(Input::get('password')
				));
				$phone_credentials = array(
					'phone' => Input::get('phone'),
					'password' => md5(Input::get('password')
				));
				if (Auth::attempt($credentials) || Auth::attempt($phone_credentials)) {
					// Signin success, redirect to the previous page that was blocked
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
			} else if($action == 'signup') // Signup
			{
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
					$user           = new User;
					$user->phone    = $phone;
					$user->password = md5(Input::get('password'));
					if ($user->save()) {
						// Redirect to a registration page, prompts user to activate
						// Signin success, redirect to the previous page that was blocked
						return Response::json(
							array(
								'status' 		=> 1
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
			} else if($action == 'complete') // Complete
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
					'sex'           => 'required',
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
					'sex.required'           => '请选择性别',
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
					$user                   = User::where('phone', Input::get('phone'))->orWhere('email', Input::get('phone'))->first();
					$oldPortrait			= $user->portrait;
					$user->nickname         = Input::get('nickname');

					// Protrait section
					$portrait               = Input::get('portrait');
					if($portrait != NULL) // User update avatar
					{
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
						if($portrait != NULL) // User update avatar
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
			} else if($action == 'members_index') // Members
			{
				$last_id  = Input::get('lastid'); // Post last user id from Android client
				$per_page = Input::get('perpage'); // Post count per query from Android client
				if($last_id) // If Android have post last user id
				{
					$users = User::whereNotNull('portrait') // Skip none portrait user
					->orderBy('id', 'desc')
					->select('id', 'nickname', 'school', 'sex')
					->where('id', '<', $last_id)
					->take($per_page)
					->get()
					->toJson();
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
					->select('id', 'nickname', 'school', 'sex')
					->where('id', '<=', $lastRecord)
					->take($per_page)
					->get()
					->toJson();
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
			} else if($action == 'members_show') // Members show profile
			{
				// Get all form data

				$info = array(
					'phone'   => Input::get('senderid'),
					'user_id' => Input::get('userid'),
				);
				if ($info)
				{
					$sender_id         = User::where('phone', Input::get('phone'))->orWhere('email', Input::get('phone'))->first()->id; // Sender ID
					$user_id           = Input::get('userid');
					$data              = User::where('id', $user_id)->first();
					$profile           = Profile::where('user_id', $user_id)->first();
					$like              = Like::where('sender_id', $sender_id)->first();
					$constellationInfo = getConstellation($profile->constellation); // Get user's constellation
					$tag_str           = explode(',', substr($profile->tag_str, 1)); // Get user's tag
					return Response::json(
						array(
							'status'        => 1,
							'sex'           => $data->sex,
							'portrait'      => route('home').'/'.'portrait/'.$data->portrait,
							'nickname'      => $data->nickname,
							'born_year'     => $data->born_year,
							'grade'         => $profile->grade,
							'constellation' => $constellationInfo['name'],
							'tag_str'       => $tag_str,
							'hobbies'       => $profile->hobbies,
							'bio'           => $data->bio,
							'question'      => $profile->question,
							'like'          => $like->count,
						)
					);
				} else {
					return Response::json(
						array(
							'status' 		=> 0
						)
					);
				}
			}
		} else {
			return Response::json(
				array(
					'status' 		=> 0
				)
			);
		}
	}

	/**
	 * View: Signup
	 * @return Response
	 */
	public function getSignup()
	{
		return View::make('authority.signup');
	}

	/**
	 * Action: PostVerifyCode
	 * @return Json Ajax Response
	 */
	public function postVerifyCode()
	{
		// Send Recovery Password SMS
		if(Input::get('forgot_password')){
			$phone       = array (
				'phone' => Input::get('phone')
			);
			// Create validation rules
			$rules = array(
				'phone'          => 'required|digits:11|exists:users'
			);
			// Custom validation message
			$messages = array(
				'phone.required' => '请输入手机号码。',
				'phone.digits'   => '请输入正确的手机号码。',
				'phone.exists'   => '此手机号未注册。'
			);
			// Begin verification
			$validator = Validator::make($phone, $rules, $messages);
			if ($validator->passes()) {
				$verify_code = rand(100000,999999);
				Session::forget('verify_code');
				Session::put('verify_code', $verify_code);
				include_once( app_path('api/sms/SendTemplateSMS.php') );
				sendTemplateSMS(Input::get('phone'), array($verify_code,'5'), "1");
			} else {
				return Response::json(
					array(
						'fail' 		=> true,
						'errors' 	=> $validator->getMessageBag()->toArray()
					)
				);
			}
		} else {
			$phone       = array (
				'phone' => Input::get('phone')
			);
			// Create validation rules
			$rules = array(
				'phone'          => 'required|digits:11|unique:users'
			);
			// Custom validation message
			$messages = array(
				'phone.required' => '请输入手机号码。',
				'phone.digits'   => '请输入正确的手机号码。',
				'phone.unique'   => '此手机号码已被使用。'
			);
			// Begin verification
			$validator = Validator::make($phone, $rules, $messages);
			if ($validator->passes()) {
				$verify_code = rand(100000,999999);
				Session::forget('verify_code');
				Session::put('verify_code', $verify_code);
				include_once( app_path('api/sms/SendTemplateSMS.php') );
				sendTemplateSMS(Input::get('phone'), array($verify_code,'5'), "1");
			} else {
				return Response::json(
					array(
						'fail' 		=> true,
						'errors' 	=> $validator->getMessageBag()->toArray()
					)
				);
			}
		}
	}

	/**
	 * Action: postSMSReset
	 * @return Json Ajax Response
	 */
	public function postSMSReset()
	{
		// Get all form data.
		$data = Input::all();
		// Create validation rules
		$rules = array(
			'phone'               => 'required|digits:11|exists:users',
			'password'            => 'required|alpha_dash|between:6,16|confirmed',
			'password_confirmation'            => 'required',
			'sms_code'			  => 'required|digits:6',
		);
		// Custom validation message
		$messages = array(
			'phone.required'      => '请输入手机号码。',
			'phone.digits'        => '请输入正确的手机号码。',
			'phone.exists'        => '此手机号码未注册。',
			'password.required'   => '请输入密码。',
			'password.alpha_dash' => '密码格式不正确。',
			'password.between'    => '密码长度请保持在:min到:max位之间。',
			'password.confirmed'  => '两次输入的密码不一致。',
			'password_confirmation.required' => '请填写确认密码',
			'sms_code.required'   => '请填写验证码。',
			'sms_code.digits'	  => '验证码错误。',
		);

		// Begin verification
		$validator   = Validator::make($data, $rules, $messages);
		$phone       = Input::get('phone');
		$verify_code = Session::get('verify_code');
		$sms_code	 = Input::get('sms_code');
		if ($validator->passes() && $sms_code == $verify_code) {
			// Verification success, add user
			$user           = User::where('phone', $phone)->first();
			$user->password = Hash::make(md5(Input::get('password')));
			if ($user->save()) {
				// Redirect to Home Page
				Auth::login($user);
				// Add user fail
				return Response::json(
					array(
						'success' 		=> true
					)
				);
			} else {
				// Add user fail
				return Response::json(
					array(
						'fail' 		=> true,
						'errors' 	=> '找回密码失败'
					)
				);
			}
		} else {
			// Add user fail
			return Response::json(
				array(
					'fail' 		=> true,
					'errors' 	=> $validator->getMessageBag()->toArray()
				)
			);
		}
	}

	/**
	 * Action: Signup
	 * @return Response
	 */
	public function postSignup()
	{
		if(Input::get('type') === 'email') {
			// Get all form data.
			$data = Input::all();
			// Create validation rules
			$rules = array(
				'email'               => 'required|email|unique:users',
				'password'            => 'required|alpha_dash|between:6,16|confirmed',
			);
			// Custom validation message
			$messages = array(
				'email.required'      => '请输入邮箱地址。',
				'email.email'         => '请输入正确的邮箱地址。',
				'email.unique'        => '此邮箱已被使用。',
				'password.required'   => '请输入密码。',
				'password.alpha_dash' => '密码格式不正确。',
				'password.between'    => '密码长度请保持在:min到:max位之间。',
				'password.confirmed'  => '两次输入的密码不一致。',
			);
			// Begin verification
			$validator = Validator::make($data, $rules, $messages);
			if ($validator->passes()) {
				// Verification success，add user
				$user           = new User;
				$user->email    = Input::get('email');
				$user->password = md5(Input::get('password'));
				if ($user->save()) {
					// Add user success
					// Generate activation code
					$activation        = new Activation;
					$activation->email = $user->email;
					$activation->token = str_random(40);
					$activation->save();
					// Send activation mail
					$with = array('activationCode' => $activation->token);
					Mail::later(10, 'emails.auth.activation', $with, function ($message) use ($user) {
						$message
							->to($user->email)
							->subject('聘爱网 账号激活邮件'); // Subject
					});
					// Redirect to a registration page, prompts user to activate
					return Redirect::route('signupSuccess', $user->email);
				} else {
					// Add user fail
					return Redirect::back()
						->withInput()
						->withErrors(array('add' => '注册失败。'));
				}
			} else {
				// Verification fail, redirect back
				return Redirect::back()
					->withInput()
					->withErrors($validator);
			}
		} else {
			// Get all form data.
			$data = Input::all();
			// Create validation rules
			$rules = array(
				'phone'               => 'required|digits:11|unique:users',
				'password'            => 'required|alpha_dash|between:6,16|confirmed',
				'sms_code'			  => 'required|digits:6',
			);
			// Custom validation message
			$messages = array(
				'phone.required'      => '请输入手机号码。',
				'phone.digits'        => '请输入正确的手机号码。',
				'phone.unique'        => '此手机号码已被使用。',
				'password.required'   => '请输入密码。',
				'password.alpha_dash' => '密码格式不正确。',
				'password.between'    => '密码长度请保持在:min到:max位之间。',
				'password.confirmed'  => '两次输入的密码不一致。',
				'sms_code.required'   => '请填写验证码。',
				'sms_code.digits'	  => '验证码错误。',
			);
			// Begin verification
			$validator   = Validator::make($data, $rules, $messages);
			$phone       = Input::get('phone');
			$verify_code = Session::get('verify_code');
			$sms_code	 = Input::get('sms_code');
			if ($validator->passes() && $sms_code == $verify_code) {
				// Verification success, add user
				$user           = new User;
				$user->phone    = $phone;
				$user->password = md5(Input::get('password'));
				if ($user->save()) {
					// Redirect to a registration page, prompts user to activate
					return Redirect::route('home');
				} else {
					// Add user fail
					return Redirect::back()
						->withInput()
						->withErrors(array('add' => '注册失败。'));
				}
			} else {
				// Add user fail
				return Redirect::route('signup')
					->withInput()
					->withErrors($validator);
			}
		}
	}

	/**
	 * View: Signuo success, prompts user to activate
	 * @param  string $email user E-mail
	 * @return Response
	 */
	public function getSignupSuccess($email)
	{
		// Confirmed the existence of this inactive mailboxes
		$activation = Activation::whereRaw("email = '{$email}'")->first();
		// No mailboxes in the database, throw 404
		is_null($activation) AND App::abort(404);
		// Prompts user to activate
		return View::make('authority.signupSuccess')->with('email', $email);
	}

	/**
	 * Action: Activate account
	 * @param  string $activationCode Activation tokens
	 * @return Response
	 */
	public function getActivate($activationCode)
	{
		// Database authentication tokens
		$activation = Activation::where('token', $activationCode)->first();
		// No tokens in the database, throw 404
		is_null($activation) AND App::abort(404);
		// Database tokens
		// Activate the corresponding user
		$user               = User::where('email', $activation->email)->first();
		$user->activated_at = new Carbon;
		$user->save();
		// Delete tokens
		$activation->delete();
		// Activation success
		return View::make('authority.activationSuccess');
	}

	/**
	 * Page: Forgot password, send a password reset mail
	 * @return Response
	 */
	public function getForgotPassword()
	{
		return View::make('authority.password.remind');
	}


	/**
	 * Action: Forgot password, send a password reset mail
	 * @return Response
	 */
	public function postForgotPassword()
	{
		// Calling the system-provided class
		$response = Password::remind(Input::only('email'), function ($m, $user, $token) {
			$m->subject('聘爱网 密码重置邮件'); // Title
		});
		// Detect mail and send a password reset message
		switch ($response) {
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));
			case Password::REMINDER_SENT:
				return Redirect::back()->with('status', Lang::get($response));
		}
	}

	/**
	 * View: Reset password
	 * @return Response
	 */
	public function getReset($token)
	{
		// No tokens in the database, throw 404
		is_null(PassowrdReminder::where('token', $token)->first()) AND App::abort(404);
		return View::make('authority.password.reset')->with('token', $token);
	}

	/**
	 * Action: Reset password
	 * @return Response
	 */
	public function postReset()
	{
		// Invoke system comes with the password reset process
		$credentials = Input::only(
			'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function ($user, $password) {
			// Save new password
			$user->password = md5($password);
			$user->save();
			// User signin
			Auth::login($user);
		});

		switch ($response) {
			case Password::INVALID_PASSWORD:
				// no break
			case Password::INVALID_TOKEN:
				// no break
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));
			case Password::PASSWORD_RESET:
				return Redirect::to('/');
		}
	}

}