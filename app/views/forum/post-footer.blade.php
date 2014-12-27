</body>
{{ HTML::script('assets/fancybox-2.1.5/jquery.fancybox.pack.js') }}
<script>
	// Define variable use in forum-post.js
	var csrfToken							= '{{ csrf_token() }}';
	var forumControllerPostCommentAction	= "{{ action('ForumController@postComment', $data->id) }}";
</script>
{{ HTML::script('assets/js/forum-post.js') }}
</html>