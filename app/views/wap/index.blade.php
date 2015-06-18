<!DOCTYPE html>
<html lang="en">
<head>
    <title>黑工程大三学生创办聘爱</title>
    @include('layout.meta')
    @yield('content')
</head>

<style type="text/css">
body, h3, span, ul{ margin:0; padding:0;}
img{border:none; vertical-align:top;}
a{ text-decoration:none; }
li{ list-style:none; }
body{
    background:#fe949e;
    font-size:8px;
    font-family:Microsoft YaHei,SimHei,Arial,Pro LiHei Pro Medium;
}
.top {
    width: 100%;
    height: 50%;
    background-color: #76d2fb;
    text-align: center;
}

.bottom {
    width: 100%;
    height: 50%;
    background-color: #fe949e;
    text-align: center;
}

.sex_btn {
    width: 50%;
    margin: 30% 0;
}

.corner {
    position: absolute;
    width: 30%;
    left: -7%;
    top: -5%;

}

.agree {
    color: #FFF;
    text-align: center;
    font-size: 1.2em;
    margin: 0 0 0.5em 0;
}

.agree a {
    color: #FFF;
}
</style>
<body>
    {{ HTML::image('assets/images/wap/corner.png', '', array('class' => 'corner')) }}
    <div class="top">
        <a href="{{ route('wap.index') }}/?sex=M">{{ HTML::image('assets/images/wap/male.png', '', array('class' => 'sex_btn')) }}</a>
    </div>
    <div class="bottom">
        <a href="{{ route('wap.index') }}/?sex=F">{{ HTML::image('assets/images/wap/female.png', '', array('class' => 'sex_btn')) }}</a>
    </div>
    <div class="agree">使用聘爱即代表您已同意<a href="{{ route('home') }}/article/privacy.html">《服务条款》</a></div>

    @include('wap.wechat_share')
    @yield('content')

    @include('layout.analytics')
    @yield('content')

</body>
</html>