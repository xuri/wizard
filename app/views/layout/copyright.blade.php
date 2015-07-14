<div class="footer">
    Copyright &copy; 2013 - <?php echo date('Y'); ?> <a href="{{ route('home') }}" target="_blank">{{ Lang::get('footer.company') }}</a> All rights reserved. {{ Lang::get('footer.icp_license') }} <a href="http://www.miitbeian.gov.cn/" target="_blank">黑ICP备14007294号</a>
    {{ Form::open(array('method' => 'post', 'route' => 'home')) }}
    <input type="hidden" name="lang" value="zh" />
    <button type="submit" title="简体中文" alt="简体中文">
        {{ HTML::image('assets/images/china_flag.svg', '', array('style' => 'max-width:100%; margin: 0 0 0.2em 1em;', 'width' => '22', 'height' => '15')) }}
    </button>
    {{ Form::close() }}
    {{ Form::open(array('method' => 'post', 'route' => 'home')) }}
    <input type="hidden" name="lang" value="en" />
    <button type="submit"  title="English" alt="English">
        {{ HTML::image('assets/images/us_flag.svg', '', array('style' => 'max-width:100%; margin: 0 0 0.2em 0.5em;', 'width' => '22', 'height' => '15')) }}
    </button>
    {{ Form::close() }}
</div>

@include('layout.analytics')
@yield('content')