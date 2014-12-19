<title>Android Debug</title>
Android Debug

<?php
// $easemob		= getEasemob();
// // Add friend relationship in chat system and start chat
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


// $id		= 4; // Get query ID from App client
// $sender = Like::where('receiver_id', $id)
// 			->where('status', 3)
// 			->select('sender_id')
// 			->get()
// 			->toArray(); // Get sender user data
// foreach($sender as $key => $field){
// 		$sender[$key]['nickname']	= User::where('id', $sender[$key]['sender_id'])->first()->nickname; // Sender nickname
// 		$sender[$key]['portrait']	= route('home').'/'.'portrait/'.User::where('id', $sender[$key]['sender_id'])->first()->portrait; // Sender portrait
// 	}
// $sender = json_encode($sender); // Convert array to json format
// if($sender) // Query successful
// {
// 	echo '{ "status" : "1", "data" : '.$sender.'}';
// } else {
// 	return Response::json(
// 		array(
// 			'status' 	=> 0
// 		)
// 	);
// }

?>