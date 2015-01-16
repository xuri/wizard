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
 * @since 		25th Nov, 2014
 * @license 	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	0.1
 */

/**
 *
 * University status
 *
 * status = 0 or NULL   Close status code
 * status = 1 			Pending status code
 * status = 2 			Open status code
 */
class Admin_UniversityResource extends BaseResource
{
	/**
	 * Resource view directory
	 * @var string
	 */
	protected $resourceView = 'admin.university';

	/**
	 * Model name of the resource, after initialization to a model instance
	 * @var string|Illuminate\Database\Eloquent\Model
	 */
	protected $model = 'University';

	/**
	 * Resource identification
	 * @var string
	 */
	protected $resource = 'admin.university';

	/**
	 * Resource database tables
	 * @var string
	 */
	protected $resourceTable = 'university';

	/**
	 * Resource name (Chinese)
	 * @var string
	 */
	protected $resourceName = '高校';

	/**
	 * Resource list view
	 * GET         /resource
	 * @return Response
	 */
	public function index()
	{
		// Get sort conditions
		$orderColumn		= Input::get('sort_up', Input::get('sort_down', 'created_at'));
		$direction			= Input::get('sort_up') ? 'asc' : 'desc' ;
		// Get search conditions
		$province_filter	= Input::get('province');
		$status_filter		= Input::get('status');
		$university_like	= Input::get('like');

		// Construct query statement
		$query = $this->model->orderBy($orderColumn, $direction);
		if($province_filter){
			isset($province_filter) AND $query->where('province_id', $province_filter);
		}

		if($status_filter){
			isset($status_filter) AND $query->where('status', $status_filter);
		}

		isset($university_like)    AND $query->where('university', 'like', "%{$university_like}%");
		$datas		= $query->paginate(10);
		$provinces	= Province::get();
		return View::make($this->resourceView.'.index')->with(compact('datas', 'provinces'));
	}

	/**
	 * Mark open university
	 * GET /{id}/open
	 * @return Response     View
	 */
	public function open($id)
	{
		// Retrieve university
		$data			= $this->model->find($id);

		// Mark open university
		$data->status	= 2;

		if (is_null($data)) {
			return Redirect::back()->with('error', '没有找到对应的'.$this->resourceName.'。');
		}
		elseif ($data->save()){
			return Redirect::back()->with('success', $this->resourceName.'开放成功。');
		} else{
			return Redirect::back()->with('warning', $this->resourceName.'开放失败。');
		}
	}

	/**
	 * Close university service
	 * POST /{id}/close
	 * @return Response     View
	 */
	public function close($id)
	{
		// Retrieve university
		$data			= $this->model->find($id);

		// Close university
		$data->status	= 0;

		if (is_null($data)) {
			return Redirect::back()->with('error', '没有找到对应的'.$this->resourceName.'。');
		}
		elseif ($data->save()){
			return Redirect::back()->with('success', $this->resourceName.'取消开放成功。');
		} else{
			return Redirect::back()->with('warning', $this->resourceName.'取消开放失败。');
		}
	}

	/**
	 * Edit university open date
	 * GET /edit/{id}
	 * @return Response     View
	 */
	public function edit($id)
	{
		$resourceName = $this->resourceName;

		// Retrieve university ticket
		$data		  = $this->model->find($id)->first();

		return View::make($this->resourceView.'.edit')->with(compact('resourceName', 'data'));
	}

	/**
	 * Update university open date
	 * POST /edit/{id}
	 * @return Response     View
	 */
	public function update($id)
	{
		if(Input::get('open_at') != null)
		{
			// Retrieve university ticket
			$data			= $this->model->find($id);
			$data->open_at	= Input::get('open_at');
			$data->status	= 1;
			$data->save();

			// Update success
			return Redirect::back()
				->with('success', '<strong>'.$this->resourceName.'开通时间设定成功：</strong>您可以修改此设置'.$this->resourceName.'，或返回'.$this->resourceName.'列表。');
		} else {
			// Update fail
			return Redirect::back()
				->with('warning', '<strong>'.$this->resourceName.'设置失败，请输入开通时间。</strong>');
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
		$data        = $this->model->find($id);
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