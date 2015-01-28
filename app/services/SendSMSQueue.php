<?php

class SendSMSQueue extends BaseController{

	public function fire($job, $data)
	{

		$phone			= $data['phone'];
		$verify_code	= $data['verify_code'];

		include_once( app_path('api/sms/SendTemplateSMS.php') );
		sendTemplateSMS($phone, array($verify_code,'5'), "11702");

		$job->delete();
	}
}