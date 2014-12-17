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

class Admin_AnalyticsResource extends BaseResource
{
	/**
	 * Resource view directory
	 * @var string
	 */
	protected $resourceView = 'admin.analytics';

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
	protected $resourceName = '统计';

	/**
	 * Resource list view
	 * GET         /resource
	 * @return Response
	 */
	public function userForm()
	{
		$analyticsUsers = AnalyticsUser::orderby('id', 'desc')->paginate(10);
		return View::make($this->resourceView.'.user-form')->with(compact('analyticsUsers'));
	}

	public function forumForm()
	{
		$analyticsForums = AnalyticsForum::orderby('id', 'desc')->paginate(10);
		return View::make($this->resourceView.'.forum-form')->with(compact('analyticsForums'));
	}

	public function likeForm()
	{
		$analyticsLikes = AnalyticsLike::orderby('id', 'desc')->paginate(10);
		return View::make($this->resourceView.'.like-form')->with(compact('analyticsLikes'));
	}

}