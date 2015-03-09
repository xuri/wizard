<?php

class SendLikesNotifySMSQueue extends BaseController{

	public function fire($job, $data)
	{

		$phone	= $data['phone'];
		$count	= $data['count'];

		include_once( app_path('api/sms/SendTemplateSMS.php') );
		sendTemplateSMS($phone, array($count,'5'), "11702");

		$job->delete();
	}
}