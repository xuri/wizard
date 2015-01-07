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




$postid		= 80;


$perpage	= 10;

// If Android have post last user id
// Retrive post data
$post		= ForumPost::where('id', $postid)->first();

// Retrive user data of this post
$author		= User::where('id', $post->user_id)->first();

// Get last record from database
$lastRecord	= ForumComments::orderBy('id', 'desc')->first()->id;

// Query all comments of this post
$comments	= ForumComments::where('post_id', $postid)
					->orderBy('created_at' , 'desc')
					->where('id', '<=', $lastRecord)
					->select('id', 'user_id', 'content', 'created_at')
					->take($perpage)
					->get()
					->toArray();
// Build comments array and include reply information
foreach($comments as $key => $field) {

	// Retrive comments user
	$comments_user						= User::where('id', $comments[$key]['user_id'])->first();

	// Comments user ID
	$comments[$key]['user_id']			= $comments_user->id;

	// Comments user portrait
	$comments[$key]['user_portrait']	= route('home') . '/' . 'portrait/' . $comments_user->portrait;

	// Comments user sex
	$comments[$key]['user_sex']			= $comments_user->sex;

	// Query all replies of this post
	$replies = ForumReply::where('comments_id', $comments[$key]['id'])
				->select('id', 'user_id', 'content', 'created_at')
				->orderBy('created_at' , 'desc')
				->take(3)
				->get()
				->toArray();

	// Calculate total replies of this post
	$comments[$key]['reply_count'] = ForumReply::where('comments_id', $comments[$key]['id'])->count();

	// Build reply array
	foreach($replies as $key => $field) {

		// Retrive reply user
		$reply_user					= User::where('id', $replies[$key]['user_id'])->first();

		// Reply user sex
		$replies[$key]['sex']		= $reply_user->sex;

		// Reply user portrait
		$replies[$key]['portrait']	= route('home') . '/' . 'portrait/' . $reply_user->portrait;

	}

	// Add comments replies array to post comments_reply array
	$comments[$key]['comment_reply'] = $replies;

}

// Build Data Array
$data = array(
	'portrait'		=> route('home') . '/' . 'portrait/' . $author->portrait, // Post user portrait
	'sex'			=> $author->sex, // Post user sex
	'nickname'		=> $author->nickname, // Post user nickname
	'user_id'		=> $author->id, // Post user ID
	'comment_count'	=> ForumComments::where('post_id', $postid)->get()->count(), // Post comments count
	'created_at'	=> $post->created_at->toDateTimeString(), // Post created date
	'content'		=> $post->content, // Post content
	'comments'		=> $comments // Post comments (array format and include reply)

);

// Build Json format
echo '{ "status" : "1", "data" : ' . json_encode($data) . '}';


?>