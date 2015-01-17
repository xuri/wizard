<?php

class CurlQueue extends BaseController{

	public function fire($job, $data)
	{

		// Add user success and chat Register
		$easemob			= getEasemob();
		// Push notifications to App client
		$data = cURL::newJsonRequest($data)
				->setHeader('content-type', 'application/json')
				->setHeader('Accept', 'json')
				->setHeader('Authorization', 'Bearer '.$easemob->token)
				->setOptions([CURLOPT_VERBOSE => true]);
				->send();
		$job->delete();
	}
}