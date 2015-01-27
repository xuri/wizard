<?php

class AddUserQueue extends BaseController{

	public function fire($job, $data)
	{
		$username	= $data['username'];
		$password	= $data['password'];

		// Chat Register and if use authorizition register need get token
		$easemob			= getEasemob();

		// newRequest or newJsonRequest returns a Request object
		$regChat = cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/users', ['username' => $username, 'password' => $password])
			->setHeader('content-type', 'application/json')
			->setHeader('Accept', 'json')
			->setHeader('Authorization', 'Bearer '.$easemob->token)
			->setOptions([CURLOPT_VERBOSE => true])
			->send();

		$job->delete();
	}
}