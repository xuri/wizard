<title>Android Debug</title>
Android Debug

<?php
// $easemob		= getEasemob();
// // Android Push notifications debug
// $test = cURL::newJsonRequest('post', 'https://a1.easemob.com/jinglingkj/pinai/messages', [
// 	'target_type' => 'users',
// 	'target' => ['4'],
// 	'msg' => ['type' => 'cmd', 'action' => '6'],
// 	'from' => 7,
// 	'ext' => ['content' => '用户6追你了', 'id' => '7']
// 	])
// 		->setHeader('content-type', 'application/json')
// 		->setHeader('Accept', 'json')
// 		->setHeader('Authorization', 'Bearer '.$easemob->token)
// 		->setOptions([CURLOPT_VERBOSE => true])
// 		->send();
// 	echo $test->body;
//
//
//
$comments_count	= ForumComments::where('post_id', 35)->count();
			$comments_array	= ForumComments::where('post_id', 35)->select('id')->get()->toArray();
			$replies_count	= 0;
			foreach ($comments_array as $key => $value) {
				$replies_count	= $replies_count + ForumReply::where('comments_id', $value['id'])->count();
			}
			$comments_and_replies = $comments_count + $replies_count;

echo $comments_and_replies;
?>