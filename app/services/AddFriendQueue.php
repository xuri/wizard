<?php

class AddFriendQueue extends BaseController{

	public function fire($job, $data)
	{
		$user_id	= $data['user_id'];
		$friend_id	= $data['friend_id'];

		$easemob	= getEasemob();

		// Add friend relationship in chat system and start chat
		cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users/' . $user_id . '/contacts/users/' . $friend_id)
				->setHeader('content-type', 'application/json')
				->setHeader('Accept', 'json')
				->setHeader('Authorization', 'Bearer '.$easemob->token)
				->setOptions([CURLOPT_VERBOSE => true])
				->send();

		$job->delete();
	}
}