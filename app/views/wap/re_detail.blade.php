<!DOCTYPE html>
<html>
<head>
    <title>聘爱 | 招聘详情</title>

    @include('wap.meta')
    @yield('content')

    {{ HTML::style('assets/css/wap/public.css') }}

    @if($user->sex == 'M')
        {{ HTML::style('assets/css/wap/re_detail-boy.css') }}
    @else
        {{ HTML::style('assets/css/wap/re_detail-girl.css') }}
    @endif
</head>

<body>

    <header class="single-top">
              <a href="{{ URL::previous() }}"> </a>
        简历缘
    </header>

    <div class="main">
        <div class="main_head_b">
             <a href="{{ route('wap.get_members_show', $id) }}?user_id={{ $user->id }}">
                @if(File::exists('portrait/' . $user->portrait) && File::size('portrait/' . $user->portrait) > 0)
                    {{ HTML::image('portrait/' . $user->portrait) }}
                @else
                    {{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('class' => 'lu_img')) }}
                @endif
             </a>
        </div>
        <p>
            {{ $job->content }}<br><br>
            要求：<br>
            1. {{ $job->rule_1 }}<br>
            2. {{ $job->rule_2 }}<br>
            @if($job->rule_3)
                3. {{ $job->rule_3 }}<br>
            @endif

            @if($job->rule_4)
                4. {{ $job->rule_4 }}<br>
            @endif

            @if($job->rule_5)
                5. {{ $job->rule_5 }}<br>
            @endif
        </p>
        {{ HTML::image('assets/images/wap/imgs/minilogo.png', '', array('class' => 'logo')) }}
    </div>

    <footer class="common-foot">
        <a href="{{ route('wap.get_like_jobs', $id) }}" class="active"><p>招聘会</p></a>
        <a href="{{ route('wap.get_members_index', $id) }}"><p>淘简历</p></a>
        <a href="{{ route('wap.office', $id) }}"><p>办公室</p></a>
        <a href="{{ route('wap.get_download_app', $id) }}?type=tab"><p>下载聘爱</p></a>
    </footer>

</body>

</html>