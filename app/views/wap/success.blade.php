<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>{{ Lang::get('navigation.pinai') }} | {{ Lang::get('index.title') }}</title>
    {{ HTML::style('assets/css/wap/public.css') }}
</head>
<style type="text/css">
body, h2, p{margin:0; padding:0;}
a{ text-decoration:none; }
.clear { zoom:1; }
.clear:after { content:''; display:block; clear:both; }
body{
    font-size:8px;
    background-color:#fe949e;
    font-weight:bold;
    font-family:Microsoft YaHei,SimHei,Arial,Pro LiHei Pro Medium;
    text-align: center;
}

img {
    width: 100%;
    margin-top: 20%;
}

a.download {
    border: solid 2px #FFF;
    border-radius: 5px;
    padding: 0.4em 1.2em;
    font-size: 3.5em;
    text-decoration: none;
    color: #FFF;
    margin: 2em;
    z-index: 10;
    font-family: Microsoft YaHei,SimHei,Arial,Pro LiHei Pro Medium;
}

h2 {
    color: #ffff01;
    font-size: 3.5em;
    padding: 0.2em;
}

.password {
    margin-bottom: 2em;
}
</style>
<body>
    {{ HTML::image('assets/images/wap/success.png') }}
    <h2>账号：{{ Cookie::get('w_id') }}</h2>
    <h2 class="password">密码：{{ Cookie::get('password') }}</h2>
    <a href="{{ route('wap.redirect') }}" class="download">下载{{ Lang::get('navigation.pinai') }}</a>

    <footer class="common-foot">
        <a href="{{ route('wap.get_like_jobs', $id) }}" class="active"><p>招聘会</p></a>
        <a href="{{ route('wap.get_members_index', $id) }}"><p>淘简历</p></a>
        <a href="{{ route('wap.office', $id) }}"><p>办公室</p></a>
        <a href="{{ route('wap.get_download_app', $id) }}?type=tab"><p>下载聘爱</p></a>
    </footer>

    @include('wap.wechat_share')
    @yield('content')

    @include('layout.analytics')
    @yield('content')

</body>
</html>