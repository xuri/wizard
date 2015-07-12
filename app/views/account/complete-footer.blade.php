<script>
    var postuniversity      = "{{ route('postuniversity') }}";
    var post_renew_url      = "{{ route('postrenew') }}";
    var checkcomplete_url   = "{{ route('checkcomplete') }}";
    var token               = "{{ csrf_token() }}";
    var lang_save           = "{{ Lang::get('account/complete.save') }}";
    var lang_incomplete     = "{{ Lang::get('account/complete.incomplete') }}";
    var lang_has_renew      = "{{ Lang::get('account/complete.has_renew') }}";
</script>

{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
{{ HTML::script('assets/js/jquery-cropit/jquery.cropit.min.js') }}

{{ Minify::javascript(array(
    '/assets/js/jingling.js',
    '/assets/js/color.js',
    '/assets/js/preInfoEdit.js'
)) }}

</body>
</html>