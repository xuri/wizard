<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
	<title>message</title>
</head>
<Style type="text/css">
body, h2, p{margin:0; padding:0;}
a{ text-decoration:none; }
.clear { zoom:1; }
.clear:after { content:''; display:block; clear:both; }
body{
	font-size:8px;
	color:#ffffff;
	font-weight:bold;
	font-family:Microsoft YaHei,SimHei,Arial,Pro LiHei Pro Medium;
}
#top{
	height:260px;
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
	margin-bottom:22px;
	margin-left:-66px;
	text-align:center;
	background:#fe959e;
	color:#ffffff;
	font-size:1.5em;
	border-radius:8px;
}
#back {
	position: fixed;
	top: 40px;
	left: 40px;
	font-size: 12px;
	border-radius: 3px;
}
</style>
<body>
	<span id="back">← 返回</span>
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
		<p class="information">{{ $data->born_year }}</p>
		<p class="information" id="school">{{ $data->school }} {{ $data->grade }}</p>
		<p class="information">{{ $constellationInfo['name'] }}</p>
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
			<a id="download" href="{{ route('home') }}">下载聘爱追Ta</a>
		</div>
	</div>
</body>
</html>