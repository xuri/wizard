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

$analyticsUser = AnalyticsUser::select(
							'all_user',
							'daily_active_user',
							'weekly_active_user',
							'monthly_active_user',
							'all_male_user',
							'daily_active_male_user',
							'weekly_active_male_user',
							'monthly_active_male_user',
							'all_female_user',
							'daily_active_female_user',
							'weekly_active_female_user',
							'monthly_active_female_user',
							'complete_profile_user_ratio',
							'from_web',
							'from_android',
							'from_ios',
							'created_at'
						)->orderBy('created_at')->take(30)->get()->toArray(); // Retrive analytics data
		$allUser = array(); // Create all user array
		foreach($analyticsUser as $key){ // Structure array elements
			$allUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['all_user']);
		}

		$allMaleUser = array(); // Create all male user array
		foreach($analyticsUser as $key){ // Structure array elements
			$allMaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['all_male_user']);
		}
		$allFemaleUser = array(); // Create all female user array
		foreach($analyticsUser as $key){ // Structure array elements
			$allFemaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['all_female_user']);
		}

		$monthlyActiveUser = array(); // Create monthly active user array
		foreach($analyticsUser as $key){ // Structure array elements
			$monthlyActiveUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['monthly_active_user']);
		}

		$monthlyActiveMaleUser = array(); // Create monthly active male user array
		foreach($analyticsUser as $key){ // Structure array elements
			$weeklyActiveMaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['monthly_active_male_user']);
		}

		$monthlyActiveFemaleUser = array(); // Create monthly active female user array
		foreach($analyticsUser as $key){ // Structure array elements
			$weeklyActiveFemaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['monthly_active_female_user']);
		}

		$monthlyActiveFemaleUser = array(); // Create monthly active female user array
		foreach($analyticsUser as $key){ // Structure array elements
			$weeklyActiveFemaleUser[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['monthly_active_female_user']);
		}

		// Build Json data (remove double quotes from Json return data)
		echo '{
			"月活用户":'.preg_replace('/["]/', '' ,json_encode($monthlyActiveUser)).
			', "月活男用户":'.preg_replace('/["]/', '' ,json_encode($monthlyActiveMaleUser)).
			', "月活女用户":'.preg_replace('/["]/', '' ,json_encode($monthlyActiveFemaleUser)).
			'}';
?>