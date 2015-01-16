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

/**
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
		$query					= User::whereNotNull('portrait')->orderBy('created_at', 'desc');
		$open_universities		= University::where('status', 2)->select('id', 'university')->get();
		$pending_universities	= University::where('status', 1)->select('id', 'university', 'created_at')->get();

		$university				= Input::get('university');
		$grade					= Input::get('grade');
		$sex					= Input::get('sex');

		// University filter
		if($university) {
			if($university == 'others') {
				$universities_list = University::where('status', 2)->select('university')->get()->toArray();
				isset($university) AND $query->whereNotIn('school', array($universities_list));
			} else {
				isset($university) AND $query->where('school', $university);
			}
		}

		// Sex filter
		if($sex) {
			isset($sex) AND $query->where('sex', $sex);
		}

		// Grade filter
		if($grade) {
			isset($grade) AND $query->whereHas('hasOneProfile', function($profileQuery){
				$profileQuery->where('grade', '=', Input::get('grade'));
			});
		}

		$datas = $query->paginate(10);

		if (Request::ajax()) {
			return Response::json(View::make($this->resource.'.load-ajax')->with(compact('datas', 'pending_universities', 'open_universities'))->render());
		}

		return View::make($this->resource.'.index')->with(compact('datas', 'pending_universities', 'open_universities'));
	}

	/**
	 * View: Show info
	 * @return Response
	 */
	public function show($id)
	{
		$data              = User::where('id', $id)->first();
		$profile           = Profile::where('user_id', $id)->first();
		$like              = Like::where('sender_id', Auth::user()->id)->where('receiver_id', $data->id)->first();
		$like_me           = Like::where('sender_id', $data->id)->where('receiver_id', Auth::user()->id)->first();
		$constellationInfo = getConstellation($profile->constellation); // Get user's constellation
		$tag_str           = explode(',', substr($profile->tag_str, 1));
		if($data->sex == 'M')
		{
			$sex = '他';
		} else {
			$sex = '她';
		}
		return View::make('members.show')->with(compact('data', 'like', 'profile', 'constellationInfo', 'tag_str', 'sex','like_me'));
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
					'answer.required'     => '请回答爱情考验问题。',
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
							$have_like->answer		= Input::get('answer');
							$have_like->count		= $have_like->count + 1;
							Auth::user()->points	= Auth::user()->points - 1;
							if($have_like->save() && Auth::user()->save())
							{
								$notification	= Notification(2, Auth::user()->id, $id); // Some user re-liked you
								$easemob		= getEasemob();
								// Push notifications to App client
								cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
										'target_type'	=> 'users',
										'target'		=> [$id],
										'msg'			=> ['type' => 'cmd', 'action' => '2'],
										'from'			=> Auth::user()->id,
										'ext'			=> ['content' => '用户'.Auth::user()->nickname.'再次追你了', 'id' => Auth::user()->id]
									])
										->setHeader('content-type', 'application/json')
										->setHeader('Accept', 'json')
										->setHeader('Authorization', 'Bearer '.$easemob->token)
										->setOptions([CURLOPT_VERBOSE => true])
										->send();
								return Redirect::route('account.sent')
								->withInput()
								->with('success', '发送成功，静待缘分到来吧。');
							}
						} else { // First like
							$like					= new Like();
							$like->sender_id		= Auth::user()->id;
							$like->receiver_id		= $id;
							$like->status			= 0; // User send like, pending accept
							$like->answer			= Input::get('answer');
							$like->count			= 1;
							Auth::user()->points	= Auth::user()->points - 1;
							if($like->save() && Auth::user()->save())
							{
								$notification	= Notification(1, Auth::user()->id, $id); // Some user first like you
								$easemob		= getEasemob();
								// Push notifications to App client
								cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
										'target_type'	=> 'users',
										'target'		=> [$id],
										'msg'			=> ['type' => 'cmd', 'action' => '1'],
										'from'			=> Auth::user()->id,
										'ext'			=> ['content' => '用户'.Auth::user()->nickname.'追你了', 'id' => Auth::user()->id]
									])
										->setHeader('content-type', 'application/json')
										->setHeader('Accept', 'json')
										->setHeader('Authorization', 'Bearer '.$easemob->token)
										->setOptions([CURLOPT_VERBOSE => true])
										->send();
								return Redirect::route('account.sent')
									->withInput()
									->with('success', '发送成功，静待缘分到来吧。');
							}
						}
					} else {
						return Redirect::back()
						->withInput()
						->with('error', '你的积分不足，每天签到可获取积分哦。');
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
						->with('success', '你已经拒绝对方邀请。');
				} else {
					return Redirect::route('account.inbox')
						->withInput()
						->with('error', '系统发生错误。');
				}
			case 'accept' :
				$like			= Like::where('sender_id', $id)->where('receiver_id', Auth::user()->id)->first();
				$like->status	= 1; // Receiver accept like

				$easemob		= getEasemob();
				// Add friend relationship in chat system and start chat
				cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users/'.Auth::user()->id.'/contacts/users/'.$id)
						->setHeader('content-type', 'application/json')
						->setHeader('Accept', 'json')
						->setHeader('Authorization', 'Bearer '.$easemob->token)
						->setOptions([CURLOPT_VERBOSE => true])
						->send();
				cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users/'.$id.'/contacts/users/'.Auth::user()->id)
						->setHeader('content-type', 'application/json')
						->setHeader('Accept', 'json')
						->setHeader('Authorization', 'Bearer '.$easemob->token)
						->setOptions([CURLOPT_VERBOSE => true])
						->send();
				if($like->save())
				{
					Notification(3, Auth::user()->id, $id); // Some user accept you like
					return Redirect::route('account.inbox')
						->withInput()
						->with('success', '添加好友成功！');
				} else {
					return Redirect::route('account.inbox')
						->withInput()
						->with('error', '添加好友失败，请重试！');
				}
			break;
			case 'block' :
				$like			= Like::where('sender_id', $id)->where('receiver_id', Auth::user()->id)->first();
				$like->status	= 3; // Receiver block user, remove friend relationship in chat system

				$easemob		= getEasemob();
				// Remove friend relationship in chat system
				cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users/'.Auth::user()->id.'/contacts/users/'.$id)
						->setHeader('content-type', 'application/json')
						->setHeader('Accept', 'json')
						->setHeader('Authorization', 'Bearer '.$easemob->token)
						->setOptions([CURLOPT_VERBOSE => true])
						->setOptions([CURLOPT_CUSTOMREQUEST => 'DELETE'])
						->send();
				cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users/'.$id.'/contacts/users/'.Auth::user()->id)
						->setHeader('content-type', 'application/json')
						->setHeader('Accept', 'json')
						->setHeader('Authorization', 'Bearer '.$easemob->token)
						->setOptions([CURLOPT_VERBOSE => true])
						->setOptions([CURLOPT_CUSTOMREQUEST => 'DELETE'])
						->send();
				if($like->save())
				{
					$notification = Notification(5, Auth::user()->id, $id); // Some user blocked you
					// Push notifications to App client
					cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
							'target_type'	=> 'users',
							'target'		=> [$id],
							'msg'			=> ['type' => 'cmd', 'action' => '5'],
							'from'			=> $receiver_id,
							'ext'			=> ['content' => User::where('id', $receiver_id)->first()->nickname.'把你加入了黑名单', 'id' => $notification->id]
						])
							->setHeader('content-type', 'application/json')
							->setHeader('Accept', 'json')
							->setHeader('Authorization', 'Bearer '.$easemob->token)
							->setOptions([CURLOPT_VERBOSE => true])
							->send();
					return Redirect::back()
						->withInput()
						->with('success', '拉黑成功。');
				} else {
					return Redirect::back()
						->withInput()
						->with('error', '拉黑失败，请重试。');
				}
			break;
			case 'sender_block' :
				$like			= Like::where('sender_id', Auth::user()->id)->where('receiver_id', $id)->first();
				$like->status	= 4; // Sender block receiver user, remove friend relationship in chat system

				$easemob		= getEasemob();
				// Remove friend relationship in chat system
				$regChat		= cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users/'.Auth::user()->id.'/contacts/users/'.$id)
						->setHeader('content-type', 'application/json')
						->setHeader('Accept', 'json')
						->setHeader('Authorization', 'Bearer '.$easemob->token)
						->setOptions([CURLOPT_VERBOSE => true])
						->setOptions([CURLOPT_CUSTOMREQUEST => 'DELETE'])
						->send();

				if($like->save())
				{
					Notification(5, Auth::user()->id, $id); // Some user blocked you
					return Redirect::back()
						->withInput()
						->with('success', '拉黑成功。');
				} else {
					return Redirect::back()
						->withInput()
						->with('error', '拉黑失败，请重试。');
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
						->with('success', '取消拉黑成功。');
				} else {
					return Redirect::back()
						->withInput()
						->with('error', '取消拉黑失败，请重试。');
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
						->with('success', '取消拉黑成功。');
				} else {
					return Redirect::back()
						->withInput()
						->with('error', '取消拉黑失败，请重试。');
				}
			break;
		}
	}

}