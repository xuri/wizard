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
// Get sender user data
$id = 2;
					// Get sender user data
					$friends = Like::where('receiver_id', $id)->orWhere('sender_id', $id)
								->where('status', 3)
								->select('sender_id', 'receiver_id')
								->get()
								->toArray();

					foreach($friends as $key => $field){

							// Determine user is sender or receiver
							if($friends[$key]['sender_id'] == $id) {

								// User is sender and retrieve receiver user
								$user = User::where('id', $friends[$key]['receiver_id'])->first();

								// Friend ID
								$friends[$key]['friend_id']	= $user->id;

								// Friend nickname
								$friends[$key]['nickname']	= e($user->nickname);

								// Determine user portrait
								if(is_null($user->portrait)){

									// Friend portrait
									$friends[$key]['portrait']	= null;
								} else {

									// Friend portrait
									$friends[$key]['portrait']	= route('home'). '/' . 'portrait/' . $user->portrait;
								}
							} else {

								// User is receiver and retrieve sender user
								$user = User::where('id', $friends[$key]['sender_id'])->first();

								// Friend ID
								$friends[$key]['friend_id']	= $user->id;

								// Friend nickname
								$friends[$key]['nickname']	= e($user->nickname);

								// Determine user portrait
								if(is_null($user->portrait)){

									// Friend portrait
									$friends[$key]['portrait']	= null;
								} else {

									// Friend portrait
									$friends[$key]['portrait']	= route('home'). '/' . 'portrait/' . $user->portrait;
								}
							}
						}

					// Convert array to json format
					$friend = json_encode($friends);

					// Query successful
					if($friend)
					{
						echo '{ "status" : "1", "data" : ' . $friend . '}';
					} else {
						return Response::json(
							array(
								'status' 	=> 0
							)
						);
					}
?>