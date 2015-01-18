<?php

class ForumQueue extends BaseController{

	public function fire($job, $data)
	{
		$target		= $data['target'];
		$action		= $data['action'];
		$from		= $data['from'];
		$content	= $data['content'];
		$id			= $data['id'];
		$unread		= $data['unread'];

		$easemob	= getEasemob();

		// Android Push notifications
		$push_notifications = cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages',
					[
						// Push notification to single user
						'target_type'	=> 'users',

						// Receiver user ID (in easemob)
						'target'		=> [$target],

						// category = 6 Some user comments your post in forum (Get more info from app/controllers/MemberController.php), category = 7 Some user reply your comments in forum (Get more info from app/controllers/MemberController.php)
						'msg'			=> ['type' => 'cmd', 'action' => $action],

						// Sender user ID (in easemob)
						'from'			=> $from,

						// Notification body
						'ext'			=> [
												// Sender user ID
												'id'		=> $id,

												// Notification content
												'content'	=> $content,

												// Count unread notofications of receiver user
												'unread'	=> $unread
											]
					])
				->setHeader('content-type', 'application/json')
				->setHeader('Accept', 'json')
				->setHeader('Authorization', 'Bearer '.$easemob->token)
				->setOptions([CURLOPT_VERBOSE => true])
				->send();

		$job->delete();
	}
}