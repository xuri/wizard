<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Class for administrators management users, include update, block and destory user, send system notifications to single user or all users.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @license   	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	Release: 0.1 2014-12-25
 */

class Admin_UserResource extends BaseResource
{
	/**
	 * Resource view directory
	 * @var string
	 */
	protected $resourceView = 'admin.users';

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
	protected $resourceName = '用户';

	/**
	 * Custom validation message
	 * @var array
	 */
	protected $validatorMessages = array(
		'email.required'		=> '请输入邮箱地址。',
		'email.email'			=> '请输入正确的邮箱地址。',
		'email.unique'			=> '此邮箱已被使用。',
		'password.required'		=> '请输入密码。',
		'password.alpha_dash'	=> '密码格式不正确。',
		'password.between'		=> '密码长度请保持在:min到:max位之间。',
		'password.confirmed'	=> '两次输入的密码不一致。',
		'is_admin.in'			=> '非法输入。',
		'points.numeric'		=> '积分数格式不正确',
		'phone.numeric'			=> '手机号码格式不正确',
		'phone.digits'			=> '手机号码必须是11位数字',
		'renew.numeric'			=> '签到次数格式不正确',
		'constellation.numeric'	=> '星座代码格式不正确',
	);

	/**
	 * Resource list view
	 * GET         /resource
	 * @return Response
	 */
	public function index()
	{
		$provinces	= Province::get();

		// Get sort conditions
		$orderColumn	= Input::get('sort_up', Input::get('sort_down', 'created_at'));
		$direction		= Input::get('sort_up') ? 'asc' : 'desc' ;

		// Get search conditions
		switch (Input::get('sex')) {
			case 'F':
				$sex = 'F';
				break;
			case 'M':
				$sex = 'M';
				break;
		}

		// Get search conditions
		switch (Input::get('is_verify')) {
			case 0:
				$is_verify = 0;
				break;
			case 1:
				$is_verify = 1;
				break;
		}

		if(Input::get('like')) {
			$filter = Input::get('like');
		}

		// Construct query statement
		$query = $this->model->orderBy($orderColumn, $direction);
		isset($sex) AND $query->where('sex', $sex);
		isset($is_verify) AND $query->where('is_verify', $is_verify);
		isset($filter) AND $query->where('email', 'like', "%{$filter}%")->orWhere('nickname', 'like', "%{$filter}%")->orWhere('id', 'like', "%{$filter}%")->orWhere('phone', 'like', "%{$filter}%");
		$datas = $query->paginate(10);
		return View::make($this->resourceView.'.index')->with(compact('datas', 'provinces'));
	}

	/**
	 * Create user
	 * @return Response     View
	 */
	public function create() {
		$universities = University::get();
		return View::make($this->resourceView . '.create')->with(compact('universities'));
	}

	/**
	 * Store user
	 * @return Response     View
	 */
	public function store() {
		// Get all form data.
		$data = Input::all();

		// Create validation rules
		$rules = array(
			'phone'		=> 'required|digits:11|unique:users',
			'password'	=> 'alpha_dash|between:6,16',
			'sex'		=> 'required',
			'is_admin'	=> 'required',
			'from'		=> 'required',
		);

		// Custom validation message
		$messages = array(
			'phone.required'		=> Lang::get('authority.phone_required'),
			'phone.digits'			=> Lang::get('authority.phone_digits'),
			'phone.unique'			=> Lang::get('authority.phone_unique'),
			'password.alpha_dash'	=> Lang::get('authority.password_alpha_dash'),
			'password.between'		=> '密码长度请保持在:min到:max位之间。',
			'sex.required'			=> Lang::get('authority.sex_required'),
			'is_admin.required'		=> '请设定用户权限类型',
			'from.required'			=> '请选择注册来源',
		);

		// Begin verification
		$validator   = Validator::make($data, $rules, $messages);
		$phone       = Input::get('phone');
		if ($validator->passes()) {

			if("" !== Input::get('created_at')) {
				$activated_at	= Input::get('created_at');
				$created_at		= Input::get('created_at');
			} else {
				$activated_at	= date('Y-m-d H:m:s');
				$created_at		= date('Y-m-d H:m:s');
			}

			if(Input::get('password')) {
				$password	= Input::get('password');
			} else {
				$password	= 'password';
			}

			// Verification success, add user
			$user				= new User;
			$user->phone		= $phone;
			$user->from			= Input::get('from');
			$user->password		= md5($password);
			$user->created_at	= $created_at;
			$user->activated_at	= $activated_at;
			$user->sex			= Input::get('sex');
			$user->is_admin		= (int)Input::get('is_admin', 0);
			$user->is_verify	= (int)Input::get('is_verify', 0);
			if("" !== Input::get('signin_at')) {
				$user->signin_at		= Input::get('signin_at');
			}
			if("" !== Input::get('nickname')) {
				$user->nickname			= Input::get('nickname');
			}
			if("" !== Input::get('born_year')) {
				$user->born_year		= Input::get('born_year');
			}
			if("" !== Input::get('school')) {
				$user_school 			= Input::get('school');
				if(is_null($user->school)) {
					// First set school
					University::where('university', $user_school)->increment('count');
				} else {
					if($user->school != $user_school) {
						University::where('university', $user_school)->increment('count');
						University::where('university', $user->school)->decrement('count');
					}
				}
				$user->school       	= $user_school;
			}
			if("" !== Input::get('portrait')) {
				$user->portrait			= Input::get('portrait');
			}
			if("" !== Input::get('sex')) {
				$user->sex				= Input::get('sex');
			}
			if("" !== Input::get('points')) {
				$user->points			= (int)Input::get('points', 2);
			}
			if("" !== Input::get('bio')) {
				$user->bio				= Input::get('bio');
			}

			if ($user->save()) {
				$profile				= new Profile;
				$profile->user_id		= (int)$user->id;
				if("" !== Input::get('grade')) {
					$profile->grade			= (int)Input::get('grade');
				}
				if("" !== Input::get('language')) {
					$profile->language		= Input::get('language');
				}
				if("" !== Input::get('constellation')) {
					$profile->constellation	= (int)Input::get('constellation');
				}
				if("" !== Input::get('tag_str')) {
					$profile->tag_str		= Input::get('tag_str');
				}
				if("" !== Input::get('hobbies')) {
					$profile->hobbies		= Input::get('hobbies');
				}
				if("" !== Input::get('self_intro')) {
					$profile->self_intro	= Input::get('self_intro');
				}
				if("" !== Input::get('question')) {
					$profile->question		= Input::get('question');
				}
				if("" !== Input::get('renew')) {
					$profile->renew			= (int)Input::get('renew');
				}
				$profile->save();

				// Register user in easemob IM system
				Queue::push('AddUserQueue', [
								'username'	=> $user->id,
								'password'	=> $user->password,
							]);

				// Create floder to store chat record
				File::makeDirectory(app_path('chatrecord/user_' . $user->id, 0777, true));

				// Add success
				return Redirect::back()
					->with('success', '<strong>' . $this->resourceName . '添加成功：</strong>您可以继续添加新' . $this->resourceName . '，或返回用户列表。');
			} else {
				// Add fail
				return Redirect::back()
					->withInput()
					->with('error', '<strong>' . $this->resourceName . '添加失败。</strong>');
			}
		} else {
			// Verification fail, redirect back
			return Redirect::back()
				->withInput()
				->withErrors($validator);
		}
	}

	/**
	 * Edit user profile
	 * @param  int $id User ID
	 * @return Response     View
	 */
	public function edit($id) {
		$data		= $this->model->where('id', $id)->first();
		$profile	= Profile::where('user_id', $id)->first();
		$universities = University::get();
		return View::make($this->resourceView . '.edit')->with(compact('data', 'profile', 'universities'));
	}

	/**
	 * Resource edit action
	 * POST   /resource/{id}
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		// Get all form data.
		$data	= Input::all();

		// Create validation rules
		$rules	= array(
			'born_year'		=> 'numeric|digits:4',
			'points'		=> 'numeric',
			'phone'			=> 'numeric|digits:11',
			'grade'			=> 'numeric|digits:4',
			'renew'			=> 'numeric',
			'constellation'	=> 'numeric'
		);

		// Custom validation message
		$messages	= $this->validatorMessages;

		// Begin verification
		$validator	= Validator::make($data, $rules, $messages);
		if ($validator->passes()) {

			// Verification success
			// Update resource
			$model						= $this->model->find($id);
			$model->is_admin			= (int)Input::get('is_admin', 0);
			$model->is_verify			= (int)Input::get('is_verify', 0);
			if("" !== Input::get('created_at')) {
				$model->created_at		= Input::get('created_at');
			}
			if("" !== Input::get('signin_at')) {
				$model->signin_at		= Input::get('signin_at');
			}
			if("" !== Input::get('nickname')) {
				$model->nickname		= Input::get('nickname');
			}
			if("" !== Input::get('phone')) {
				$model->phone			= Input::get('phone');
			}
			if("" !== Input::get('born_year')) {
				$model->born_year		= Input::get('born_year');
			}
			if("" !== Input::get('school')) {
				$school 				= Input::get('school');
				if(is_null($model->school)) {
					// First set school
					University::where('university', $school)->increment('count');
				} else {
					if($model->school != $school) {
						University::where('university', $school)->increment('count');
						University::where('university', $model->school)->decrement('count');
					}
				}
				$model->school       	= $school;
			}
			if("" !== Input::get('portrait')) {
				$model->portrait		= Input::get('portrait');
			}
			if("" !== Input::get('sex')) {
				$model->sex				= Input::get('sex');
			}
			if("" !== Input::get('points')) {
				$model->points			= (int)Input::get('points');
			}
			if("" !== Input::get('bio')) {
				$model->bio				= Input::get('bio');
			}

			// Update user profile
			$profile					= Profile::where('user_id', $id)->first();
			if("" !== Input::get('grade')) {
				$profile->grade			= (int)Input::get('grade');
			}
			if("" !== Input::get('language')) {
				$profile->language		= Input::get('language');
			}
			if("" !== Input::get('constellation')) {
				$profile->constellation	= (int)Input::get('constellation');
			}
			if("" !== Input::get('tag_str')) {
				$profile->tag_str		= Input::get('tag_str');
			}
			if("" !== Input::get('hobbies')) {
				$profile->hobbies		= Input::get('hobbies');
			}
			if("" !== Input::get('self_intro')) {
				$profile->self_intro	= Input::get('self_intro');
			}
			if("" !== Input::get('question')) {
				$profile->question		= Input::get('question');
			}
			if("" !== Input::get('renew')) {
				$profile->renew			= (int)Input::get('renew');
			}

			if ($model->save() && $profile->save())
			{
				// Update success
				return Redirect::back()
					->with('success', '<strong>'.$this->resourceName.'更新成功：</strong>您可以继续编辑'.$this->resourceName.'，或返回'.$this->resourceName.'列表。');
			} else {
				// Update fail
				return Redirect::back()
					->withInput()
					->with('error', '<strong>'.$this->resourceName.'更新失败。</strong>');
			}
		} else {
			// Verification fail
			return Redirect::back()->withInput()->withErrors($validator);
		}
	}

	/**
	 * Push notifications to user
	 * GET /{id}/notify
	 * @param  int $id USer ID
	 * @return Response     View
	 */
	public function notify($id)
	{
		$data			= $this->model->where('id', $id)->first();
		$notifications	= Notification::where('receiver_id', $id)->where('category', 8)->get();
		return View::make($this->resourceView.'.notify')->with(compact('data', 'notifications'));
	}

	/**
	 * Push notifications to user
	 * POST /{id}/notify
	 * @param  int $id USer ID
	 * @return Response     View
	 */
	public function postNotify($id)
	{
		if(Input::get('system_notification') != null)
		{
			$notification							= Notification(8, 0, $id); // System notifications to special user
			$notificationsContent					= new NotificationsContent;
			$notificationsContent->notifications_id	= $notification->id;
			$notificationsContent->content			= Input::get('system_notification');
			$notificationsContent->save();

			// Add push notifications for App client to queue
			Queue::push('SystemNotificationsQueue', [
				'id'					=> $id,
				'notification_id'		=> $notification->id,
				'created_at'			=> date('m-d H:m', strtotime($notification->created_at)),
				'system_notification'	=> Input::get('system_notification')
				]);

			// Update success
			return Redirect::back()
				->with('success', '<strong>'.$this->resourceName.'系统消息推送成功：</strong>您可以继续推送'.$this->resourceName.'，或返回'.$this->resourceName.'列表。');
		} else {
			// Update fail
			return Redirect::back()
				->with('warning', '<strong>'.$this->resourceName.'推送失败，请输入要推送的系统消息内容。</strong>');
		}

	}

	/**
	 * Block user
	 * POST /{id}/block
	 * @return Response     View
	 */
	public function block()
	{
		// Retrieve user
		$data			= $this->model->find(Input::get('id'));
		$data->block	= 1;
		if (is_null($data)) {
			return Redirect::back()->with('error', '没有找到对应的'.$this->resourceName.'。');
		} elseif ($data->save()){
			DB::table('forum_posts')->where('user_id', Input::get('id'))->update(array('block' => 1));
			DB::table('forum_comments')->where('user_id', Input::get('id'))->update(array('block' => 1));
			DB::table('forum_reply')->where('user_id', Input::get('id'))->update(array('block' => 1));
			return Redirect::back()->with('success', $this->resourceName.'锁定成功。');
		} else{
			return Redirect::back()->with('warning', $this->resourceName.'锁定失败。');
		}
	}

	/**
	 * Unclock user
	 * POST /{id}/unclock
	 * @return Response     View
	 */
	public function unclock()
	{
		// Retrieve user
		$data			= $this->model->find(Input::get('id'));
		$data->block	= 0;
		if (is_null($data)) {
			return Redirect::back()->with('error', '没有找到对应的'.$this->resourceName.'。');
		}
		elseif ($data->save()){
			DB::table('forum_posts')->where('user_id', Input::get('id'))->update(array('block' => 0));
			DB::table('forum_comments')->where('user_id', Input::get('id'))->update(array('block' => 0));
			DB::table('forum_reply')->where('user_id', Input::get('id'))->update(array('block' => 0));
			return Redirect::back()->with('success', $this->resourceName.'解锁成功。');
		} else{
			return Redirect::back()->with('warning', $this->resourceName.'解锁失败。');
		}
	}

	/**
	 * Resource destory action
	 * DELETE      /resource/{id}
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$data			= $this->model->find($id);
		$profile		= Profile::where('user_id', $id)->first();
		$portrait		= $this->model->find($id)->portrait;
		$oldPortrait	= $portrait;
		$portraitPath	= public_path('portrait/');
		if (is_null($data)) {
			return Redirect::back()->with('error', '没有找到对应的'.$this->resourceName.'。');
		}
		elseif ($data->delete() && $profile->delete()){

			// Destory user post in forum
			ForumPost::where('user_id', $id)->delete();

			if($portrait != NULL)
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
			return Redirect::back()->with('success', $this->resourceName.'删除成功。');
		} else{
			return Redirect::back()->with('warning', $this->resourceName.'删除失败。');
		}
	}

	/**
	 * User friendly relation details
	 * @param  int $id User ID
	 * @return Response     View
	 */
	public function detail($id)
	{
		$data			= $this->model->where('id', $id)->first();
		$sends			= Like::where('sender_id', $id)->get();
		$inboxs			= Like::where('receiver_id', $id)->get();
		$sent_count		= 1;
		$inbox_count	= 1;
		return View::make($this->resourceView.'.detail')->with(compact('data', 'sends', 'inboxs', 'sent_count', 'inbox_count'));
	}

	/**
	 * User chat record directory list
	 * @param  int $id User ID
	 * @return Response     View
	 */
	public function chatdir($id)
	{

		// Retrieve user
		$data			= $this->model->where('id', $id)->first();

		// Define chat record log storge path
		$path			= app_path('chatrecord/user_' . $id . '/');

		// Convert chat record log storge directories lists to array
		$directories	= array_map('basename', File::files($path));

		return View::make($this->resourceView.'.chatdir')->with(compact('data', 'directories'));
	}

	/**
	 * View user daily chat record
	 * @param  string $id slug
	 * @return Response     View
	 */
	public function chatrecord($id)
	{

		// Split slug to get user ID and log file name
		list($user_id, $log)	= preg_split('/[: -]/', $id);

		// Retrieve user
		$data					= $this->model->where('id', $user_id)->first();

		// Split log file name to get date
		$date					= substr($log, 0, 4) . '年' . substr($log, 4, -6) . '月' . substr($log, 6, -4) . '日';

		// Define chat record log storge path
		$path					= app_path('chatrecord/user_' . $user_id . '/');

		// Build standard Json format
		$json					= '[' . substr(File::get($path . $log), 0, -1) . ']';

		// Convert Json to array
		$chatrecord				= json_decode($json);

		return View::make($this->resourceView.'.chatrecord')->with(compact('data', 'date', 'chatrecord'));
	}

	/**
	 * User add friends request not responsed list
	 * @return response     View
	 */
	public function noactive()
	{

		// Get sort conditions
		$orderColumn	= Input::get('sort_up', Input::get('sort_down', 'id'));
		$direction		= Input::get('sort_up') ? 'asc' : 'desc' ;

		// Retrieve all add friends request not responsed
		$query			= Like::where('status', 0);

		// Fuzzy search conditions
		if(Input::get('like')) {
			$filter 	= Input::get('like');
		}

		$is_notify	= Input::get('is_notify', 0);

		// Notify status filter
		switch ($is_notify) {
			case '1':
				// All notified add friend requests
				$query->where('is_notify', 1);
				$query->orderBy($orderColumn, $direction);
				isset($filter) AND $query->where('id', 'like', "%{$filter}%")->orWhere('sender_id', 'like', "%{$filter}%")->orWhere('receiver_id', 'like', "%{$filter}%")->orWhere('answer', 'like', "%{$filter}%");
				$datas		= $query->paginate(10);
				return View::make($this->resourceView . '.isnotify')->with(compact('datas', 'all_notify'));
			break;

			default:
				// Get all is notified user id to array
				$is_notified = Like::where('is_notify', 1)->select('id')->get()->toArray();

				// All not notify add friend requests
				$query->where('is_notify', '!=', 1)->where(DB::raw('DAY(receiver_updated_at)'), '>', DB::raw('DAY(updated_at) + 3'))->orderBy($orderColumn, $direction)->groupBy('like.receiver_id')->whereNotIn('receiver_id', $is_notified);

				isset($filter) AND $query->where('id', 'like', "%{$filter}%")->orWhere('sender_id', 'like', "%{$filter}%")->orWhere('receiver_id', 'like', "%{$filter}%")->orWhere('answer', 'like', "%{$filter}%");
				$datas			= $query->paginate(10);

				return View::make($this->resourceView . '.noactive')->with(compact('datas'));
			break;
		}
	}

	/**
	 * Send SMS notify no active much more than 3 days user
	 * @param  int $id     Receiver SMS user ID
	 * @return response    View
	 */
	public function sms_notify($id)
	{
		// Retrieve like
		$like = DB::table('like')->where('receiver_id', $id);

		// Retrieve user
		$user = User::find($id);

		if($user->phone)
		{
			// Send SMS
			// Queue::push('SendLikesNotifySMSQueue', [
			// 	'phone'	=> $user->phone,
			// 	'count'	=> Like::where('reserver_id', $id)->where('status', 0)->count()
			// ]);

			if($like->update(array('is_notify' => 1))){
				return Redirect::back()->with('success', $this->resourceName.'好友请求通知短信发送成功。');
			} else{
				return Redirect::back()->with('warning', $this->resourceName.'好友请求通知短信发送失败。');
			}
		} else {
			// Send E-mail
			$with = array();
			Mail::later(10, 'emails.notify.likereminder', $with, function ($message) use ($user) {
						$message
							->to($user->email)
							->subject('聘爱 好友请求提醒'); // Subject
					});
			if($like->update(array('is_notify' => 1))){
				return Redirect::back()->with('success', $this->resourceName.'好友请求通知邮件发送成功。');
			} else{
				return Redirect::back()->with('warning', $this->resourceName.'好友请求通知邮件发送失败。');
			}
		}
	}

	/**
	 * Batch add user
	 * @return Response     View
	 */
	public function batch() {
		$universities = University::get();
		return View::make($this->resourceView . '.batch')->with(compact('universities'));
	}

	/**
	 * Batch add user
	 * @return Response     View
	 */
	public function batchadd() {
		// Get all form data.
		$data = Input::all();

		// Create validation rules
		$rules = array(
			'm_phone'		=> 'digits:3',
			'f_phone'		=> 'digits:3',
			'm_password'	=> 'alpha_dash|between:6,16',
			'f_password'	=> 'alpha_dash|between:6,16',
			'm_create'		=> 'required|numeric',
			'f_create'		=> 'required|numeric',
			'm_from'		=> 'required',
			'f_from'		=> 'required',
		);

		// Custom validation message
		$messages = array(
			'm_phone.digits'		=> '手机号段格式不正确。',
			'f_phone.digits'		=> '手机号段格式不正确。',
			'm_password.alpha_dash'	=> '密码格式不正确。',
			'm_password.between'	=> '密码长度请保持在:min到:max位之间。',
			'f_password.alpha_dash'	=> '密码格式不正确。',
			'f_password.between'	=> '密码长度请保持在:min到:max位之间。',
			'm_create.required'		=> '请填写创建男用户的数量',
			'f_create.required'		=> '请填写创建女用户的数量',
			'f_create.numeric'		=> '数量格式不正确',
			'f_create.numeric'		=> '数量格式不正确',
			'm_from.required'		=> '请选择注册来源',
			'f_from.required'		=> '请选择注册来源',
		);

		// Custom validation message
		$messages	= $this->validatorMessages;

		// Begin verification
		$validator	= Validator::make($data, $rules, $messages);

		// Validation success
		if ($validator->passes()) {

			// Add male users
			for ($m_create=0; $m_create < Input::get('m_create'); $m_create++) {

				// Get phone number set
				if("" !== Input::get('m_phone')) {
					// Custom number
					$m_phone = Input::get('m_phone', 187) . rand(10000000,99999999);
				} else {
					// Default number
					$m_phone = '187' . rand(10000000,99999999);
				}

				// Get phone password set
				if("" !== Input::get('m_password')) {
					// Custom password
					$m_password = Input::get('m_password');
				} else {
					// Default password
					$m_password = 'password';
				}

				// Determin male user phone number exists
				while (User::where('phone', $m_phone)->first()) {

					// Generate number
					if("" !== Input::get('m_phone')) {
					// Custom number
						$m_phone = Input::get('m_phone', 187) . rand(10000000,99999999);
					} else {
						// Default number
						$m_phone = '187' . rand(10000000,99999999);
					}

				}

				// Rand created time
				$m_rand_timestamp		= rand(strtotime('-20 day'), strtotime('-10 day'));
				$m_date					= date('Y-m-d H:i:s', $m_rand_timestamp);

				// Verification success, add user
				$m_user					= new User;
				$m_user->phone			= $m_phone;
				$m_user->from			= Input::get('m_from');
				$m_user->password		= md5($m_password);
				$m_user->created_at		= date('Y-m-d H:m:s');
				$m_user->activated_at	= date('Y-m-d H:m:s');
				$m_user->sex			= 'M';
				$m_user->is_admin		= (int)Input::get('is_admin', 0);
				$m_user->is_verify		= (int)Input::get('is_verify', 0);
				$m_user->created_at		= $m_date;
				$m_user->updated_at		= $m_date;
				$m_user->activated_at	= $m_date;
				$m_user->save();

				// Create user profile
				$m_profile				= new Profile;
				$m_profile->user_id		= (int)$m_user->id;
				$m_profile->created_at	= $m_date;
				$m_profile->updated_at	= $m_date;
				$m_profile->save();

				// Register user in easemob IM system
				Queue::push('AddUserQueue', [
								'username'    => $m_user->id,
								'password'	=> $m_user->password,
							]);

				// Create floder to store chat record
				File::makeDirectory(app_path('chatrecord/user_' . $m_user->id, 0777, true));

			}

			for ($f_create=0; $f_create < Input::get('f_create'); $f_create++) {
				// Get phone number set
				if("" !== Input::get('f_phone')) {
					// Custom number
					$f_phone = Input::get('f_phone', 187) . rand(10000000,99999999);
				} else {
					// Default number
					$f_phone = '187' . rand(10000000,99999999);
				}

				// Get phone password set
				if("" !== Input::get('f_password')) {
					// Custom password
					$f_password = Input::get('f_password');
				} else {
					// Default password
					$f_password = 'password';
				}

				// Determin male user phone number exists
				while (User::where('phone', $f_phone)->first()) {

					// Generate phone number
					if("" !== Input::get('f_phone')) {
						// Custom number
						$f_phone = Input::get('f_phone', 187) . rand(10000000,99999999);
					} else {
						// Default number
						$f_phone = '187' . rand(10000000,99999999);
					}
				}

				// Rand created time
				$f_rand_timestamp		= rand(strtotime('-20 day'), strtotime('-10 day'));
				$f_date					= date('Y-m-d H:i:s', $f_rand_timestamp);

				// Verification success, add user
				$f_user					= new User;
				$f_user->phone			= $f_phone;
				$f_user->from			= Input::get('f_from');
				$f_user->password		= md5($f_password);
				$f_user->created_at		= date('Y-m-d H:m:s');
				$f_user->activated_at	= date('Y-m-d H:m:s');
				$f_user->sex			= 'F';
				$f_user->is_admin		= (int)Input::get('is_admin', 0);
				$f_user->is_verify		= (int)Input::get('is_verify', 0);
				$f_user->created_at		= $f_date;
				$f_user->updated_at		= $f_date;
				$f_user->activated_at	= $f_date;
				$f_user->save();

				// Create user profile
				$f_profile				= new Profile;
				$f_profile->user_id		= (int)$f_user->id;
				$f_profile->created_at	= $f_date;
				$f_profile->updated_at	= $f_date;
				$f_profile->save();

				// Register user in easemob IM system
				Queue::push('AddUserQueue', [
								'username'	=> $f_user->id,
								'password'	=> $f_user->password,
							]);

				// Create floder to store chat record
				File::makeDirectory(app_path('chatrecord/user_' . $f_user->id, 0777, true));
			}

			// Update success
			return Redirect::back()
				->with('success', '成功添加了 ' . Input::get('m_create') . ' 个男' . $this->resourceName . '，'  . Input::get('f_create') . ' 个女' . $this->resourceName . '。 您可以继续编辑'.$this->resourceName.'，或返回'.$this->resourceName.'列表。');
		} else {
			// Verification fail
			return Redirect::back()->withInput()->withErrors($validator);
		}
	}
}