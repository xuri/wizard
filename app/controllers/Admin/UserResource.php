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
		if(Input::get('like')) {
			$filter = Input::get('like');
		}

		// Construct query statement
		$query = $this->model->orderBy($orderColumn, $direction);
		isset($sex) AND $query->where('sex', $sex);
		isset($filter) AND $query->where('email', 'like', "%{$filter}%")->orWhere('nickname', 'like', "%{$filter}%")->orWhere('id', 'like', "%{$filter}%")->orWhere('phone', 'like', "%{$filter}%");
		$datas = $query->paginate(10);
		return View::make($this->resourceView.'.index')->with(compact('datas', 'provinces'));
	}

	/**
	 * Edit user profile
	 * @param  int $id USer ID
	 * @return Response     View
	 */
	public function edit($id) {
		$data		= $this->model->where('id', $id)->first();
		$profile	= Profile::where('user_id', $id)->first();
		$universities = University::get();
		return View::make($this->resourceView.'.edit')->with(compact('data', 'profile', 'universities'));
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
			$model					= $this->model->find($id);
			$model->is_admin		= (int)Input::get('is_admin', 0);
			$model->is_verify		= (int)Input::get('is_verify', 0);
			$model->created_at		= Input::get('created_at');
			$model->signin_at		= Input::get('signin_at');
			$model->nickname		= Input::get('nickname');
			$model->phone			= Input::get('phone');
			$model->born_year		= Input::get('born_year');
			$model->school			= Input::get('school');
			$model->portrait		= Input::get('portrait');
			$model->sex				= Input::get('sex');
			$model->points			= Input::get('points');
			$model->bio				= Input::get('bio');

			// Update user profile
			$profile				= Profile::where('user_id', $id)->first();
			if(Input::get('grade') != null) {
				$profile->grade		= Input::get('grade');
			}
			if(Input::get('constellation') != null) {
				$profile->constellation	= Input::get('constellation');
			}
			if(Input::get('tag_str') != null) {
				$profile->tag_str	= Input::get('tag_str');
			}
			if(Input::get('hobbies') != null) {
				$profile->hobbies	= Input::get('hobbies');
			}
			if(Input::get('self_intro') != null) {
				$profile->self_intro = Input::get('self_intro');
			}
			if(Input::get('question') != null) {
				$profile->question	= Input::get('question');
			}
			if(Input::get('renew') != null) {
				$profile->renew		= Input::get('renew');
			}
			if(Input::get('language') != null) {
				$profile->language	= Input::get('language');
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
		}
		elseif ($data->save()){
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

		// Retrieve all add friends request not responsed
		$datas			= Like::where('status', 0)->get();

		return View::make($this->resourceView . '.noactive')->with(compact('datas'));
	}

	/**
	 * Send SMS notify no active much more than 3 days user
	 * @param  int $id     Receiver SMS user ID
	 * @return response    View
	 */
	public function sms_notify($id)
	{
		// Retrieve like
		$like = Like::where('receiver_id', $id);

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
							->subject('聘爱网 好友请求提醒'); // Subject
					});
			if($like->update(array('is_notify' => 1))){
				return Redirect::back()->with('success', $this->resourceName.'好友请求通知邮件发送成功。');
			} else{
				return Redirect::back()->with('warning', $this->resourceName.'好友请求通知邮件发送失败。');
			}
		}
	}

}