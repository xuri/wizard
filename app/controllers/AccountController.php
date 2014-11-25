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

class AccountController extends BaseController
{
	/**
	 * Account index
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
	 * Account profile
	 * @return Reponse
	 */
	public function getComplete()
	{
		$profile = Profile::where('user_id', Auth::user()->id)->first();
		$constellationInfo = getConstellation($profile->constellation); // Get user's constellation
		return View::make('account.complete')->with(compact('profile', 'constellationInfo'));
	}

	/**
	 * Ajax get university list
	 * @return json $school
	 */
	public function postUniversity()
	{
		$province    = Input::get('province');

		$universites = University::where('province', $province)->get();
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
	 * @return json success true or false
	 */
	public function postRenew()
	{
		$renew = Input::get('renew');
		$today = Carbon::today();
		$user  = Profile::where('user_id', Auth::user()->id)->first();

		if($user->renew_at == '0000-00-00 00:00:00'){ // First renew
			$user->renew_at = Carbon::now();
			$user->renew    = $user->renew + 1;
			$user->save();
			return Response::json(
				array(
					'success' => true
				)
			);
		} else if ($today >= $user->renew_at){ // You haven't renew today
			$user->renew_at = Carbon::now();
			$user->renew    = $user->renew + 1;
			$user->save();
			return Response::json(
				array(
					'success' => true
				)
			);
		} else { // You have renew today
			return Response::json(
				array(
					'success' => false
				)
			);
		}
	}

	/**
	 * Post update profile information
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
		    if(Auth::user()->sex == NULL)
		    {
				$user->sex          = Input::get('sex');
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
		        	File::delete($portraitPath.$oldPortrait); // Delete old poritait
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
		$datas = Like::where('sender_id', Auth::user()->id)->get();
		return View::make('account.sent.index')->with(compact('datas'));
	}

	/**
	 * Other user like me
	 * @return Response
	 */
	public function getInbox()
	{
		$datas = Like::where('receiver_id', Auth::user()->id)->get();
		return View::make('account.inbox.index')->with(compact('datas'));
	}

}