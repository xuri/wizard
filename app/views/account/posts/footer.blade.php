<script>
	var csrfToken						= '{{ csrf_token() }}';
	var token							= '{{ csrf_token() }}';
	var post_renew_url					= "{{ route('postrenew') }}";
	var accountDeleteForumPostAction	= "{{ action('AccountController@postDeleteForumPost') }}";
	var lang_delete_confirm				= "{{ Lang::get('account/posts.delete_confirm') }}";
</script>
{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

{{ Minify::javascript(array(
	'/assets/js/jingling.js',
	'/assets/js/color.js',
	'/assets/js/preInfo.js',
	'/assets/js/account-posts.js'
)) }}

</body>
</html>