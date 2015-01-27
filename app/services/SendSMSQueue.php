<?php

class SendSMSQueue extends BaseController{

	public function fire($job, $data)
	{

		$phone		= $data['phone'];

		include_once( app_path('api/sms/SendTemplateSMS.php') );
		sendTemplateSMS($phone, array($verify_code,'5'), "1");

		$job->delete();
	}
}