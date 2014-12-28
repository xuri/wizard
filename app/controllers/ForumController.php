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
	 * getForumType Multi Pagination in a Single Page
	 * @param  string $type Category kind
	 * @return Return       Ajax
	 */
	public function getForumType($type)
	{
		$items_per_page = Input::get('per_pg', 10);

		if ($type == 'first') {
			$items			= ForumPost::where('category_id', 1)->orderBy('created_at' , 'desc')->paginate($items_per_page);
			$categoryCode	= 1;
			$editorCode		= 'cat1_editor';
		} else if ($type == 'second'){
			$items			= ForumPost::where('category_id', 2)->orderBy('created_at' , 'desc')->paginate($items_per_page);
			$categoryCode	= 2;
			$editorCode		= 'cat2_editor';
		} else {
			$items			= ForumPost::where('category_id', 3)->orderBy('created_at' , 'desc')->paginate($items_per_page);
			$categoryCode	= 3;
			$editorCode		= 'cat3_editor';
		}

		$view = View::make($this->resource.'.item-type')->with(compact('categoryCode', 'items', 'editorCode'));
		return $view;
		exit;
	}

	/**
	 * postNew Create new post
	 * @return Response Redirect back
	 */
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
				return Response::json(
					array(
						'success'		=> true,
						'success_info'	=> '发帖成功！',
						'post_content'	=> Input::get('content'),
						'post_id'		=> $post->id,
						'post_title'	=> Input::get('title'),
						'post_comments'	=> ForumComments::where('post_id', $post->id)->count(),
						'post_created'	=> date("m-d H:m",strtotime($post->created_at))
					)
				);
			} else {
				return Redirect::back()
					->withInput()
					->with('error', '发帖失败，请重试。');
			}
		} else {
			return Response::json(
					array(
						'fail'      => true,
						'errors'    => $validator->getMessageBag()->toArray()
					)
				);
		}
	}

	/**
	 * getShow Show single post
	 * @return Response View
	 */
	public function getShow($id)
	{
		$data		= ForumPost::where('id', $id)->first();
		$author		= User::where('id', $data->user_id)->first();
		$comments	= ForumComments::where('post_id', $id)->orderBy('created_at' , 'desc')->paginate(10);
		$floor		= 2;

		if (Request::ajax()) {
            return Response::json(View::make($this->resource.'.post-ajax')->with(compact('data', 'author', 'comments', 'floor'))->render());
        }

		return View::make($this->resource.'.post')->with(compact('data', 'author', 'comments', 'floor'));
	}

	/**
	 * postComment Create a comment
	 * @return Response View
	 */
	public function postComment($id)
	{
		// Select post type
		if(Input::get('type') == 'comments') // Post comments
		{
			// Get all form data.
			$data = Input::all();
			// Create validation rules
			$rules = array(
				'content'	=> 'required',
			);
			// Custom validation message
			$messages = array(
				'content.required'	=> '请输入评论内容。',
			);

			// Begin verification
			$validator		= Validator::make($data, $rules, $messages);
			if ($validator->passes())
			{
				$comment			= new ForumComments;
				$comment->post_id	= $id;
				$comment->content	= Input::get('content');
				$comment->user_id	= Auth::user()->id;
				$comment->floor		= ForumComments::where('post_id', $id)->count() + 2; // Calculate this comment in which floor
				if($comment->save())
				{
					return Response::json(
						array(
							'success'		=> true,
							'success_info'	=> '评论成功'
						)
					);
				} else {
					return Response::json(
						array(
							'fail'      => true
						)
					);
				}
			} else {
				// Validation fail
				return Response::json(
					array(
						'fail'      => true,
						'errors'    => $validator->getMessageBag()->toArray()
					)
				);
			}
		} else { // Post reply

			// Get all form data.
			$data = Input::all();
			// Create validation rules
			$rules = array(
				'reply_content'	=> 'required',
				'reply_id'		=> 'required',
				'comments_id'	=> 'required'
			);
			// Custom validation message
			$messages = array(
				'reply_content.required'	=> '请输入回复内容。',
			);
			// Begin verification
			$validator		= Validator::make($data, $rules, $messages);
			$reply_content	= str_replace('回复 '.Input::get('data_nickname').':', '', Input::get('reply_content'));

			if($validator->passes())
			{
				if($reply_content != '') // Verify again
				{
					$reply				= new ForumReply;
					$reply->content		= Input::get('reply_content');
					$reply->reply_id	= Input::get('reply_id');
					$reply->comments_id	= Input::get('comments_id');
					$reply->user_id		= Auth::user()->id;
					$reply->floor		= ForumReply::where('comments_id', Input::get('comments_id'))->count() + 1; // Calculate this reply in which floor
					if($reply->save())
					{
						return Response::json(
							array(
								'success'		=> true,
								'success_info'	=> '回复成功'
							)
						);
					} else {
						return Response::json(
							array(
								'error'			=> true,
								'error_info'	=> '回复失败，请重试！'
							)
						);
					}
				} else {
					return Response::json(
						array(
							'error'			=> true,
							'error_info'	=> '请输入回复内容。'
						)
					);
				}


			} else {
				// Validation fail
				return Response::json(
					array(
						'fail'      => true,
						'errors'    => $validator->getMessageBag()->toArray()
					)
				);
			}
		}
	}

}