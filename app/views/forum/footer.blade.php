{{ HTML::script('assets/fancybox-2.1.5/jquery.fancybox.pack.js') }}
<script>
    // Instantiate editor
    var um1                             = UM.getEditor('cat1_editor');
    var um2                             = UM.getEditor('cat2_editor');
    var um3                             = UM.getEditor('cat3_editor');
    // Define variable use in forum.js
    var csrfToken                       = '{{ csrf_token() }}';
    var forumShowRoute                  = '{{ route("forum.index") }}/';
    var forumControllerPostNewAction    = "{{ action('ForumController@postNew') }}";
    var firstAjaxURL                    = '{{ route("forum.type", "first") }}';
    var secondAjaxURL                   = '{{ route("forum.type", "second") }}';
    var thirdAjaxURL                    = '{{ route("forum.type", "third") }}';
</script>

{{ Minify::javascript(array('/assets/js/forum.js')) }}

</body>
</html>