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

// Retrieve user
						$user				= User::where('id', 3)->first();
						$profile			= Profile::where('user_id', $user->id)->first();
						$constellationInfo	= getConstellation($profile->constellation); // Get user's constellation
						$tag_str			= explode(',', substr($profile->tag_str, 1)); // Get user's tag
						$data = array(
								'status'		=> 1,
								'sex'			=> $user->sex,
								'bio'			=> $user->bio,
								'nickname'		=> $user->nickname,
								'born_year'		=> $user->born_year,
								'school'		=> $user->school,
								'portrait'		=> route('home').'/'.'portrait/'.$user->portrait,
								'constellation'	=> $constellationInfo['name'],
								'tag_str'		=> $tag_str,
								'hobbies'		=> $profile->hobbies,
								'grade'			=> $profile->grade,
								'question'		=> $profile->question,
								'self_intro'	=> $profile->self_intro,
							);
						echo '<pre>';
						echo Response::json(utf8_converter(
							array(
								'status'		=> 1,
								'sex'			=> $user->sex,
								'bio'			=> $user->bio,
								'nickname'		=> $user->nickname,
								'born_year'		=> $user->born_year,
								'school'		=> $user->school,
								'portrait'		=> route('home').'/'.'portrait/'.$user->portrait,
								'constellation'	=> $constellationInfo['name'],
								'tag_str'		=> $tag_str,
								'hobbies'		=> $profile->hobbies,
								'grade'			=> $profile->grade,
								'question'		=> $profile->question,
								'self_intro'	=> $profile->self_intro)
							));