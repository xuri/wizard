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
			$items			= ForumPost::where('block', false)->where('category_id', 1)->orderBy('created_at' , 'desc')->paginate($items_per_page);
			$categoryCode	= 1;
			$editorCode		= 'cat1_editor';
		} else if ($type == 'second'){
			$items			= ForumPost::where('block', false)->where('category_id', 2)->orderBy('created_at' , 'desc')->paginate($items_per_page);
			$categoryCode	= 2;
			$editorCode		= 'cat2_editor';
		} else {
			$items			= ForumPost::where('block', false)->where('category_id', 3)->orderBy('created_at' , 'desc')->paginate($items_per_page);
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
		$comments	= ForumComments::where('post_id', $id)->orderBy('created_at' , 'asc')->paginate(10);
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
		$forum_post = ForumPost::where('id', $id)->first();

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
					// Determine sender and receiver
					if(Auth::user()->id != $forum_post->user_id) {

						// Retrieve forum notifications of post author
						$post_author_notifications	= Notification::where('receiver_id', $forum_post->user_id)->whereIn('category', array(6, 7))->get();

						$easemob		= getEasemob();
						// Android Push notifications
						$push_notifications = cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages',
									[
										// Push notification to single user
										'target_type'	=> 'users',
										// Receiver user ID (in easemob)
										'target'		=> [$forum_post->user_id],
										// category = 6 Some user comments your post in forum (Get more info from app/controllers/MemberController.php)
										'msg'			=> ['type' => 'cmd', 'action' => '6'],
										// Sender user ID (in easemob)
										'from'			=> Auth::user()->id,
										// Notification body
										'ext'			=> [
																// Sender user ID
																'id'		=> Auth::user()->id,
																// Notification content
																'content'	=> '有人评论了你的帖子，快去看看吧',
																// Count unread notofications of receiver user
																'unread'	=> $post_author_notifications->count()
															]
									])
								->setHeader('content-type', 'application/json')
								->setHeader('Accept', 'json')
								->setHeader('Authorization', 'Bearer '.$easemob->token)
								->setOptions([CURLOPT_VERBOSE => true])
								->send();

						// Create notifications
						Notifications(6, Auth::user()->id, $forum_post->user_id, $forum_post->category_id, $id, $comment->id, null);
					}

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
			// Remove default string on reply textarea
			$reply_content	= str_replace('回复 '.Input::get('data_nickname').':', '', Input::get('reply_content'));

			if($validator->passes())
			{
				if($reply_content != null) // Verify again
				{
					$reply				= new ForumReply; // Create comments reply
					$reply->content		= Input::get('reply_content');
					$reply->reply_id	= Input::get('reply_id');
					$reply->comments_id	= Input::get('comments_id');
					$reply->user_id		= Auth::user()->id;
					$reply->floor		= ForumReply::where('comments_id', Input::get('comments_id'))->count() + 1; // Calculate this reply in which floor
					if($reply->save())
					{
						// Retrieve comments
						$comment						= ForumComments::where('id', Input::get('comments_id'))->first();
						// Retrieve author of comment
						$comment_author					= User::where('id', $comment->user_id)->first();
						// Retrieve forum notifications of comment author
						$comment_author_notifications	= Notification::where('receiver_id', $comment_author->id)->whereIn('category', array(6, 7))->get();

						// Determine sender and receiver
						if(Auth::user()->id != $comment_author->id) {
							$easemob			= getEasemob();
							// Android Push notifications
							$push_notifications = cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages',
										[
											// Push notification to single user
											'target_type'	=> 'users',
											// Receiver user ID (in easemob)
											'target'		=> [$comment_author->id],
											// category = 7 Some user reply your comments in forum (Get more info from app/controllers/MemberController.php)
											'msg'			=> ['type' => 'cmd', 'action' => '7'],
											// Sender user ID (in easemob)
											'from'			=> Auth::user()->id,
											// Notification body
											'ext'			=> [
																	// Sender user ID
																	'id'		=> Auth::user()->id,
																	// Notification content
																	'content'	=> '有人回复了你的评论，快去看看吧',
																	// Count unread notofications of receiver user
																	'unread'	=> $comment_author_notifications->count()
																]
										])
									->setHeader('content-type', 'application/json')
									->setHeader('Accept', 'json')
									->setHeader('Authorization', 'Bearer '.$easemob->token)
									->setOptions([CURLOPT_VERBOSE => true])
									->send();

							// Create notifications
							Notifications(7, Auth::user()->id, $comment_author->id, $forum_post->category_id, $id, Input::get('comments_id'), $reply->id);
						}

						// Reply success
						return Response::json(
							array(
								'success'		=> true,
								'success_info'	=> '回复成功' // Success information
							)
						);
					} else {
						// Reply fail
						return Response::json(
							array(
								'error'			=> true,
								'error_info'	=> '回复失败，请重试！' // Error infrmation
							)
						);
					}
				} else {
					return Response::json(
						// Reply fail
						array(
							'error'			=> true,
							'error_info'	=> '请输入回复内容。' // Error infrmation
						)
					);
				}
			} else {
				// Validation fail
				return Response::json(
					array(
						'fail'      => true,
						'errors'    => $validator->getMessageBag()->toArray() // Error infrmation to array
					)
				);
			} // End of post reply
		} // End of select post type
	} // End of postComments

}