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

class ForumController extends BaseController {

	/**
	 * Resource identification
	 * @var string
	 */
	protected $resource = 'forum';

	/**
	 * View: Forum
	 * @return Response
	 */

	public function getIndex()
	{
		return View::make($this->resource.'.index');
	}

	/**
	 * [getShow description]
	 * @return [type] [description]
	 */
	public function getShow()
	{
		return View::make($this->resource.'.post');
	}

	public function postNew()
	{
		// Get all form data.
		$data = Input::all();
		// Create validation rules
		$rules = array(
			'title'		=> 'required',
			'content'	=> 'required',
		);
		// Custom validation message
		$messages = array(
			'title.required'	=> '请填写帖子标题。',
			'content.required'	=> '请输入内容。',
		);

		// Begin verification
		$validator		= Validator::make($data, $rules, $messages);
		if ($validator->passes())
		{
			$post				= new ForumPost;
			$post->category_id	= Input::get('category_id');
			$post->title		= Input::get('title');
			$post->user_id		= Auth::user()->id;
			$post->content		= Input::get('content');
			if($post->save())
			{
				return Redirect::back()
					->with('success', '发帖成功。');
			} else {
				return Redirect::back()
					->withInput()
					->with('error', '发帖失败，请重试。');
			}
		} else {
			// Validation fail
			return Redirect::back()
				->withInput()
				->withErrors($validator);
		}
	}
}