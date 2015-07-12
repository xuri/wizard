<?php

// Post number chars of items summary from App client
$numchars           = Input::get('numchars');

// Post number chars of original items summary from App client
$original_numchars  = Input::get('original_numchars');

// Get user ID from App client
$id                 = Input::get('id');

// Retrieve all user's notifications
$notifications = Notification::where('receiver_id', $id)
                    ->whereIn('category', array(6, 7))
                    ->where('status', 0) // Unread flag
                    ->select('id', 'category', 'sender_id', 'receiver_id', 'category_id', 'post_id', 'comment_id', 'reply_id', 'created_at')
                    ->orderBy('created_at' , 'desc')
                    ->get()
                    ->toArray();

// Build format
foreach ($notifications as $key => $notification) {

    // Retrieve sender
    $sender                     = User::where('id', $notifications[$key]['sender_id'])->first();

    // Determine user set portrait
    if ($sender->portrait) {

        // Get user portrait
        $notifications[$key]['portrait']    = route('home') . '/' . 'portrait/' . $sender->portrait;
    } else {

        // Return null
        $notifications[$key]['portrait']    = null;
    }

    // Determine user set nuckname
    if ($sender->nickname) {

        // Get user nickname
        $notifications[$key]['nickname']    = app_out_filter($sender->nickname);
    } else {

        // Return null
        $notifications[$key]['nickname']    = null;
    }

    // Determine category
    if ($notifications[$key]['category'] == 6) {

        // Comment
        $post                                       = ForumPost::where('id', $notifications[$key]['post_id'])->first();

        // Retrieve comment
        $comment                                    = ForumComments::where('id', $notifications[$key]['comment_id'])->first();

        // Add comment content summary to content key
        $notifications[$key]['content']             = app_out_filter(getplaintextintrofromhtml($comment->content, $numchars));

        // Add post content summary to original_content key
        $notifications[$key]['original_content']    = app_out_filter(getplaintextintrofromhtml($post->content, $numchars));

    } else {

        // Reply
        $comment                                    = ForumComments::where('id', $notifications[$key]['comment_id'])->first();

        // Retrieve reply
        $reply                                      = ForumReply::where('id', $notifications[$key]['reply_id'])->first();

        // Add reply content summary to content key
        $notifications[$key]['content']             = app_out_filter(getplaintextintrofromhtml($reply->content, $numchars));

        // Add post content summary to original_content key
        $notifications[$key]['original_content']    = app_out_filter(getplaintextintrofromhtml($comment->content, $original_numchars));
    }
}

Notification::where('receiver_id', $id)->whereIn('category', array(6, 7))->update(array('status' => 1));

// Build Json format
return Response::json(
    array(
        'status'    => 1,
        'data'      => $notifications
    )
);
