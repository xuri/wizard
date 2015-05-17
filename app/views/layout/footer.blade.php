{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

{{ Minify::javascript(array(
    '/assets/js/jquery.scrollto.js',
    '/assets/js/main.js'
)) }}
<script type="text/javascript">
    var scroll=function (id){
        $("#"+id).ScrollTo(1000);
    }
</script>
</body>
</html>