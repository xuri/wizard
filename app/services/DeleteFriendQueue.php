<?php

class DeleteFriendQueue extends BaseController{

	public function fire($job, $data)
	{
		$user_id	= $data['user_id'];
		$block_id	= $data['block_id'];

		$easemob	= getEasemob();

		// Remove friend relationship in chat system
		cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users/' . $user_id . '/contacts/users/' . $block_id)
				->setHeader('content-type', 'application/json')
				->setHeader('Accept', 'json')
				->setHeader('Authorization', 'Bearer '.$easemob->token)
				->setOptions([CURLOPT_VERBOSE => true])
				->setOptions([CURLOPT_CUSTOMREQUEST => 'DELETE'])
				->send();

		$job->delete();
	}
}