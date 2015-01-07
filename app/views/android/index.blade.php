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


$lastRecord = ForumPost::orderBy('id', 'desc')->first()->id; // Query last user id in database
$items	= ForumPost::where('category_id', 1)
			->orderBy('created_at' , 'desc')
			->where('id', '<=', $lastRecord)
			->select('id', 'user_id', 'title', 'content', 'created_at')
			->take('10')
			->get()
			->toArray();
// Replace receiver ID to receiver portrait
foreach($items as $key => $field){
	$post_user = User::where('id', $items[$key]['user_id'])->first();
	$items[$key]['portrait']	= route('home').'/'.'portrait/'.$post_user->portrait; // Get post user portrait real storage path
	$items[$key]['sex']	= $post_user->sex; // Get post user sex (M, F or null)
	$items[$key]['comments_count'] = ForumComments::where('post_id', $items[$key]['id'])->count();
	$items[$key]['nickname'] = $post_user->nickname;
	$items[$key]['content'] = getplaintextintrofromhtml($items[$key]['content'], Input::get('numchars'));
	// Using expression get all picture attachments (Only with pictures stored on this server.)
	preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $items[$key]['content'], $match );
	// Construct picture attachments list
	$items[$key]['thumbnails'] = array_pop($match);
}

echo '<pre>';
//print_r($items);

echo json_encode($items);
?>