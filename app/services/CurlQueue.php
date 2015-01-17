<?php

class CurlQueue extends BaseController{

	public function fire($data)
	{
		$easemob								= getEasemob();
		// Push notifications to App client
		cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', $data)
				->setHeader('content-type', 'application/json')
				->setHeader('Accept', 'json')
				->setHeader('Authorization', 'Bearer '.$easemob->token)
				->setOptions([CURLOPT_VERBOSE => true])
				->send();
	}
}