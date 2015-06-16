<!DOCTYPE html>
<html>
<head>
    <title>聘爱 | 招聘大厅</title>

    @include('wap.meta')
    @yield('content')

    {{ HTML::style('assets/css/wap/public.css') }}
    {{ HTML::style('assets/css/wap/recruitmentHall.css') }}

</head>

<body>

    <header class="common-top boxSha">
        <a href="javascript:void(0);" id="chooseCity"><span>招聘会
            <!-- 请选择城市{{-- HTML::image('assets/images/wap/imgs/down.png') --}} -->
        </span></a>
        <a href="{{ route('wap.get_download_app', $id) }}?type=recruit" id="meToo">我也聘</a>
    </header>
    <ul id="list">
        @foreach($datas as $data)
        <?php
            $user = User::find($data->user_id);
            switch ($user->sex) {
                case 'M':
                    $title = '聘妻: ';
                    break;

                default:
                    $title = '聘夫: ';
                    break;
            }
        ?>
        <li><a href="{{ route('wap.show_like_job', $id) }}?job_id={{ $data->id }}">{{ $title . $data->title }}</a></li>
        @endforeach

    </ul>
    {{ pagination($datas->appends(Input::except('page')), 'layout.paginator') }}
    <footer class="common-foot">
        <a href="{{ route('wap.get_like_jobs', $id) }}" class="active"><p>招聘会</p></a>
        <a href="{{ route('wap.get_members_index', $id) }}"><p>淘简历</p></a>
        <a href="{{ route('wap.office', $id) }}"><p>办公室</p></a>
        <a href="{{ route('wap.get_download_app', $id) }}?type=tab"><p>下载聘爱</p></a>
    </footer>
</body>

</html>