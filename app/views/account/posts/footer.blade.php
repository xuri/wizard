</body>
{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
{{ HTML::script('assets/js/jingling.js') }}
{{ HTML::script('assets/js/color.js') }}
{{ HTML::script('assets/js/preInfo.js') }}
<script>
var csrfToken 						= '{{ csrf_token() }}';
var accountDeleteForumPostAction = "{{ action('AccountController@postDeleteForumPost') }}";
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
</script>
</html>