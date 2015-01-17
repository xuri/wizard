<?php

class CurlQueue extends BaseController{

	public function fire($job, $data)
	{

		$id			= $data['id'];
		$password	= $data['password'];

		// Add user success and chat Register
		$easemob			= getEasemob();

		// newRequest or newJsonRequest returns a Request object
		$regChat			= cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users', ['username' => $id, 'password' => $password])
			->setHeader('content-type', 'application/json')
			->setHeader('Accept', 'json')
			->setHeader('Authorization', 'Bearer '.$easemob->token)
			->setOptions([CURLOPT_VERBOSE => true])
			->send();

		$job->delete();
	}
}