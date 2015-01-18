<?php

class SystemNotificationsQueue extends BaseController{

	public function fire($job, $data)
	{

		$id						= $data['id'];
		$notification_id		= $data['notification_id'];
		$created_at				= $data['created_at'];
		$system_notification	= $data['system_notification'];

		$easemob				= getEasemob();
		// Push notifications to App client
		cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
				'target_type'	=> 'users',
				'target'		=> [$id],
				'msg'			=> ['type' => 'cmd', 'action' => '8'],
				'from'			=> '0',
				'ext'			=> [
										'content'		=> '系统消息：'. $system_notification,
										'id'			=> $notification_id,
										'created_at'	=> $created_at
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