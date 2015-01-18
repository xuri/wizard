<?php

class LikeQueue extends BaseController{

	public function fire($job, $data)
	{

		$target		= $data['target'];
		$action		= $data['action'];
		$from		= $data['from'];
		$content	= $data['content'];
		$id			= $data['id'];
		$portrait	= $data['portrait'];
		$nickname	= $data['nickname'];
		$answer		= $data['answer'];

		$easemob	= getEasemob();
		// Push notifications to App client
		cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
				'target_type'	=> 'users',
				'target'		=> [$target],
				'msg'			=> ['type' => 'cmd', 'action' => $action],
				'from'			=> $from,
				'ext'			=> [
										'content'	=> $content,
										'id'		=> $id,
										'portrait'	=> $portrait,
										'nickname'	=> $nickname,
										'answer'	=> $answer
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