<title>Android Debug</title>
Android Debug

<?php
$easemob		= getEasemob();
	// Add friend relationship in chat system and start chat
	$test = cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
		'target_type' => 'users',
		'target' => ['4'],
		'msg' => ['type' => 'cmd', 'action' => '1'],
		'from' => 7,
		'ext' => ['content' => '用户6追你了', 'id' => '4']
		])
			->setHeader('content-type', 'application/json')
			->setHeader('Accept', 'json')
			->setHeader('Authorization', 'Bearer '.$easemob->token)
			->setOptions([CURLOPT_VERBOSE => true])
			->send();
		echo $test->body;



?>