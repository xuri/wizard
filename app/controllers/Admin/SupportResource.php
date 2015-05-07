<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Class for administrators management support or suggestions.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @license   	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	Release: 0.1 2014-12-25
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
	 * @param integer $id Support or suggestions ID
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
	 * @param integer $id Support or suggestions ID
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
	 * @param integer $id Support or suggestions ID
	 * @return Response     View
	 */
	public function show($id)
	{
		$resourceName = $this->resourceName;

		// Retrieve support ticket
		$data			= $this->model->find($id);

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

	/**
	 * Resource promotion action
	 * GET      /resource/promotion
	 * @return Response
	 */
	public function promotion()
	{
		// Query all support content is 3 or 4 digits with complete profile user
		$completeProfileUser = Support::whereRaw("content regexp '^[0-9]{3,4}$'")
									->orderBy('content')
									->groupBy('user_id')
									->whereHas('hasOneUser', function($hasProfile) {
										$hasProfile->whereNotNull('school')
												->whereNotNull('portrait');
										})
									->get()
									->toArray();

		// Foreach new array from query result array content key
		foreach($completeProfileUser as $completeProfileUserKey => $completeProfileUserItem){
			$completeProfileUserArray[$completeProfileUserKey] =  $completeProfileUserItem['content'];
		}

		// Remove duplicate keys in array
		$completeProfileUserList = array_unique($completeProfileUserArray);

		// Define an empty array of complete profile user list
		$completeProfileUserListAnalytics = [];

		// To make agent ID as key and count as value of complete profile user list
		foreach ($completeProfileUserList as $completeProfileUserListKey => $completeProfileUserListVaule) {
			$completeProfileUserListAnalytics[$completeProfileUserList[$completeProfileUserListKey]] = Support::whereRaw("content regexp '^[0-9]{3,4}$'")
												->where('content', $completeProfileUserList[$completeProfileUserListKey])
												->whereHas('hasOneUser', function($hasUncompleteProfile) {
												$hasUncompleteProfile->whereNotNull('school')
														->whereNotNull('bio')
														->whereNotNull('portrait')
														->whereNotNull('born_year');
												})
												->distinct()
												->get(array('user_id'))
												->count();
		}

		// Define master agent ID index array of complete profile user list
		foreach ($completeProfileUserListAnalytics as $completeProfileUserListAnalyticsKey => $completeProfileUserListAnalyticsKeyValue)
		{
			// Remove last 2 digits of key, that's master agent ID of complete profile user list
		    $completeProfileUserListAnalyticsIndex[$completeProfileUserListAnalyticsKey] =  substr($completeProfileUserListAnalyticsKey, 0, -2);
		}

		// Remove duplicate keys in master agent ID index array of complete profile user list
		$completeProfileUserListAnalyticsIndexArray = array_unique($completeProfileUserListAnalyticsIndex);

		// Query all support content is 3 or 4 digits with uncomplete profile user
		$uncompleteProfileUser = Support::whereRaw("content regexp '^[0-9]{3,4}$'")
									->orderBy('content')
									->groupBy('user_id')
									// ->whereHas('hasOneUser', function($hasUncompleteProfile) {
									// 	$hasUncompleteProfile->orWhereNull('school')
									// 			->orWhereNull('bio')
									// 			->orWhereNull('portrait')
									// 			->orWhereNull('born_year');
									// 	})
									->get()
									->toArray();

		// Foreach new array from query result array content key
		foreach($uncompleteProfileUser as $uncompleteProfileUserKey => $uncompleteProfileUserItem){
			$uncompleteProfileUserArray[$uncompleteProfileUserKey] =  $uncompleteProfileUserItem['content'];
		}

		// Remove duplicate keys in array
		$uncompleteProfileUserList = array_unique($uncompleteProfileUserArray);

		// Define an empty array of uncomplete profile user list
		$uncompleteProfileUserListAnalytics = [];

		// To make agent ID as key and count as value of uncomplete profile user list
		foreach ($uncompleteProfileUserList as $uncompleteProfileUserListKey => $uncompleteProfileUserListVaule) {
			$uncompleteProfileUserListAnalytics[$uncompleteProfileUserList[$uncompleteProfileUserListKey]] = Support::whereRaw("content regexp '^[0-9]{3,4}$'")
										->where('content', $uncompleteProfileUserList[$uncompleteProfileUserListKey])
										->distinct()
										->get(array('user_id'))
										->count() -
										Support::whereRaw("content regexp '^[0-9]{3,4}$'")
											->where('content', $uncompleteProfileUserList[$uncompleteProfileUserListKey])
											->whereHas('hasOneUser', function($hasUncompleteProfile) {
											$hasUncompleteProfile->whereNotNull('school')
													->whereNotNull('portrait')
													->whereNotNull('born_year');
											})
											->distinct()
											->get(array('user_id'))
											->count();
		}

		// Define master agent ID index array of uncomplete profile user list
		foreach ($uncompleteProfileUserListAnalytics as $uncompleteProfileUserListAnalyticsKey => $uncompleteProfileUserListAnalyticsKeyValue)
		{
			// Remove last 2 digits of key, that's master agent ID of uncomplete profile user list
		    $uncompleteProfileUserListAnalyticsIndex[$uncompleteProfileUserListAnalyticsKey] =  substr($uncompleteProfileUserListAnalyticsKey, 0, -2);
		}

		// Remove duplicate keys in master agent ID index array of uncomplete profile user list
		$uncompleteProfileUserListAnalyticsIndexArray = array_unique($uncompleteProfileUserListAnalyticsIndex);

		return View::make($this->resourceView . '.promotion')->with(compact('completeProfileUserList', 'uncompleteProfileUserList', 'completeProfileUserListAnalyticsIndexArray', 'completeProfileUserListAnalytics', 'uncompleteProfileUserListAnalyticsIndexArray', 'uncompleteProfileUserListAnalytics'));
	}

	/**
	 * Resource promotion action
	 * GET      /resource/promotion
	 * @return Response
	 */
	public function promotionPublic()
	{
		// Query all support content is 3 or 4 digits with complete profile user
		$completeProfileUser = Support::whereRaw("content regexp '^[0-9]{3,4}$'")
									->orderBy('content')
									->groupBy('user_id')
									->whereHas('hasOneUser', function($hasProfile) {
										$hasProfile->whereNotNull('school')
												->whereNotNull('portrait');
										})
									->get()
									->toArray();

		// Foreach new array from query result array content key
		foreach($completeProfileUser as $completeProfileUserKey => $completeProfileUserItem){
			$completeProfileUserArray[$completeProfileUserKey] =  $completeProfileUserItem['content'];
		}

		// Remove duplicate keys in array
		$completeProfileUserList = array_unique($completeProfileUserArray);

		// Define an empty array of complete profile user list
		$completeProfileUserListAnalytics = [];

		// To make agent ID as key and count as value of complete profile user list
		foreach ($completeProfileUserList as $completeProfileUserListKey => $completeProfileUserListVaule) {
			$completeProfileUserListAnalytics[$completeProfileUserList[$completeProfileUserListKey]] = Support::whereRaw("content regexp '^[0-9]{3,4}$'")
												->where('content', $completeProfileUserList[$completeProfileUserListKey])
												->whereHas('hasOneUser', function($hasUncompleteProfile) {
												$hasUncompleteProfile->whereNotNull('school')
														->whereNotNull('bio')
														->whereNotNull('portrait')
														->whereNotNull('born_year');
												})
												->distinct()
												->get(array('user_id'))
												->count();
		}

		// Define master agent ID index array of complete profile user list
		foreach ($completeProfileUserListAnalytics as $completeProfileUserListAnalyticsKey => $completeProfileUserListAnalyticsKeyValue)
		{
			// Remove last 2 digits of key, that's master agent ID of complete profile user list
		    $completeProfileUserListAnalyticsIndex[$completeProfileUserListAnalyticsKey] =  substr($completeProfileUserListAnalyticsKey, 0, -2);
		}

		// Remove duplicate keys in master agent ID index array of complete profile user list
		$completeProfileUserListAnalyticsIndexArray = array_unique($completeProfileUserListAnalyticsIndex);

		// Query all support content is 3 or 4 digits with uncomplete profile user
		$uncompleteProfileUser = Support::whereRaw("content regexp '^[0-9]{3,4}$'")
									->orderBy('content')
									->groupBy('user_id')
									->whereHas('hasOneUser', function($hasUncompleteProfile) {
										$hasUncompleteProfile->orWhereNull('school')
												->orWhereNull('bio')
												->orWhereNull('portrait')
												->orWhereNull('born_year');
										})
									->get()
									->toArray();

		// Foreach new array from query result array content key
		foreach($uncompleteProfileUser as $uncompleteProfileUserKey => $uncompleteProfileUserItem){
			$uncompleteProfileUserArray[$uncompleteProfileUserKey] =  $uncompleteProfileUserItem['content'];
		}

		// Remove duplicate keys in array
		$uncompleteProfileUserList = array_unique($uncompleteProfileUserArray);

		// Define an empty array of uncomplete profile user list
		$uncompleteProfileUserListAnalytics = [];

		// To make agent ID as key and count as value of uncomplete profile user list
		foreach ($uncompleteProfileUserList as $uncompleteProfileUserListKey => $uncompleteProfileUserListVaule) {
			$uncompleteProfileUserListAnalytics[$uncompleteProfileUserList[$uncompleteProfileUserListKey]] = Support::whereRaw("content regexp '^[0-9]{3,4}$'")
										->where('content', $uncompleteProfileUserList[$uncompleteProfileUserListKey])
										->distinct()
										->get(array('user_id'))
										->count() -
										Support::whereRaw("content regexp '^[0-9]{3,4}$'")
											->where('content', $uncompleteProfileUserList[$uncompleteProfileUserListKey])
											->whereHas('hasOneUser', function($hasUncompleteProfile) {
											$hasUncompleteProfile->whereNotNull('school')
													->whereNotNull('portrait')
													->whereNotNull('born_year');
											})
											->distinct()
											->get(array('user_id'))
											->count();
		}

		// Define master agent ID index array of uncomplete profile user list
		foreach ($uncompleteProfileUserListAnalytics as $uncompleteProfileUserListAnalyticsKey => $uncompleteProfileUserListAnalyticsKeyValue)
		{
			// Remove last 2 digits of key, that's master agent ID of uncomplete profile user list
		    $uncompleteProfileUserListAnalyticsIndex[$uncompleteProfileUserListAnalyticsKey] =  substr($uncompleteProfileUserListAnalyticsKey, 0, -2);
		}

		// Remove duplicate keys in master agent ID index array of uncomplete profile user list
		$uncompleteProfileUserListAnalyticsIndexArray = array_unique($uncompleteProfileUserListAnalyticsIndex);

		return View::make($this->resourceView . '.promotion_public')->with(compact('completeProfileUserList', 'uncompleteProfileUserList', 'completeProfileUserListAnalyticsIndexArray', 'completeProfileUserListAnalytics', 'uncompleteProfileUserListAnalyticsIndexArray', 'uncompleteProfileUserListAnalytics'));
	}
}