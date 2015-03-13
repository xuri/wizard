<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Class for university management, include set opening date, set open, pending and close status.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @license   	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	Release: 0.1 2014-12-25
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
	 * @return response View
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
	 * Analytics university status order by register users
	 * @return response View
	 */
	public function order_by_users_desc()
	{
		$resourceName		= '高校';
		$resource			= 'admin.university';
		$provinces			= Province::get();
		$universities		= University::get();
		$universities_list	= array();

		foreach ($universities as $universitiy => $keys) {

			$items['id']			= $keys->id;
			$items['name']			= $keys->university;
			$items['male_users']	= User::where('school', $keys->university)->where('sex', 'M')->count();
			$items['female_users']	= User::where('school', $keys->university)->where('sex', 'F')->count();
			$items['all_users']		= User::where('school', $keys->university)->count();
			$universities_list[]	= $items;
		}

		foreach ($universities_list as $key => $row)
		{
			$id[$key]			= $row['id'];
			$name[$key]			= $row['name'];
			$male_users[$key]	= $row['male_users'];
			$female_users[$key]	= $row['female_users'];
			$all_users[$key]	= $row['all_users'];
		}

		array_multisort($all_users, SORT_DESC, $universities_list);
		return View::make($this->resourceView.'.order_by_users_desc')->with(compact('universities_list', 'provinces', 'resourceName', 'resource'));
	}
	/**
	 * Mark open university
	 * GET /{id}/open
	 * @param integer $id University ID
	 * @return response View
	 */
	public function open($id)
	{
		// Retrieve university
		$data            = $this->model->find($id);

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
	 * @param integer $id University ID
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
	 * @param integer $id University ID
	 * @return Response     View
	 */
	public function edit($id)
	{
		$resourceName	= $this->resourceName;

		// Retrieve university ticket
		$data			= $this->model->find($id);
		$provinces		= Province::get();
		return View::make($this->resourceView.'.edit')->with(compact('resourceName', 'data', 'provinces'));
	}

	/**
	 * Update university open date
	 * POST /edit/{id}
	 * @param integer $id University ID
	 * @return Response     View
	 */
	public function update($id)
	{
		$data       = array (
			'university'	=> Input::get('university'),
			'province_id'	=> Input::get('province_id')
		);

		// Create validation rules
		$rules = array(
			'university'	=> 'required',
			'province_id'	=> 'required|integer'
		);

		// Custom validation message
		$messages = array(
			'university.required'	=> '高校名称必须填写。',
			'province_id.required'	=> '请选择所属省份。',
			'province_id.integer'	=> '所属省份格式不正确。'
		);

		// Begin verification
		$validator = Validator::make($data, $rules, $messages);
		if ($validator->passes()) {

			// Retrieve university ticket
			$data				= $this->model->find($id);
			$data->university	= Input::get('university');
			$data->province_id	= Input::get('province_id');
			if(Input::get('open_at') != null) {
				$data->open_at	= Input::get('open_at');
				$data->status	= 1;
			}
			if ($data->save()) {

				// Update success
				return Redirect::back()
					->with('success', '<strong>' . $this->resourceName.'信息编辑成功：</strong>您可以修改此设置' . $this->resourceName.'，或返回'.$this->resourceName.'列表。');
			} else {

				// Update fail
				return Redirect::back()
					->with('warning', '更新失败，请重新尝试。');
			}

		} else {

			// Verification fail
			return Redirect::back()->withInput()->withErrors($validator);
		}

	}

	/**
	 * Resource destory action
	 * DELETE      /resource/{id}
	 * @param integer $id University ID
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