<!DOCTYPE html>
<html>
<head>
    <title>聘爱 | 下载聘爱</title>

    @include('wap.meta')
    @yield('content')

    {{ HTML::style('assets/css/wap/public.css') }}

    @if($user->sex == 'M')
        {{ HTML::style('assets/css/wap/download-boy.css') }}
    @else
        {{ HTML::style('assets/css/wap/download-girl.css') }}
    @endif
</head>

<body>


    <div class="main  main_tab">
        <div class="main_head_b">
                @if($user->portrait)
                    @if (File::exists('portrait/'.$user->portrait) && File::size('portrait/' . $user->portrait) > 0)
                        {{ HTML::image('portrait/'.$user->portrait) }}
                    @else
                        {{ HTML::image('assets/images/preInfoEdit/peo.png') }}
                    @endif
                @else
                {{ HTML::image('assets/images/preInfoEdit/peo.png') }}
                @endif
        </div>
        <p>
        下载app后登陆方式如下<br><br>
        <span>
            <em>账号</em>: {{ $user->w_id }}<br>
            <em>密码</em>: {{ $user->passcode }}
        <span>
        <a href="{{ route('wap.redirect') }}">
        <span class="down_btn">
            下载喽~
        </span>
        </a>
        </p>

        {{ HTML::image('assets/images/wap/imgs/minilogo.png', '', array('class' => 'logo')) }}
    </div>

    <footer class="common-foot">
        <a href="{{ route('wap.get_like_jobs', $id) }}"><p>招聘会</p></a>
        <a href="{{ route('wap.get_members_index', $id) }}"><p>淘简历</p></a>
        <a href="{{ route('wap.office', $id) }}"><p>办公室</p></a>
        <a href="{{ route('wap.get_download_app', $id) }}?type=tab" class="active"><p>下载聘爱</p></a>
    </footer>

    @include('wap.wechat_share')
    @yield('content')

    @include('layout.analytics')
    @yield('content')

</body>

</html>