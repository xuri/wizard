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
$analyticsLike = AnalyticsLike::select(
							'daily_like',
							'weekly_like',
							'monthly_like',
							'all_male_like',
							'all_female_like',
							'daily_male_like',
							'daily_female_like',
							'weekly_male_like',
							'weekly_female_like',
							'monthly_male_like',
							'monthly_female_like',
							'all_male_accept_ratio',
							'all_female_accept_ratio',
							'average_like_duration',
							'created_at'
						)->orderBy('created_at')->take(31)->get()->toArray(); // Retrive analytics data

		/*
		|--------------------------------------------------------------------------
		| Likes Analytics Section
		|--------------------------------------------------------------------------
		|
		*/

		$allMaleLike = array(); // Create all male likes array
		foreach($analyticsLike as $key){ // Structure array elements
			$allMaleLike[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['all_male_like']);
		}

		$allFemaleLike = array(); // Create all female likes array
		foreach($analyticsLike as $key){ // Structure array elements
			$allFemaleLike[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['all_female_like']);
		}

		// Build Json data (remove double quotes from Json return data)
		$basicLikes = '{
			"累计男生追女生次数":'.preg_replace('/["]/', '' ,json_encode($allMaleLike)).
			', "累计女生追男生次数":'.preg_replace('/["]/', '' ,json_encode($allFemaleLike)).
			'}';

		/*
		|--------------------------------------------------------------------------
		| Daily Likes Analytics Section
		|--------------------------------------------------------------------------
		|
		*/

		$dailyLike = array(); // Create daily likes array
		foreach($analyticsLike as $key){ // Structure array elements
			$dailyLike[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['daily_like']);
		}

		$dailyMaleLike = array(); // Create daily male likes array
		foreach($analyticsLike as $key){ // Structure array elements
			$dailyMaleLike[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['daily_male_like']);
		}

		$dailyFemaleLike = array(); // Create daily female likes array
		foreach($analyticsLike as $key){ // Structure array elements
			$dailyFemaleLike[] = array(
				date('Y', strtotime($key['created_at'])),
				date('m', strtotime($key['created_at'])),
				date('d', strtotime($key['created_at'])),
				$key['daily_female_like']);
		}

		// Build Json data (remove double quotes from Json return data)
	echo	$dailyLikes = '{
			"每日用户互动次数":'.preg_replace('/["]/', '' ,json_encode($dailyLike)).
			', "每日男生追女生次数":'.preg_replace('/["]/', '' ,json_encode($dailyMaleLike)).
			', "每日女生追男生次数":'.preg_replace('/["]/', '' ,json_encode($dailyFemaleLike)).
			'}';
?>