<?php

class AccountController extends BaseController
{
	/**
	 * Account index
	 * @return Response
	 */
	public function getIndex()
	{
		$profile = Profile::where('user_id', Auth::user()->id)->first();
		$constellationInfo = getConstellation($profile->constellation);
		$constellationIcon = $constellationInfo['icon'];
		$constellationName = $constellationInfo['name'];
		return View::make('account.index')->with(compact('profile', 'constellationIcon', 'constellationName'));
	}

	/**
	 * Account profile
	 * @return Reponse
	 */
	public function getComplete()
	{
		$profile = Profile::where('user_id', Auth::user()->id)->first();
		return View::make('account.complete')->with(compact('profile'));
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
			'portrait'      => 'required',
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
			'portrait.required'      => '设置个头像吧',
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

		    $portrait                = Input::get('portrait');
			$portrait                = str_replace('data:image/png;base64,', '', $portrait);
			$portrait                = str_replace(' ', '+', $portrait);
			$portraitData            = base64_decode($portrait);
			$portraitPath			 = public_path('portrait/');
			$portraitFile            = uniqid() . '.png';
			$successPortrait         = file_put_contents($portraitPath.$portraitFile, $portraitData);

		    // Update account
			$user                   = Auth::user();
			$user->nickname         = Input::get('nickname');
			$user->portrait         = $portraitFile;
			$user->sex              = Input::get('sex');
			$user->born_year        = Input::get('born_year');
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
		        return Redirect::back()->withInput()
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
	 * User sent messages
	 * @return Response
	 */
	public function getSent()
	{
		return View::make('account.sent.index');
	}

}