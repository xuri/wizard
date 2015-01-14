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
$sex_filter = Input::get('sex');
$university_filter = Input::get('university');


$last_id  = Input::get('lastid'); // Post last user id from Android client
					$per_page = Input::get('perpage',10); // Post count per query from Android client
					if($last_id) // If Android have post last user id
					{

						$users = User::whereNotNull('portrait') // Skip none portrait user
						->orderBy('id', 'desc')
						->select('id', 'nickname', 'school', 'sex', 'portrait')
						->where('id', '<', $last_id)
						->take($per_page)
						->get()
						->toArray();
						// Replace receiver ID to receiver portrait
						foreach($users as $key => $field){
							// Convert to real storage path
							$users[$key]['portrait']	= route('home') . '/' . 'portrait/' . $users[$key]['portrait'];

							// Retrieve sex with UTF8 encode
							$users[$key]['sex']			= e($users[$key]['sex']);

							// Retrieve nickname with UTF8 encode
							$users[$key]['nickname']	= e($users[$key]['nickname']);

							// Retrieve school with UTF8 encode
							$users[$key]['school']		= e($users[$key]['school']);
						}
						$users = json_encode($users); // Encode likes array to json format
						if($users) // If get query success
						{
							echo '{ "status" : "1", "data" : '.$users.'}'; // Build Json format
						} else { // Get query fail
							echo Response::json(
								array(
									'status' 		=> 0
								)
							);
						}
					} else { // First get data from Android client
						$query      = User::whereNotNull('portrait');
						if($sex_filter){
							isset($sex_filter) AND $query->where('sex', $sex_filter);
						}
						if($university_filter){
							isset($university_filter) AND $query->where('school', $university_filter);
						}
						$lastRecord = User::orderBy('id', 'desc')->first()->id; // Query last user id in database
						$users      = $query // Skip none portrait user
										->orderBy('id', 'desc')
										->select('id', 'nickname', 'school', 'sex', 'portrait')
										->where('id', '<=', $lastRecord)
										->take($per_page)
										->get()
										->toArray();
						// Replace receiver ID to receiver portrait
						foreach($users as $key => $field){
							// Convert to real storage path
							$users[$key]['portrait']	= route('home') . '/' . 'portrait/' . $users[$key]['portrait'];

							// Retrieve sex with UTF8 encode
							$users[$key]['sex']			= e($users[$key]['sex']);

							// Retrieve nickname with UTF8 encode
							$users[$key]['nickname']	= e($users[$key]['nickname']);

							// Retrieve school with UTF8 encode
							$users[$key]['school']		= e($users[$key]['school']);
						}
						$users = json_encode($users); // Encode likes array to json format
						if($users)
						{
							echo '{ "status" : "1", "data" : '.$users.'}';
						} else {
							echo Response::json(
								array(
									'status' 		=> 0
								)
							);
						}
					}

?>