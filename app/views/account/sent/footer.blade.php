<script>
	var postuniversity	= "{{ route('postuniversity') }}";
	var post_renew_url	= "{{ route('postrenew') }}";
	var token			= "{{ csrf_token() }}";
	// For easemob
	var curUserId		= '{{ Auth::user()->id }}';
	var curUserPass		= '{{ Auth::user()->password }}';
</script>

{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
{{ HTML::script('assets/js/jingling.js') }}
{{ HTML::script('assets/js/color.js') }}
{{ HTML::script('assets/js/courtship.js') }}
{{ HTML::script('assets/remodal-0.3.0/jquery.remodal.js') }}
{{-- Easemob Web IM SDK --}}
{{ HTML::script('assets/easymob-webim1.0/strophe-custom-1.0.0.js') }}
{{ HTML::script('assets/easymob-webim1.0/json2.js') }}
{{ HTML::script('assets/easymob-webim1.0/easemob.im-1.0.0.js') }}
{{ HTML::script('assets/js/inbox_chat.js') }}

</body>
</html>