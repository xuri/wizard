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
$user_id = 3;
$per_page = 10;
$lastRecord = Like::where('sender_id', $user_id)->orderBy('id', 'desc')->first()->id; // Query last like id in database
						$allLike    = Like::where('sender_id', $user_id) // Query all user liked users
							->orderBy('id', 'desc')
							->select('receiver_id', 'status', 'created_at', 'count')
							->where('id', '<=', $lastRecord)
							->take($per_page)
							->get()
							->toArray();
						// Replace receiver_id key name to portrait
						foreach($allLike as $key1 => $val1){
							foreach($val1 as $key => $val){
								$new_key				= str_replace('receiver_id', 'portrait', $key);
								$new_array[$new_key]	= $val;
							}
							$likes[] = $new_array;
						}
						// Replace receiver ID to receiver portrait
						foreach($likes as $key => $field){
							// Retrieve receiver user
							$user						= User::where('id',  $likes[$key]['portrait'])->first();

							// Receiver ID
							$likes[$key]['id']			= e($user->id);

							// Receiver avatar real storage path
							$likes[$key]['portrait']	= route('home') . '/' . 'portrait/' . $user->portrait;

							// Receiver school
							$likes[$key]['school']		= e($user->school);

							// Receiver nickname
							$likes[$key]['name']		= e($user->nickname);

							// Receiver sex
							$likes[$key]['sex']			= e($user->sex);



							// Convert how long liked
							$Date_1						= date("Y-m-d"); // Current date and time
							$Date_2						= date("Y-m-d",strtotime($likes[$key]['created_at']));
							$d1							= strtotime($Date_1);
							$d2							= strtotime($Date_2);
							$Days						= round(($d1-$d2)/3600/24); // Calculate liked time
							$likes[$key]['created_at']	= $Days;
						}
						$like = json_encode($likes); // Encode likes array to json format
						if($allLike)
						{
							echo '{ "status" : "1", "data" : '.$like.'}';
						} else {
							echo Response::json(
								array(
									'status' 		=> 0
								)
							);
						}
?>