<title>Android Debug</title>
Android Debug

<?php
// $easemob		= getEasemob();
// // Androit Push notifications debug
// $test = cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
// 	'target_type' => 'users',
// 	'target' => ['4'],
// 	'msg' => ['type' => 'cmd', 'action' => '1'],
// 	'from' => 7,
// 	'ext' => ['content' => '用户6追你了', 'id' => '7']
// 	])
// 		->setHeader('content-type', 'application/json')
// 		->setHeader('Accept', 'json')
// 		->setHeader('Authorization', 'Bearer '.$easemob->token)
// 		->setOptions([CURLOPT_VERBOSE => true])
// 		->send();
// 	echo $test->body;

$obj = json_decode('{
    "image1": ["jpg", "www.baidu.com"],
    "image2": ["png", "www.tecent.com"],
    "image3": ["jpg", "www.taobao.com"]
}');

$path = array();
foreach($obj as $key => $item) {
	echo $item['0'];
	$path[$key] = $item['1'].$item['0'];
}

print_r($path);
?>