<script>
    var post_renew_url  = "{{ route('postrenew') }}";
    var token           = "{{ csrf_token() }}";
    var lang_has_renew  = "{{ Lang::get('account/complete.has_renew') }}";
</script>
{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

{{ Minify::javascript(array(
    '/assets/js/jingling.js',
    '/assets/js/color.js',
    '/assets/js/preInfo.js'
)) }}

</body>
</html>