<!DOCTYPE html>
<html>
<head>

    <title>{{ Lang::get('navigation.pinai') }} | 填写资料</title>

    @include('wap.meta')
    @yield('content')

    {{ HTML::style('assets/css/wap/public.css') }}
    {{ HTML::style('assets/css/wap/data.css') }}
    {{ HTML::style('assets/css/wap/Hselect.css') }}

</head>

<body>

    <h2 class="data-top">{{ HTML::image('assets/images/wap/imgs/jingling.png') }}<span>最后一步，绝对的！！</span></h2>
    <section id="form-wrap">
        <div class="per-input">
            <span class="input-title">出生年 :</span><span class="select-span select-birth borR-4">1993</span>
        </div>
        <div class="per-input">
            <span class="input-title">星 &nbsp; 座 :</span><span class="select-span select-constellation borR-4">摩羯座</span>
        </div>
        <div class="per-input">
            <span class="input-title">入学年 :</span><span class="select-span select-school-year borR-4">2012</span>
        </div>
        <div class="per-input">
            <span class="input-title">月 &nbsp; 薪 :</span><span class="select-span select-salary borR-4">在校学生</span>
        </div>
        <div class="per-input">
            <span class="input-title">个人简介 :</span>
            <textarea class="peo-intr borR-4" placeholder="你是一个怎样的人呢？"></textarea>
        </div>
        <div class="per-input">
            <span class="input-title">我的爱好 :</span>
            <textarea class="hobbies-intr borR-4" placeholder="你有哪些爱好？"></textarea>
        </div>

    </section>

    <button class="submit-data borR-4">去找对象</button>

    <div hidden id="birth-box" data-HprevSelect="-1">
        <li class="H-effective">1990</li>
        <li class="H-effective">1991</li>
        <li class="H-effective">1992</li>
        <li class="H-effective">1993</li>
        <li class="H-effective">1994</li>
        <li class="H-effective">1995</li>
        <li class="H-effective">1996</li>
        <li class="H-effective">1997</li>
        <li class="H-effective">1998</li>

    </div>

    <div hidden id="constellation-box" data-HprevSelect="-1">
        <li class="H-effective" data="1">水瓶座</li>
        <li class="H-effective" data="2">双鱼座</li>
        <li class="H-effective" data="3">白羊座</li>
        <li class="H-effective" data="4">金牛座</li>
        <li class="H-effective" data="5">双子座</li>
        <li class="H-effective" data="6">巨蟹座</li>
        <li class="H-effective" data="7">狮子座</li>
        <li class="H-effective" data="8">处女座</li>
        <li class="H-effective" data="9">天秤座</li>
        <li class="H-effective" data="10">天蝎座</li>
        <li class="H-effective" data="11">射手座</li>
        <li class="H-effective" data="12">摩羯座</li>
    </div>

    <div hidden id="schoolyear-box" data-HprevSelect="-1">
        <li class="H-effective">2010</li>
        <li class="H-effective">2011</li>
        <li class="H-effective">2012</li>
        <li class="H-effective">2013</li>
        <li class="H-effective">2014</li>
        <li class="H-effective">2015</li>
    </div>

    <div hidden id="salary-box" data-HprevSelect="-1">
        <li class="H-effective">在校学生</li>
        <li class="H-effective">0-2000</li>
        <li class="H-effective">2000-5000</li>
        <li class="H-effective">5000-9000</li>
        <li class="H-effective">9000以上</li>
    </div>

    <script type="text/javascript">
        var set_data_url = "{{ route('wap.set_data', $id) }}";
    </script>
    {{ HTML::script('assets/js/wap/zepto.min.js') }}
    {{ HTML::script('assets/js/wap/Hselect.js') }}
    {{ HTML::script('assets/js/wap/data.js') }}

    @include('wap.wechat_share')
    @yield('content')

    @include('layout.analytics')
    @yield('content')

</body>

</html>