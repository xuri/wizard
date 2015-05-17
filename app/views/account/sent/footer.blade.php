<script>
    var postuniversity  = "{{ route('postuniversity') }}";
    var post_renew_url  = "{{ route('postrenew') }}";
    var token           = "{{ csrf_token() }}";
    // For easemob
    var curUserId       = '{{ Auth::user()->id }}';
    var curUserPass     = '{{ Auth::user()->password }}';
    var lang_has_renew  = "{{ Lang::get('account/complete.has_renew') }}";
</script>

{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

{{ Minify::javascript(array(
    '/assets/js/jingling.js',
    '/assets/js/color.js',
    '/assets/js/courtship.js',
    '/assets/remodal-0.3.0/jquery.remodal.js',
    '/assets/easymob-webim1.0/strophe-custom-1.0.0.js',
    '/assets/easymob-webim1.0/json2.js',
    '/assets/easymob-webim1.0/easemob.im-1.0.0.js',
    '/assets/js/inbox_chat.js'
)) }}

</body>
</html>