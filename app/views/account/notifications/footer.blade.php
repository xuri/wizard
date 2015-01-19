{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

<script>
	// Define variable use in forum.js
	var csrfToken		= '{{ csrf_token() }}';
	var token			= '{{ csrf_token() }}';
	var post_renew_url	= "{{ route('postrenew') }}";
	var firstAjaxURL	= "{{ route('account.notifications.type', 'first') }}";
	var secondAjaxURL	= "{{ route('account.notifications.type', 'second') }}";
	var thirdAjaxURL	= "{{ route('account.notifications.type', 'third') }}";
</script>

{{ Minify::javascript(array(
	'/assets/js/jingling.js',
	'/assets/js/color.js',
	'/assets/js/preInfo.js',
	'/assets/js/notifications.js'
)) }}

</body>
</html>