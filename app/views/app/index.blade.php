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
$id	 = 1;

					// Retrieve all system notifications
					$notifications	= Notification::where('receiver_id', $id)
										->whereIn('category', array(8, 9))
										->orderBy('created_at' , 'desc')
										->select('id', 'sender_id', 'created_at')
										->where('status', 0)
										->get()
										->toArray();

					$friend_notifications = Notification::where('receiver_id', $id)
										->whereIn('category', array(1, 2, 3))
										->orderBy('created_at' , 'desc')
										->select('id', 'sender_id', 'created_at', 'receiver_id', 'category')
										->where('status', 0)
										->get()
										->toArray();
					// Build array
					foreach ($notifications as $key => $value) {
						$notifications_content				= NotificationsContent::where('notifications_id', $notifications[$key]['id'])->first();
						$notifications[$key]['content']		= $notifications_content->content;
						$notifications[$key]['created_at']	= date('m-d H:m', strtotime($notifications_content->created_at));

					}

					foreach ($friend_notifications as $key => $value) {
						switch ($friend_notifications[$key]['category']) {
							case '1' :
								$sender_user							= User::find($friend_notifications[$key]['sender_id']);
								$like									= Like::where('sender_id', $friend_notifications[$key]['sender_id'])->where('receiver_id', $friend_notifications[$key]['receiver_id'])->first();
								$friend_notifications[$key]['content']	= $sender_user->nickname . '追你了，快去看看吧';
								$friend_notifications[$key]['nickname']	= $sender_user->nickname;
								$friend_notifications[$key]['portrait']	= route('home') . '/' . 'portrait/' . $sender_user->portrait;
								$friend_notifications[$key]['answer']	= $like->answer;
								$friend_notifications[$key]['id']		= $friend_notifications[$key]['sender_id'];
							break;

							case '2' :
								$sender_user							= User::find($friend_notifications[$key]['sender_id']);
								$like									= Like::where('sender_id', $friend_notifications[$key]['sender_id'])->where('receiver_id', $friend_notifications[$key]['receiver_id'])->first();
								$friend_notifications[$key]['content']	= $sender_user->nickname . '再次追你了，快去看看吧';
								$friend_notifications[$key]['nickname']	= e($sender_user->nickname);
								$friend_notifications[$key]['portrait']	= route('home') . '/' . 'portrait/' . $sender_user->portrait;
								$friend_notifications[$key]['answer']	= e($like->answer);
								$friend_notifications[$key]['id']		= $friend_notifications[$key]['sender_id'];
							break;

							case '3' :
								$sender_user							= User::find($friend_notifications[$key]['sender_id']);
								$like									= Like::where('sender_id', $friend_notifications[$key]['sender_id'])->where('receiver_id', $friend_notifications[$key]['receiver_id'])->first();
								$friend_notifications[$key]['nickname']	= $sender_user->nickname;
								$friend_notifications[$key]['portrait']	= route('home') . '/' . 'portrait/' . $sender_user->portrait;
								$friend_notifications[$key]['id']		= $friend_notifications[$key]['sender_id'];
							break;
						}
					}

					// Mark read for this user
					//Notification::where('receiver_id', $id)->update(array('status' => 1));

					$data = array(
							'system_notifications'	=> $notifications,
							'friend_notifications'	=> $friend_notifications,
						);
					// Build Json format
					echo '{ "status" : "1", "data" : ' . json_encode($data) . '}';
?>