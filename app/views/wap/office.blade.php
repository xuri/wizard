<!DOCTYPE html>
<html>
<head>
    <title>聘爱 | 办公室</title>

    @include('wap.meta')
    @yield('content')

    {{ HTML::style('assets/css/wap/public.css') }}

    @if($user->sex == 'M')
        {{ HTML::style('assets/css/wap/office-boy.css') }}
    @else
        {{ HTML::style('assets/css/wap/office-girl.css') }}
    @endif

</head>
<body>
    <header id="header" class="clear">
        @if($user->portrait)
            @if (File::exists('portrait/'.$user->portrait) && File::size('portrait/' . $user->portrait) > 0)
                {{ HTML::image('portrait/'.$user->portrait) }}
            @else
                {{ HTML::image('assets/images/preInfoEdit/peo.png') }}
            @endif
        @else
        {{ HTML::image('assets/images/preInfoEdit/peo.png') }}
        @endif
        <h2>{{ $user->nickname }}</h2>
        <p>{{ getConstellation($user->constellation)['name'] }}</p>
        <p>{{ $user->school }}</p>
    </header>
    <ul id="body">
        <li><a href="{{ route('wap.fate', $id) }}">简历缘</a></li>
        <li><a href="{{ route('wap.get_download_app', $id) }}?type=tab">单身公寓<em>(app可玩)</em></a></li>
        <li><a href="{{ route('wap.get_download_app', $id) }}?type=tab">上市排行<em>(app可玩)</em></a></li>
        <li><a href="{{ route('wap.get_download_app', $id) }}?type=tab">聘爱指数<em>(app可玩)</em></a></li>
        <li><a href="{{ route('wap.get_download_app', $id) }}?type=tab">下载聘爱</a></li>
    </ul>
    <footer class="common-foot">
        <a href="{{ route('wap.get_like_jobs', $id) }}"><p>招聘会</p></a>
        <a href="{{ route('wap.get_members_index', $id) }}"><p>淘简历</p></a>
        <a href="{{ route('wap.office', $id) }}" class="active"><p>办公室</p></a>
        <a href="{{ route('wap.get_download_app', $id) }}?type=tab"><p>下载聘爱</p></a>
    </footer>

</body>

</html>