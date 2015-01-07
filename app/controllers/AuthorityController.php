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

class AuthorityController extends BaseController
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
	public function getSignin()
	{
		return View::make('authority.signin');
	}

	/**
	 * Action: Signin
	 * @return Response
	 */
	public function postSignin()
	{
		// Credentials
		$credentials = array(
			'email' => Input::get('email'),
			'password' => md5(Input::get('password')
		));
		$phone_credentials = array(
			'phone' => Input::get('email'),
			'password' => md5(Input::get('password')
		));
		// Remember login status
		// $remember    = Input::get('remember-me', 1);
		// Verify signin
		if (Auth::attempt($credentials) || Auth::attempt($phone_credentials)) {
			// Signin success, redirect to the previous page that was blocked
			return Redirect::intended();
		} else {
			// Signin fail, redirect back
			return Redirect::back()
				->withInput()
				->withErrors(array('attempt' => 'E-mail 或 用户名错误, 请重新登录'));
		}
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
						'fail'      => true,
						'errors'    => $validator->getMessageBag()->toArray()
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
						'fail'      => true,
						'errors'    => $validator->getMessageBag()->toArray()
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
			'phone'					=> 'required|digits:11|exists:users',
			'password'				=> 'required|alpha_dash|between:6,16|confirmed',
			'password_confirmation'	=> 'required',
			'sms_code'				=> 'required|digits:6',
		);
		// Custom validation message
		$messages = array(
			'phone.required'					=> '请输入手机号码。',
			'phone.digits'						=> '请输入正确的手机号码。',
			'phone.exists'						=> '此手机号码未注册。',
			'password.required'					=> '请输入密码。',
			'password.alpha_dash'				=> '密码格式不正确。',
			'password.between'					=> '密码长度请保持在:min到:max位之间。',
			'password.confirmed'				=> '两次输入的密码不一致。',
			'password_confirmation.required'	=> '请填写确认密码',
			'sms_code.required'					=> '请填写验证码。',
			'sms_code.digits'					=> '验证码错误。',
		);

		// Begin verification
		$validator		= Validator::make($data, $rules, $messages);
		$phone			= Input::get('phone');
		$verify_code	= Session::get('verify_code');
		$sms_code		= Input::get('sms_code');
		if ($validator->passes() && $sms_code == $verify_code) {
			// Verification success, add user
			$user			= User::where('phone', $phone)->first();
			$user->password	= Hash::make(md5(Input::get('password')));
			if ($user->save()) {
				// Redirect to Home Page
				Auth::login($user);
				// Add user fail
				return Response::json(
					array(
						'success'       => true
					)
				);
			} else {
				// Add user fail
				return Response::json(
					array(
						'fail'		=> true,
						'errors'	=> '找回密码失败'
					)
				);
			}
		} else {
			// Add user fail
			return Response::json(
				array(
					'fail'		=> true,
					'errors'	=> $validator->getMessageBag()->toArray()
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
					$profile			= new Profile;
					$profile->user_id	= $user->id;
					$profile->save();
					// Add user success
					// Generate activation code
					$activation			= new Activation;
					$activation->email	= $user->email;
					$activation->token	= str_random(40);
					$activation->save();

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
				'sms_code'            => 'required|digits:6',
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
				'sms_code.digits'     => '验证码错误。',
			);
			// Begin verification
			$validator   = Validator::make($data, $rules, $messages);
			$phone       = Input::get('phone');
			$verify_code = Session::get('verify_code');
			$sms_code    = Input::get('sms_code');
			if ($validator->passes() && $sms_code == $verify_code) {
				// Verification success, add user
				$user           = new User;
				$user->phone    = $phone;
				$user->password = md5(Input::get('password'));
				if ($user->save()) {
					$profile			= new Profile;
					$profile->user_id	= $user->id;
					$user->activated_at	= date('Y-m-d H:m:s');
					$profile->save();

					// Chat Register

					// If use authorizition register need get token
					$easemob			= getEasemob();
					// newRequest or newJsonRequest returns a Request object
					$regChat = cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users', ['username' => $user->id, 'password' => $user->password])
						->setHeader('content-type', 'application/json')
						->setHeader('Accept', 'json')
						->setHeader('Authorization', 'Bearer '.$easemob->token)
						->setOptions([CURLOPT_VERBOSE => true])
						->send();
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
			'email', 'password', 'password_confirmation', 'token'
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