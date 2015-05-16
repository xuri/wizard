<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Class for posts, comments and reply in forum.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @license   	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	Release: 0.1 2014-12-25
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
		// Determine forum open status
		if(ForumCategories::where('id', 1)->first()->open == 1) {

			// Forum is open
			$cat2_status = 1;
			$cat3_status = 1;
		} else {
			if(Auth::user()->is_admin){
				$cat2_status = true;
				$cat3_status = true;
			} else {
				// Determine user sex
				if(Auth::user()->sex == 'M') {

					// Forum is close and male user can't access category 3
					$cat2_status = true;
					$cat3_status = false;
				} else {

					// Forum is close and female user can't access category 2
					$cat2_status = false;
					$cat3_status = true;
				}
			}
		}
		return View::make($this->resource.'.index')->with(compact('cat2_status', 'cat3_status'));
	}

	/**
	 * getForumType Multi Pagination in a Single Page
	 * @param  string $type Category kind
	 * @return Return       Ajax
	 */
	public function getForumType($type)
	{
		$items_per_page = Input::get('per_pg', 10);

		// Determine forum open status
		if(ForumCategories::where('id', 1)->first()->open == 1) {
			// Forum is opening
			if ($type == 'first') {
				$tops			= ForumPost::where('block', false)
										->where('category_id', 1)
										->where('top', 1)
										->orderBy('updated_at' , 'desc')
										->paginate($items_per_page);

				$items			= ForumPost::where('block', false)
										->where('category_id', 1)
										->where('top', 0)
										->orderBy('updated_at' , 'desc')
										->paginate($items_per_page);
				$categoryCode	= 1;
				$editorCode		= 'cat1_editor';
			} else if ($type == 'second'){
				$tops			= ForumPost::where('block', false)
										->where('category_id', 2)
										->where('top', 1)
										->orderBy('updated_at' , 'desc')
										->paginate($items_per_page);

				$items			= ForumPost::where('block', false)
										->where('category_id', 2)
										->where('top', 0)
										->orderBy('updated_at' , 'desc')
										->paginate($items_per_page);

				$categoryCode	= 2;
				$editorCode		= 'cat2_editor';
			} else {
				$tops			= ForumPost::where('block', false)
										->where('category_id', 3)
										->where('top', 1)
										->orderBy('updated_at' , 'desc')
										->paginate($items_per_page);

				$items			= ForumPost::where('block', false)
										->where('category_id', 3)
										->where('top', 0)
										->orderBy('updated_at' , 'desc')
										->paginate($items_per_page);
				$categoryCode	= 3;
				$editorCode		= 'cat3_editor';
			}
		} else {
			if(Auth::user()->is_admin) {
				// Forum is opening
				if ($type == 'first') {
					$tops			= ForumPost::where('block', false)
											->where('category_id', 1)
											->where('top', 1)
											->orderBy('updated_at' , 'desc')
											->paginate($items_per_page);

					$items			= ForumPost::where('block', false)
											->where('category_id', 1)
											->where('top', 0)
											->orderBy('updated_at' , 'desc')
											->paginate($items_per_page);
					$categoryCode	= 1;
					$editorCode		= 'cat1_editor';
				} else if ($type == 'second'){
					$tops			= ForumPost::where('block', false)
											->where('category_id', 2)
											->where('top', 1)
											->orderBy('updated_at' , 'desc')
											->paginate($items_per_page);

					$items			= ForumPost::where('block', false)
											->where('category_id', 2)
											->where('top', 0)
											->orderBy('updated_at' , 'desc')
											->paginate($items_per_page);

					$categoryCode	= 2;
					$editorCode		= 'cat2_editor';
				} else {
					$tops			= ForumPost::where('block', false)
											->where('category_id', 3)
											->where('top', 1)
											->orderBy('updated_at' , 'desc')
											->paginate($items_per_page);

					$items			= ForumPost::where('block', false)
											->where('category_id', 3)
											->where('top', 0)
											->orderBy('updated_at' , 'desc')
											->paginate($items_per_page);
					$categoryCode	= 3;
					$editorCode		= 'cat3_editor';
				}
			} else {
				// Determine user sex
				if(Auth::user()->sex == 'M') {
					// Forum is closed only show category 1 and 2
					if ($type == 'first') {
						$tops			= ForumPost::where('block', false)
												->where('category_id', 1)
												->where('top', 1)
												->orderBy('updated_at' , 'desc')
												->paginate($items_per_page);

						$items			= ForumPost::where('block', false)
												->where('category_id', 1)
												->where('top', 0)
												->orderBy('updated_at' , 'desc')
												->paginate($items_per_page);
						$categoryCode	= 1;
						$editorCode		= 'cat1_editor';
					} else if ($type == 'second'){
						$tops			= ForumPost::where('block', false)
												->where('category_id', 2)
												->where('top', 1)
												->orderBy('updated_at' , 'desc')
												->paginate($items_per_page);

						$items			= ForumPost::where('block', false)
												->where('category_id', 2)
												->where('top', 0)
												->orderBy('updated_at' , 'desc')
												->paginate($items_per_page);
						$categoryCode	= 2;
						$editorCode		= 'cat2_editor';
					} else {
						$items			= null;
						$categoryCode	= 3;
						$editorCode		= 'cat3_editor';
					}
				} else {
					// Forum is closed only show category 1 and 3
					if ($type == 'first') {
						$tops			= ForumPost::where('block', false)
												->where('category_id', 1)
												->where('top', 1)
												->orderBy('updated_at' , 'desc')
												->paginate($items_per_page);

						$items			= ForumPost::where('block', false)
												->where('category_id', 1)
												->where('top', 0)
												->orderBy('updated_at' , 'desc')
												->paginate($items_per_page);
						$categoryCode	= 1;
						$editorCode		= 'cat1_editor';
					} else if ($type == 'second'){
						$items			= null;
						$categoryCode	= 2;
						$editorCode		= 'cat2_editor';
					} else {
						$tops			= ForumPost::where('block', false)
												->where('category_id', 3)
												->where('top', 1)
												->orderBy('updated_at' , 'desc')
												->paginate($items_per_page);

						$items			= ForumPost::where('block', false)
												->where('category_id', 3)
												->where('top', 0)
												->orderBy('updated_at' , 'desc')
												->paginate($items_per_page);
						$categoryCode	= 3;
						$editorCode		= 'cat3_editor';
					}
				}
			}
		}

		$view = View::make($this->resource.'.item-type')->with(compact('categoryCode', 'tops', 'items', 'editorCode'));
		return $view;
		exit;
	}

	/**
	 * postNew Create new post
	 * @return Response Redirect back
	 */
	public function postNew()
	{
		// Determin user block status
		if(Auth::user()->block == 1) {

			// User is blocked forbidden post
			return Response::json(
				array(
					'fail'      => true,
					'errors'    => array('title' => Lang::get('forum/index.block_post'))
				)
			);
		} else {
			// Get all form data.
			$data = Input::all();
			// Create validation rules
			$rules = array(
				'title'		=> 'required|max:30',
				'content'	=> 'required',
			);

			// Custom validation message
			$messages = array(
				'title.required'	=> Lang::get('forum/index.title_required'),
				'title.max'			=> '帖子标题不超过:max个字。',
				'content.required'	=> Lang::get('forum/index.content_required')
			);

			// Begin verification
			$validator		= Validator::make($data, $rules, $messages);
			if ($validator->passes())
			{
				// Determin repeat post
				$posts_exist = ForumPost::where('user_id', Auth::user()->id)
								->where('title', htmlentities(Input::get('title')))
								->where('category_id', Input::get('category_id'))
								->where('content', Input::get('content'))
								->where('created_at', '>=', Carbon::today())
								->count();

				if($posts_exist >= 1) {

					// User repeat post
					return Response::json(
						array(
							'fail'      => true,
							'errors'    => array('title' => Lang::get('forum/index.repeat_post'))
						)
					);
				} else {
					$post				= new ForumPost;
					$post->category_id	= Input::get('category_id');
					$post->title		= htmlentities(Input::get('title'));
					$post->user_id		= Auth::user()->id;
					$post->content		= Input::get('content');
					if($post->save())
					{
						// Using expression get all picture attachments (Only with pictures stored on this server.)
						preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $post->content, $match );
						$thumbnails = join(',', array_pop($match));

						$i = 0;
						$post_thumbnails = null;
						foreach($match[0] as $thumbnail){
							$post_thumbnails = '<a ' . str_replace('_src=', 'href=', $thumbnail) . ' class="fancybox" rel="gallery5"><img class="post_thumbnails" ' . str_replace('_src', 'src', $thumbnail) . ' /></a>' . $post_thumbnails;
						++$i;
						if($i == 3) break;
						}

						return Response::json(
							array(
								'success'			=> true,
								'success_info'		=> Lang::get('forum/index.post_success'),
								'post_content'		=> badWordsFilter(str_ireplace("\n", '', getplaintextintrofromhtml($post->content, 200))),
								'post_id'			=> $post->id,
								'post_title'		=> htmlentities(Input::get('title')),
								'post_comments'		=> ForumComments::where('post_id', $post->id)->where('block', false)->count(),
								'post_thumbnails'	=> $post_thumbnails,
								'post_created'		=> date("m-d H:m",strtotime($post->created_at))
							)
						);
					} else {
						return Redirect::back()
							->withInput()
							->with('error', Lang::get('forum/index.post_error'));
					}
				}
			} else {
				return Response::json(
						array(
							'fail'      => true,
							'errors'    => $validator->getMessageBag()->toArray()
						)
					);
			}
		} // End of determin user block status
	}

	/**
	 * getShow Show single post
	 * @param integer $id Post ID in forum
	 * @return Response View
	 */
	public function getShow($id)
	{
		$data			= ForumPost::where('id', $id)->first();
		$author			= User::where('id', $data->user_id)->first();
		$comments		= ForumComments::where('post_id', $id)
							->orderBy('created_at' , 'asc')
							->where('block', false)
							->paginate(10);
		$floor			= 2;

		// Get user's profile
		$author_profile	= Profile::where('user_id', $data->user_id)->first();

		if (Request::ajax()) {

			// Calculate floor number for Ajax pagination
			$floor = (Input::get('page') - 1) * 10 + 2;
			return Response::json(View::make($this->resource.'.post-ajax')->with(compact('data', 'author', 'author_profile', 'comments', 'floor'))->render());
		}

		return View::make($this->resource.'.post')->with(compact('data', 'author', 'author_profile', 'comments', 'floor'));
	}

	/**
	 * postComment Create a comment
	 * @param integer $id Post ID in forum
	 * @return Response View
	 */
	public function postComment($id)
	{
		// Determin user block status
		if(Auth::user()->block == 1) {

			// User is blocked forbidden post
			return Response::json(
				array(
					'error'			=> true,
					'error_info'	=> Lang::get('forum/index.block_post'), // Error infrmation
					'fail'			=> true,
					'errors'		=> array('content' => Lang::get('forum/index.block_post'))
				)
			);
		} else {
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
					'content.required'	=> Lang::get('forum/index.comments_required'),
				);

				// Begin verification
				$validator		= Validator::make($data, $rules, $messages);
				if ($validator->passes())
				{
					// Determin repeat comment
					$comment_exist = ForumComments::where('user_id', Auth::user()->id)
									->where('post_id', $id)
									->where('content', Input::get('content'))
									->where('created_at', '>=', Carbon::today())
									->count();

					if($comment_exist >= 1) {

						// Rpeat comment
						return Response::json(
							array(
								'fail'		=> true,
								'errors'	=> array('content' => Lang::get('forum/index.repeat_comment')) // Error infrmation
							)
						);

					} else {
						$forum_post->updated_at	= Carbon::now();
						$forum_post->save();
						$comment				= new ForumComments;
						$comment->post_id		= $id;
						$comment->content		= Input::get('content');
						$comment->user_id		= Auth::user()->id;

						// Calculate this comment in which floor
						$comment->floor			= ForumComments::where('post_id', $id)->where('block', false)->count() + 2;


						if($comment->save())
						{
							// Determine sender and receiver
							if(Auth::user()->id != $forum_post->user_id) {

								// Retrieve forum notifications of post author
								$post_author_notifications	= Notification::where('receiver_id', $forum_post->user_id)->whereIn('category', array(6, 7))->get();

								// Add push notifications for App client to queue
								Queue::push('ForumQueue', [
															'target'	=> $forum_post->user_id,
															'action'	=> 6,
															'from'		=> Auth::user()->id,
															// Notification content
															'content'	=> '有人评论了你的帖子，快去看看吧',
															// Sender user ID
															'id'		=> Auth::user()->id,
															// Count unread notofications of receiver user
															'unread'	=> $post_author_notifications->count()
														]);

								// Create notifications
								Notifications(6, Auth::user()->id, $forum_post->user_id, $forum_post->category_id, $id, $comment->id, null);
							}

							return Response::json(
								array(
									'success'		=> true,
									'success_info'	=> Lang::get('forum/index.comments_success')
								)
							);
						} else {
							return Response::json(
								array(
									'fail'      => true
								)
							);
						}
					} // End of repeat comment check
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
					'reply_content.required'	=> Lang::get('forum/index.reply_required'),
				);

				// Begin verification
				$validator		= Validator::make($data, $rules, $messages);

				// Remove default string on reply textarea
				$reply_content	= str_replace('回复 ' . Input::get('data_nickname') . ':', '', htmlentities(Input::get('reply_content')));

				if($validator->passes())
				{
					if($reply_content != null) // Verify again
					{
						// Determin repeat reply
						$reply_exist = ForumReply::where('user_id', Auth::user()->id)
										->where('reply_id', Input::get('reply_id'))
										->where('comments_id', Input::get('comments_id'))
										->where('content', htmlentities(Input::get('reply_content')))
										->where('created_at', '>=', Carbon::today())
										->count();

						if($reply_exist >= 1) {

							// Rpeat reply
							return Response::json(
								array(
									'error'			=> true,
									'error_info'	=> Lang::get('forum/index.repeat_reply') // Error infrmation
								)
							);

						} else {
							$reply				= new ForumReply; // Create comments reply
							$reply->content		= htmlentities(Input::get('reply_content'));
							$reply->reply_id	= Input::get('reply_id');
							$reply->comments_id	= Input::get('comments_id');
							$reply->user_id		= Auth::user()->id;
							$reply->floor		= ForumReply::where('comments_id', Input::get('comments_id'))->where('block', false)->count() + 1; // Calculate this reply in which floor
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

									// Add push notifications for App client to queue
									Queue::push('ForumQueue', [
																'target'	=> $comment_author->id,
																// category = 7 Some user reply your comments in forum (Get more info from app/controllers/MemberController.php)
																'action'	=> 7,
																// Sender user ID
																'from'		=> Auth::user()->id,
																// Notification content
																'content'	=> '有人回复了你的评论，快去看看吧',
																// Sender user ID
																'id'		=> Auth::user()->id,
																// Count unread notofications of receiver user
																'unread'	=> $comment_author_notifications->count()
															]);

									// Create notifications
									Notifications(7, Auth::user()->id, $comment_author->id, $forum_post->category_id, $id, Input::get('comments_id'), $reply->id);
								}

								// Reply success
								return Response::json(
									array(
										'success'		=> true,
										'success_info'	=> Lang::get('forum/index.reply_success') // Success information
									)
								);
							} else {
								// Reply fail
								return Response::json(
									array(
										'error'			=> true,
										'error_info'	=> Lang::get('forum/index.reply_error') // Error infrmation
									)
								);
							}
						} // End of repeat reply check
					} else {
						return Response::json(
							// Reply fail
							array(
								'error'			=> true,
								'error_info'	=> Lang::get('forum/index.reply_required') // Error infrmation
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
	} // End of determin user block status

}