<script>
	var postuniversity	= "{{ route('postuniversity') }}";
	var post_renew_url	= "{{ route('postrenew') }}";
	var token			= "{{ csrf_token() }}";
</script>

{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

{{ Minify::javascript(array(
	'/assets/js/jingling.js',
	'/assets/js/color.js',
	'/assets/js/preInfoEdit.js'
)) }}

</body>
</html>