<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @since 		25th Nov, 2014
 * @license 	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	0.1
 */

class Admin_SupportResource extends BaseResource
{
	/**
	 * Resource view directory
	 * @var string
	 */
	protected $resourceView = 'admin.support';

	/**
	 * Model name of the resource, after initialization to a model instance
	 * @var string|Illuminate\Database\Eloquent\Model
	 */
	protected $model = 'Support';

	/**
	 * Resource identification
	 * @var string
	 */
	protected $resource = 'admin.support';

	/**
	 * Resource database tables
	 * @var string
	 */
	protected $resourceTable = 'support';

	/**
	 * Resource name (Chinese)
	 * @var string
	 */
	protected $resourceName = '用户反馈';

	/**
	 * Resource list view
	 * GET         /resource
	 * @return Response
	 */
	public function index()
	{
		// Get sort conditions
		$orderColumn	= Input::get('sort_up', Input::get('sort_down', 'created_at'));
		$direction		= Input::get('sort_up') ? 'asc' : 'desc' ;
		// Get search conditions
		switch (Input::get('status')) {
			case '0':
				$is_admin = 0;
				break;
			case '1':
				$is_admin = 1;
				break;
		}
		switch (Input::get('target')) {
			case 'email':
				$email = Input::get('like');
				break;
		}
		// Construct query statement
		$query = $this->model->orderBy($orderColumn, $direction);
		isset($is_admin) AND $query->where('is_admin', $is_admin);
		isset($email)    AND $query->where('email', 'like', "%{$email}%");
		$datas = $query->paginate(10);
		return View::make($this->resourceView.'.index')->with(compact('datas'));
	}

	/**
	 * Mark read user support ticket
	 * GET /{id}/block
	 * @return Response     View
	 */
	public function read($id)
	{
		// Retrieve user support ticket
		$data			= $this->model->find($id);

		// Mark read support ticket
		$data->status	= true;

		if (is_null($data)) {
			return Redirect::back()->with('error', '没有找到对应的'.$this->resourceName.'。');
		}
		elseif ($data->save()){
			return Redirect::back()->with('success', $this->resourceName.'设置已读标记成功。');
		} else{
			return Redirect::back()->with('warning', $this->resourceName.'设置已读标记失败。');
		}
	}

	/**
	 * Unmark read user support ticket
	 * POST /{id}/block
	 * @return Response     View
	 */
	public function unread($id)
	{
		// Retrieve user support ticket
		$data			= $this->model->find($id);

		// Unmark read user support ticket
		$data->status	= false;

		if (is_null($data)) {
			return Redirect::back()->with('error', '没有找到对应的'.$this->resourceName.'。');
		}
		elseif ($data->save()){
			return Redirect::back()->with('success', $this->resourceName.'取消已读标记成功。');
		} else{
			return Redirect::back()->with('warning', $this->resourceName.'取消已读标记失败。');
		}
	}

	/**
	 * Show user support ticket
	 * POST /{id}/block
	 * @return Response     View
	 */
	public function show($id)
	{
		$resourceName = $this->resourceName;

		// Retrieve support ticket
		$data			= $this->model->find($id)->first();

		// Retrieve user
		$user			= User::find($data->user_id);
		return View::make($this->resourceView.'.show')->with(compact('resourceName', 'data', 'user'));
	}

	/**
	 * Resource destory action
	 * DELETE      /resource/{id}
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$data		= $this->model->find($id);
		if (is_null($data)) {
			return Redirect::back()->with('error', '没有找到对应的'.$this->resourceName.'。');
		}
		elseif ($data->delete()){
			return Redirect::back()->with('success', $this->resourceName.'删除成功。');
		} else{
			return Redirect::back()->with('warning', $this->resourceName.'删除失败。');
		}
	}
}