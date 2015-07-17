<!DOCTYPE html>
<html lang="{{ Session::get('language', Config::get('app.locale')) }}">
<head>
    <title>{{ Lang::get('navigation.pinai') }} | {{ Lang::get('index.title') }}</title>

    @include('layout.meta')
    @yield('content')

    <!--[if lte IE 9]>
        <script type=text/javascript>window.location.href="{{ route('browser_not_support') }}";  </script>
    <![endif]-->

    {{-- The Stylesheets --}}

    {{ Minify::stylesheet(array(
        '/assets/css/indexv3/public.css',
        '/assets/css/indexv3/product.css'
    )) }}

</head>
<body>
    <div id="content">
        <div class="big-box box1">
            <h1 class="product-name">聘爱</h1>
            <p class="intr">只属于90后的情侣招聘平台</p>
            <div class="btn-wrap">
                <a href="https://itunes.apple.com/cn/app/pin-ai/id985554599?l=en&mt=8" class="download-btn" target="_blank">App Store</a>
                <a href="http://fir.im/pinai" class="download-btn" target="_blank">Android</a>
            </div>
        </div>

        <div class="big-box box2">
            <div class="sec2-phone-wrap">
                {{ HTML::image('assets/images/indexv3/phone1.png') }}
                <div class="phone-shadow"></div>
            </div>
            <ul class="function-intr">
                <li>
                    <h3>1</h3>
                    <p>招聘会：填写信息招聘最心仪的人</p>
                </li>
                <li>
                    <h3>2</h3>
                    <p>单身公寓：用户互动，分享空间</p>
                </li>
                <li>
                    <h3>3</h3>
                    <p>简历缘：每日3次匹配缘分</p>
                </li>
                <li>
                    <h3>4</h3>
                    <p>上市排行：通过用户粘性进行排行</p>
                </li>
            </ul>
        </div>

        <div class="big-box box3">
            <p class="sec3-txt">恋爱=适合</p>
            <p class="sec3-txt">适合的都能够相遇</p>
            <div class="sec3-phone-wrap2">
                {{ HTML::image('assets/images/indexv3/phone2.png') }}
                <div class="phone-shadow"></div>
            </div>
            <div class="sec3-phone-wrap3">
                {{ HTML::image('assets/images/indexv3/phone3.png') }}
                <div class="phone-shadow"></div>
            </div>
            {{ HTML::image('assets/images/indexv3/boy.png', '', array('class' => 'boy-img')) }}
            {{ HTML::image('assets/images/indexv3/girl.png', '', array('class' => 'girl-img')) }}
            {{ HTML::image('assets/images/indexv3/feiji.png', '', array('class' => 'feiji-img')) }}
            {{ HTML::image('assets/images/indexv3/sec3-xin1.png', '', array('class' => 'sec3-xin1-img')) }}
            {{ HTML::image('assets/images/indexv3/sec3-xin2.png', '', array('class' => 'sec3-xin2-img')) }}
        </div>

        <div class="big-box box4">
            <div class="sec4-phone-wrap2">
                {{ HTML::image('assets/images/indexv3/phone4.png') }}
                <div class="phone-shadow"></div>
            </div>
            <div class="sec4-phone-wrap3">
                {{ HTML::image('assets/images/indexv3/phone5.png') }}
                <div class="phone-shadow"></div>
            </div>
            <p class="sec4-txt1">每日新相遇</p>
            <p class="sec4-txt2">让相遇变成最好的礼物</p>
            {{ HTML::image('assets/images/indexv3/sec4.png', '', array('class' => 'sec4-img')) }}
        </div>

        <div class="footer">
            <p class="intr">聘爱团队是由90后在校大学生组成，是一群有梦想的年轻人，如果你是某个领域的优秀人才，想加入请联系我们哦</p>
            <div class="link">
                <span>QQ:523591643</span>
                <span>TEL:15636129303</span>
                <span>E-mail:support@pinai521.com</span>
            </div>
            <p class="Copyright">Copyright &copy; 2013 - <?php echo date('Y'); ?> {{ Lang::get('footer.company') }} All rights reserved. {{ Lang::get('footer.icp_license') }} 黑ICP备14007294号</p>
        </div>
    </div>

    {{ HTML::image('assets/images/indexv3/erweima.png', '', array('class' => 'erweima')) }}

    {{-- Analytics Code --}}
    @include('layout.analytics')
    @yield('content')

    {{-- jQuery --}}
    {{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
    {{ HTML::script('assets/js/jquery.mousewheel.min.js') }}
    {{ HTML::script('assets/js/product.js') }}
</body>
</html>