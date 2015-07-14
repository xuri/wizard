<div class="footer">
    Copyright &copy; 2013 - <?php echo date('Y'); ?> <a href="{{ route('home') }}" target="_blank">{{ Lang::get('footer.company') }}</a> All rights reserved. {{ Lang::get('footer.icp_license') }} <a href="http://www.miitbeian.gov.cn/" target="_blank">黑ICP备14007294号</a>
    <a href="javascript:void(0);" title="简体中文" alt="简体中文" class="set_lang_zh">
        {{ HTML::image('assets/images/china_flag.svg', '', array('style' => 'max-width:100%; margin: 0 0 0.2em 1em;', 'width' => '22', 'height' => '15')) }}
    </a>
    <a href="javascript:void(0);" title="English" alt="English" class="set_lang_en">
        {{ HTML::image('assets/images/us_flag.svg', '', array('style' => 'max-width:100%; margin: 0 0 0.2em 0.5em;', 'width' => '22', 'height' => '15')) }}
    </a>
</div>
<script>

    var csrfToken = '{{ csrf_token() }}';
    var homeRoute = '{{ route("home") }}';

    $('.set_lang_zh').click(function(e) {
        $.post(homeRoute, { _token: csrfToken, lang: "zh" } );
        e.preventDefault();
        window.location.href = homeRoute;
    });

    $('.set_lang_en').click(function(e) {
        $.post(homeRoute, { _token: csrfToken, lang: "en" } );
        e.preventDefault();
        window.location.href = homeRoute;
    });

</script>

@include('layout.analytics')
@yield('content')