<script>
	var post_renew_url	= "{{ route('postrenew') }}";
	var token			= "{{ csrf_token() }}";
</script>
{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
{{ HTML::script('assets/js/jingling.js') }}
{{ HTML::script('assets/js/color.js') }}
{{ HTML::script('assets/js/preInfo.js') }}

</body>
</html>