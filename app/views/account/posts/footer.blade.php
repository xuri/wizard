<script>
	var csrfToken						= '{{ csrf_token() }}';
	var accountDeleteForumPostAction	= "{{ action('AccountController@postDeleteForumPost') }}";
</script>
{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
{{ HTML::script('assets/js/jingling.js') }}
{{ HTML::script('assets/js/color.js') }}
{{ HTML::script('assets/js/preInfo.js') }}
{{ HTML::script('assets/js/account-posts.js') }}
</body>
</html>