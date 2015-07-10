<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>{{ Lang::get('navigation.pinai') }}丨全国首个大学生恋爱APP</title>
    {{ HTML::style('assets/css/wap/public.css') }}

</head>
<style type="text/css">
body, h2, p{margin:0; padding:0;}
a{ text-decoration:none; }
.clear { zoom:1; }
.clear:after { content:''; display:block; clear:both; }
body{
    font-size:8px;
    color:#ffffff;
    font-weight:normal;
    font-family:Microsoft YaHei,SimHei,Arial,Pro LiHei Pro Medium;
}
#top{
    height:310px;
    padding-top:50px;
    background:#fe959e;
}
.center{ padding-left:50%; }
#head{
    display:block;
    width:122px;
    height:122px;
    margin-left:-61px;
    border-radius:61px;
    overflow:hidden;
}
#head img{ width:122px; }
#name{
    margin-top:20px;
    font-size:2.5em;
    margin-bottom:30px;
    text-align:center;
}
.line{
    display:block;
    width:60%;
    margin-left:20%;
    height:1px;
    background:#ffffff;
}
.information{
    float:left;
    margin-top:26px;
    width:25%;
    text-align:center;
    font-size:1.7em;
}
#school{ width:50%; }
/*#down{ text-align:center; }*/
#lable{
    margin-top:25px;
    width:260px;
    margin-left:-130px;
}
#lable span{
    float:left;
    width:76px;
    height:27px;
    line-height:27px;
    margin-right:10px;
    margin-bottom:10px;
    text-align:center;
    font-size:1.5em;
    border-radius:8px;
}
#lable span:nth-of-type(1){ background:#febe4d; }
#lable span:nth-of-type(2){ background:#fbe539; }
#lable span:nth-of-type(3){ background:#3c92e9; }
#lable span:nth-of-type(4){ background:#4aed3a; }
#lable span:nth-of-type(5){ background:#ffa6a6; }
#lable span:nth-of-type(6){ background:#76d2fb; }
#lable span:nth-of-type(7){ background:#febe4d; }
#lable span:nth-of-type(8){ background:#fbe539; }
#lable span:nth-of-type(9){ background:#3c92e9; }
#lable span:nth-of-type(10){ background:#4aed3a; }
#lable span:nth-of-type(11){ background:#ffa6a6; }
#lable span:nth-of-type(12){ background:#76d2fb; }
#lable span:nth-of-type(13){ background:#febe4d; }
#lable span:nth-of-type(14){ background:#fbe539; }
#lable span:nth-of-type(15){ background:#3c92e9; }
#lable span:nth-of-type(16){ background:#4aed3a; }
#lable span:nth-of-type(17){ background:#ffa6a6; }
#lable span:nth-of-type(18){ background:#76d2fb; }
.introduce{
    margin-top:14px;
    margin-bottom:12px;
    padding-left:15%;
    padding-right:15%;
    text-align:center;
    color:#858585;
    font-size:1.35em;
}
#down .line{ background:#858585; }
#download{
    display:block;
    width:132px;
    height:30px;
    line-height:30px;
    margin-top:20px;
    margin-bottom: 70px;
    margin-left:-66px;
    text-align:center;
    background:#fe959e;
    color:#ffffff;
    font-size:1.5em;
    border-radius:8px;
}

#back {
    float: left;
    margin: 5.3% 0 0 5.5%;
    font-size: 1.4em;
    width: 5em;
    color: #FFF;

}
#back a {
    font-size: 1.4em;
    color: #FFF;
}
.information {
    float:left;
    line-height: 2em;
    text-align:left;
    font-size:1.7em;
    margin-top: 0;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
}
.info_left {
    width: 60%;
}

.info_right {
    width: 40%;
}

.information_box {
    margin-left:5%;
    margin-top: 2em;
}
.points {
    float: right;
    font-size: 1.8em;
    margin: 5.5% 5.5% 0 0;
}

.points_num {
    color: #fb3a3d;
}
</style>
<body>
    <span id="back"><a href="{{ URL::previous() }}">← 返回</a></span>
    <div class="points">聘爱指数: <span class="points_num">{{ $data->points }}</span></div>
    <div id="top">
        <div class="center">
            <span id="head">
                @if($data->portrait)
                    @if (File::exists('portrait/'.$data->portrait) && File::size('portrait/' . $data->portrait) > 0)
                        {{ HTML::image('portrait/'.$data->portrait) }}
                    @else
                        {{ HTML::image('assets/images/preInfoEdit/peo.png') }}
                    @endif
                @else
                {{ HTML::image('assets/images/preInfoEdit/peo.png') }}
                @endif
            </span>
        </div>
        <h2 id="name">{{ $data->nickname }}</h2>
        <span class="line"></span>
        <div class="information_box">
            <p class="information info_left">出生年: {{ $data->born_year }}</p>
            <p class="information info_right">所在地: {{ $province }}</p>
            <p class="information info_left">星 &nbsp; 座: {{ $constellationInfo['name'] }}</p>
            <p class="information info_right">月 &nbsp; 薪: {{ $salary }}</p>
            <p class="information info_left">学 &nbsp; 校: {{ $data->school }}</p>
            <p class="information info_right">入学年: {{ $profile->grade }}</p>
        </div>
    </div>
    <div id="down">
        <div class="center clear">
            <div id="lable">
                @foreach($tag_str as $tag)
                    <span>{{ getTagName($tag) }}</span>
                @endforeach
            </div>
        </div>
        <p class="introduce">{{ $profile->self_intro }}</p>
        <span class="line"></span>
        <p class="introduce">{{ $profile->bio }}</p>
        <div class="center">
            <a id="download" href="{{ route('wap.get_download_app', $id) }}?type=default&friend_id={{ $data->id }}">投递简历</a>
        </div>
    </div>

    <footer class="common-foot">
        <a href="{{ route('wap.get_like_jobs', $id) }}"><p>招聘会</p></a>
        <a href="{{ route('wap.get_members_index', $id) }}" class="active"><p>淘简历</p></a>
        <a href="{{ route('wap.office', $id) }}"><p>办公室</p></a>
        <a href="{{ route('wap.get_download_app', $id) }}?type=tab"><p>下载聘爱</p></a>
    </footer>

    @include('wap.wechat_share')
    @yield('content')

    @include('layout.analytics')
    @yield('content')

</body>
</html>