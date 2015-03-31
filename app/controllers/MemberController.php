<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * PHP Version 5.5
 */

/**
 * Class for website members relationship management, such as add, accept, reject and block add friend request.
 *
 * @uses 		Laravel The PHP frameworks for web artisans http://laravel.com
 * @author 		Ri Xu http://xuri.me <xuri.me@gmail.com>
 * @copyright 	Copyright (c) Harbin Wizard Techonlogy Co., Ltd.
 * @link 		http://www.jinglingkj.com
 * @license   	Licensed under The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version 	Release: 0.1 2014-12-25
 *
 * Status Code Explanation
 *
 * status = 0 User send like, pending accept
 * status = 1 Receiver accept like, add friend relationship in chat system and start chat
 * status = 2 Receiver reject user, remove friend relationship in chat system
 * status = 3 Receiver block user, remove friend relationship in chat system
 * status = 4 Sender block receiver user, remove friend relationship in chat system
 *
 *
 * Notifications Code Explanation
 *
 * category = 1 Some user first like you
 * category = 2 Some user re-liked you
 * category = 3 Some user accept you like
 * category = 4 Some user reject you like
 * category = 5 Some user blocked you
 * category = 6 Some user comments your post in forum
 * category = 7 Some user reply your comments in forum
 * category = 8 System notifications to special user
 * category = 9 System notifacations to all users
 * category = 10 Some user recover blocked you
 *
 * Easemob Push Notifications Code Explanation
 *
 * category = 1 Some user first like you
 * category = 2 Some user re-liked you
 * category = 3 Some user accept you like
 * category = 4 Some user reject you like
 * category = 5 Some user blocked you
 * category = 6 Some user comments your post in forum
 * category = 7 Some user reply your comments in forum
 * category = 8 System notifications to special user
 * category = 9 System notifacations to all users
 * category = 10 Some user recover blocked you
 *
 * User Points Explanation
 *
 * Daily renew add 2 points
 * Like other users minus 1 point
 *
 */

class MemberController extends BaseController {

	/**
	 * Resource identification
	 * @var string
	 */
	protected $resource = 'members';

	/**
	 * View: Members
	 * @return Response
	 */
	public function index()
	{
		$query					= User::whereNotNull('portrait')
											->where('block', 0)
											->whereNotNull('nickname')
											->orderBy('updated_at', 'desc');
		// Ruled out not set tags user
		$query->whereHas('hasOneProfile', function($hasTagStr) {
					$hasTagStr->where('tag_str', '!=', ',');
				});

		$open_universities		= University::where('status', 2)->select('id', 'university')->get();
		$pending_universities	= University::where('status', 1)->select('id', 'university', 'open_at')->get();

		$university				= Input::get('university');
		$grade					= Input::get('grade');
		$sex					= Input::get('sex');

		$session_university		= Session::get('university');
		$session_grade			= Session::get('grade');
		$session_sex			= Session::get('sex');

		// University filter
		if($university) {
			if($university == 'all') {
				Session::forget('university');
			} elseif($university == 'others') {
				Session::forget('university');
				Session::put('university', 'others');
				$universities_list = University::where('status', 2)->select('university')->get()->toArray();
				isset($university) AND $query->whereNotIn('school', $universities_list);
			} else {
				Session::put('university', $university);
				isset($university) AND $query->where('school', $university);
			}
		} elseif($session_university) {

			// Session University filter
			if($session_university == 'all') {
				Session::forget('university');
			} elseif ($session_university == 'others') {
				$universities_list = University::where('status', 2)->select('university')->get()->toArray();
				isset($session_university) AND $query->whereNotIn('school', $universities_list);
			} else {
				isset($session_university) AND $query->where('school', $session_university);
			}
		}

		// Sex filter
		if($sex) {
			if($sex == 'all') {
				Session::forget('sex');
			} else {
				Session::put('sex', $sex);
				isset($sex) AND $query->where('sex', $sex);
			}
		} elseif($session_sex) {

			// Session Sex filter
			if($session_sex == 'all') {
				Session::forget('sex');
			} else {
				isset($session_sex) AND $query->where('sex', $session_sex);
			}
		}

		// Grade filter
		if($grade) {
			if($grade == 'all') {
				Session::forget('grade');
			} else {
				Session::forget('grade');
				Session::put('grade', $grade);
				isset($grade) AND $query->whereHas('hasOneProfile', function($profileQuery){
					$profileQuery->where('grade', '=', Input::get('grade'));
				});
			}
		} elseif($session_grade) {

			// Session Grade filter
			if($session_grade == 'all') {
				Session::forget('grade');
			} else {
				isset($session_grade) AND $query->whereHas('hasOneProfile', function($profileQuery){
					$profileQuery->where('grade', '=', Session::get('grade'));
				});
			}
		}

		$datas = $query->paginate(10);

		if (Request::ajax()) {
			return Response::json(View::make($this->resource.'.load-ajax')->with(compact('datas', 'pending_universities', 'open_universities'))->render());
		}

		return View::make($this->resource.'.index')->with(compact('datas', 'pending_universities', 'open_universities'));
	}

	/**
	 * View: Show info
	 * @param integer $id User ID
	 * @return Response
	 */
	public function show($id)
	{
		$data              = User::where('id', $id)->first();
		$profile           = Profile::where('user_id', $id)->first();
		$like              = Like::where('sender_id', Auth::user()->id)->where('receiver_id', $data->id)->first();
		$like_me           = Like::where('sender_id', $data->id)->where('receiver_id', Auth::user()->id)->first();

		// Get user's constellation
		$constellationInfo = getConstellation($profile->constellation);
		$tag_str           = explode(',', substr($profile->tag_str, 1));

		if($data->sex == 'M')
		{
			$sex = Lang::get('members/show.he');
		} else {
			$sex = Lang::get('members/show.she');
		}

		// Determine user renew status
		if($profile->crenew >= 30){
			$crenew = true;
		} else {
			$crenew = false;
		}

		return View::make('members.show')->with(compact('data', 'like', 'profile', 'constellationInfo', 'tag_str', 'sex', 'like_me', 'crenew'));
	}

	/**
	 * User like
	 * @param  int $id Like ID
	 * @return Resopnse
	 */
	public function like($id)
	{
		$status = Input::get('status');
		switch ($status) {
			case 'like':
				// Get all form data.
				$data = Input::all();
				// Create validation rules
				$rules = array(
					'answer'              => 'required|min:3',
				);
				// Custom validation message
				$messages = array(
					'answer.required'     => Lang::get('members/show.answer_warning'),
					'answer.min'          => '至少要写:min个字哦。',
				);

				// Begin verification
				$validator   = Validator::make($data, $rules, $messages);
				if ($validator->passes()) {
					if(Auth::user()->points > 0)
					{
						$have_like = Like::where('sender_id', Auth::user()->id)->where('receiver_id', $id)->first();
						if($have_like) // This user already sent like
						{
							$have_like->answer		= htmlentities(Input::get('answer'));
							$have_like->count		= $have_like->count + 1;
							Auth::user()->points	= Auth::user()->points - 1;
							if($have_like->save() && Auth::user()->save())
							{
								$notification	= Notification(2, Auth::user()->id, $id); // Some user re-liked you

								// Add push notifications for App client to queue
								Queue::push('LikeQueue', [
															'target'	=> $id,
															'action'	=> 2,
															'from'		=> Auth::user()->id,

															// Notification ID
															'id' 		=> e($notification->id),
															'content'	=> Auth::user()->nickname . '又追你了，快去查看一下吧',
															'sender_id'	=> e(Auth::user()->id),
															'portrait'	=> route('home') . '/' . 'portrait/' . Auth::user()->portrait,
															'nickname'	=> Auth::user()->nickname,
															'answer'	=> htmlentities(Input::get('answer'))
														]);

								return Redirect::route('account.sent')
									->withInput()
									->with('success', Lang::get('members/show.send_success'));
							}
						} else { // First like
							$like					= new Like();
							$like->sender_id		= Auth::user()->id;
							$like->receiver_id		= $id;
							$like->status			= 0; // User send like, pending accept
							$like->answer			= htmlentities(Input::get('answer'));
							$like->count			= 1;
							Auth::user()->points	= Auth::user()->points - 1;
							if($like->save() && Auth::user()->save())
							{
								$notification	= Notification(1, Auth::user()->id, $id); // Some user first like you

								// Add push notifications for App client to queue
								Queue::push('LikeQueue', [
															'target'	=> $id,
															'action'	=> 1,
															'from'		=> Auth::user()->id,

															// Notification ID
															'id'		=> e($notification->id),
															'content'	=> Auth::user()->nickname . '追你了，快去查看一下吧',
															'sender_id'	=> e(Auth::user()->id),
															'portrait'	=> route('home') . '/' . 'portrait/' . Auth::user()->portrait,
															'nickname'	=> Auth::user()->nickname,
															'answer'	=> htmlentities(Input::get('answer'))
														]);

								return Redirect::route('account.sent')
									->withInput()
									->with('success', Lang::get('members/show.send_success'));
							}
						}
					} else {
						return Redirect::back()
						->withInput()
						->with('error', Lang::get('members/index.points_require'));
					}
				} else { // Validation fail
					return Redirect::back()
						->withInput()
						->withErrors($validator);
				}
			break;
			case 'reject' :
				$like			= Like::where('sender_id', $id)->where('receiver_id', Auth::user()->id)->first();
				$like->status	= 2; // Receiver reject user, remove friend relationship in chat system
				if($like->save())
				{
					Notification(4, Auth::user()->id, $id); // Some user reject you like
					return Redirect::route('account.inbox')
						->withInput()
						->with('success', Lang::get('members/index.reject_success'));
				} else {
					return Redirect::route('account.inbox')
						->withInput()
						->with('error', Lang::get('members/index.error'));
				}
			case 'accept' :
				$like			= Like::where('sender_id', $id)->where('receiver_id', Auth::user()->id)->first();
				$like->status	= 1; // Receiver accept like

				$easemob		= getEasemob();

				// Add friend relationship in chat system and start chat
				Queue::push('AddFriendQueue', [
											'user_id'	=> Auth::user()->id,
											'friend_id'	=> $id,
										]);
				Queue::push('AddFriendQueue', [
											'user_id'	=> $id,
											'friend_id'	=> Auth::user()->id,
										]);

				if($like->save())
				{
					$notification = Notification(3, Auth::user()->id, $id); // Some user accept you like

					// Add push notifications for App client to queue
					Queue::push('LikeQueue', [
												'target'	=> $id,
												'action'	=> 3,
												'from'		=> Auth::user()->id,

												// Notification ID
												'id'		=> e($notification->id),
												'content'	=> Auth::user()->nickname . '接受了你的邀请，快去查看一下吧',
												'sender_id'	=> e(Auth::user()->id),
												'portrait'	=> route('home') . '/' . 'portrait/' . Auth::user()->portrait,
												'nickname'	=> e(Auth::user()->nickname),
												'answer'	=> null
											]);

					return Redirect::route('account.inbox')
						->withInput()
						->with('success', Lang::get('members/index.accept_success'));
				} else {
					return Redirect::route('account.inbox')
						->withInput()
						->with('error', Lang::get('members/index.accept_error'));
				}
			break;
			case 'block' :
				$like			= Like::where('sender_id', $id)->where('receiver_id', Auth::user()->id)->first();
				$like->status	= 3; // Receiver block user, remove friend relationship in chat system

				// Remove friend relationship in chat system
				Queue::push('DeleteFriendQueue', [
											'user_id'	=> Auth::user()->id,
											'block_id'	=> $id,
										]);
				Queue::push('DeleteFriendQueue', [
											'user_id'	=> $id,
											'block_id'	=> Auth::user()->id,
										]);
				if($like->save())
				{
					// Some user blocked you
					$notification = Notification(5, Auth::user()->id, $id);

					// Push notifications to App client
					// cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
					// 		'target_type'	=> 'users',
					// 		'target'		=> [$id],
					// 		'msg'			=> ['type' => 'cmd', 'action' => '5'],
					// 		'from'			=> Auth::user()->id,
					// 		'ext'			=> ['content' => User::where('id', Auth::user()->id)->first()->nickname.'把你加入了黑名单', 'id' => $notification->id]
					// 	])
					// 		->setHeader('content-type', 'application/json')
					// 		->setHeader('Accept', 'json')
					// 		->setHeader('Authorization', 'Bearer '.$easemob->token)
					// 		->setOptions([CURLOPT_VERBOSE => true])
					// 		->send();

					return Redirect::back()
						->withInput()
						->with('success', Lang::get('members/index.lock_success'));
				} else {
					return Redirect::back()
						->withInput()
						->with('error', Lang::get('members/index.lock_error'));
				}
			break;
			case 'sender_block' :
				$like			= Like::where('sender_id', Auth::user()->id)->where('receiver_id', $id)->first();

				// Sender block receiver user, remove friend relationship in chat system
				$like->status	= 4;

				// Remove friend relationship in chat system
				Queue::push('DeleteFriendQueue', [
											'user_id'	=> $id,
											'block_id'	=> Auth::user()->id,
										]);
				Queue::push('DeleteFriendQueue', [
											'user_id'	=> Auth::user()->id,
											'block_id'	=> $id,
										]);

				if($like->save())
				{
					Notification(5, Auth::user()->id, $id); // Some user blocked you
					return Redirect::back()
						->withInput()
						->with('success', Lang::get('members/index.lock_success'));
				} else {
					return Redirect::back()
						->withInput()
						->with('error', Lang::get('members/index.lock_error'));
				}
			break;
			case 'recover' :
				$like			= Like::where('sender_id', $id)->where('receiver_id', Auth::user()->id)->first();
				$like->status	= 0; // User send like, pending accept
				if($like->save())
				{
					Notification(10, Auth::user()->id, $id); // Some user recover blocked you
					return Redirect::back()
						->withInput()
						->with('success', Lang::get('members/index.unlock_success'));
				} else {
					return Redirect::back()
						->withInput()
						->with('error', Lang::get('members/index.unlock_error'));
				}
			break;
			case 'sender_recover' :
				$like			= Like::where('sender_id', Auth::user()->id)->where('receiver_id', $id)->first();
				$like->status	= 0; // User send like, pending accept
				if($like->save())
				{
					Notification(10, Auth::user()->id, $id); // Some user recover blocked you
					return Redirect::back()
						->withInput()
						->with('success', Lang::get('members/index.unlock_success'));
				} else {
					return Redirect::back()
						->withInput()
						->with('error', Lang::get('members/index.unlock_error'));
				}
			break;
		}
	}

}