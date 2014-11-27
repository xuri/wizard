</body>
{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
{{ HTML::script('assets/js/jingling.js') }}
{{ HTML::script('assets/js/color.js') }}
{{ HTML::script('assets/js/courtship.js') }}
{{ HTML::script('assets/remodal-0.3.0/jquery.remodal.js') }}
<script>
    $(document).on('open', '.remodal', function () {
        // console.log('open');
    });

    $(document).on('opened', '.remodal', function () {
        // console.log('opened');
    });

    $(document).on('close', '.remodal', function () {
        // console.log('close');
    });

    $(document).on('closed', '.remodal', function () {
        // console.log('closed');
    });

    $(document).on('confirm', '.remodal', function () {
        // console.log('confirm');
    });

    $(document).on('cancel', '.remodal', function () {
        // console.log('cancel');
    });

   // You can open or close it like this:
   // $(function () {
   //     var inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
   //     inst.open();
   //     inst.close();
   // });

    //  Or init in this way:
    var inst = $('[data-remodal-id=modal2]').remodal();
    //  inst.open();
</script>
</html>