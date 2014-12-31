// Ajax pagination

$(function() {
	$('#load-ajax').on('click', '.lu_paging a', function (e) {
		getPosts($(this).attr('href').split('page=')[1]);
		e.preventDefault();
	});
});

function getPosts(page) {
	$.ajax({
		url : '?page=' + page,
		dataType: 'json',
	}).done(function (data) {
		$('#load-ajax').html(data);
	}).fail(function () {
		alert('Posts could not be loaded.');
	});
}

// Ajax delete posts
$(function(){

	$(".delete-message").click(function(){
		var postId = $(this).data('post-id'); // Get post ID attribute
		if (confirm("确定要删除这条帖子吗?")) {
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