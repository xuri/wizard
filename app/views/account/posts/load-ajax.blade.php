<div id="load-ajax">
	<ul id="new_main_forum" class="new_main">
		<div id="if_error"></div>
		<div id="courtship-mine">
			<ul class="clear" id="post-ajax">
				@foreach($posts as $post)
				<?php
					$comments = ForumComments::where('post_id', $post->id)->count();
				?>
				<li class="clear">
					<a href="{{ route('forum.show', $post->id) }}" title="这条帖子有{{ $comments }}条回复。" alt="这条帖子有{{ $comments }}条回复。">
						<span>{{ $comments }}</span>
					</a>
					<a href="{{ route('forum.show', $post->id) }}" title="查看我的帖子：{{ $post->title }}" alt="查看我的帖子：{{ $post->title }}">
						<p>
							{{ $post->title }}
						</p>
					</a>
					<a class="date">{{ date('Y年m月d日 H:m',strtotime($post->created_at)) }}</a>
					<a href="javascript:void(0);" class="delete-message" data-post-id="{{ $post->id }}"><i class="fa fa-trash-o"></i></a>
				</li>
				@endforeach
			</ul>
		</div>
		{{ pagination($posts->appends(Input::except('page')), 'layout.paginator') }}
	</ul>
</div>

<script>
// Ajax delete posts
$(function(){

	$(".delete-message").click(function(){
		var postId = $(this).data('post-id'); // Get post ID attribute
		if (confirm("{{ Lang::get('account/posts.delete_confirm') }}")) {
			// Ajax post data
			var formData = {
				post_id 	: postId, // Get post title
				_token 		: csrfToken, // CSRF token
			};

			// Process ajax request
			$.ajax({
				url 	: accountDeleteForumPostAction, // the url where we want to POST
				type 	: "POST",  // define the type of HTTP verb we want to use (POST for our form)
				data 	: formData, // our data object
			}).done(function(data) {

				// Here we will handle errors and validation messages
				if ( ! data.success) {
					// Handle suucess message and auto hide element after 3 seconds
					$('#if_error').html('<div class="callout-warning">' + data.fail_info + '</div>').delay(3000).fadeOut('slow');
				} else {
					// Ajax reload
					location.reload();
				}
			});
		}
		return false;
	});
});
</script>