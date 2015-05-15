<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
	<title>黑工程大三学生创办聘爱</title>
</head>

<style type="text/css">
body, h3, span, ul{ margin:0; padding:0;}
img{border:none; vertical-align:top;}
a{ text-decoration:none; }
li{ list-style:none; }
body{
	max-width:640px;
	background:#eeeeee;
	font-size:8px;
    font-family:Microsoft YaHei,SimHei,Arial,Pro LiHei Pro Medium;
}
#top{
	width:100%;
	height:54px;
	background:rgba(255,255,255,0.7);
	position:fixed;
	z-index:20;
}
#top_logo{
	width:46px;
	margin-top:4px;
	margin-left:6px;
	float:left;
}
#top_slogans{
	width:178px;
	margin-top:6px;
	margin-left:6px;
	color:#f76c6c;
	font-size:1.25em;
	font-weight:bold;
	float:left;
}
#top_slogans h3{
	font-size:1.5625em;
	margin-bottom:3px;
}
#top_download{
	position:absolute;
	top:14px;
	right:10px;
	width:70px;
	height:24px;
	line-height:24px;
	text-align:center;
	font-size:1.4em;
	font-weight:bold;
	color:#ffffff;
	background:#f76c6c;
	border-radius:7px;
}
.tab{
	position:absolute;
	top:54px;
	background:#ffffff;
	width:100%;
}
.tab a{
	float:left;
	width:50%;
	text-align:center;
	height:32px;
	line-height:32px;
	font-size:1.75em;
	font-weight:bold;
	color:#ffb7b7;
}
.tab span{
	display:block;
	width:100%;
	height:1px;
	background:#d9d7d7;
}
.on{ color:#44bbfb!important; }
.on span{ background:#3c92e9; }
#list{ padding-top:70px; }
#list li{
	margin-bottom:1px;
	padding-top:9px;
	padding-bottom:9px;
	padding-left:13px;
	background:#ffffff;
	font-weight:bold;
}
.list_head{
	float:left;
	display:block;
	width:38px;
	height:38px;
	border-radius:19px;
	overflow:hidden;
}
.list_head img{ width:38px; }
.list_introduction{
	float:left;
	margin-left:11px;
	margin-top:4px;
	color: #333;
}
.list_introduction img{
	width:12px;
	margin-right:4px;
	float:left;
}
.list_introduction span{
	display:block;
	height:16px;
	line-height:14px;
	font-size:1.4em;
	float: left;
}
.list_lable{
	margin-top:40px;
	margin-left:50px;
}
.list_lable span{
	float:left;
	width:75px;
	height:27px;
	line-height:27px;
	margin-right:10px;
	margin-bottom:4px;
	text-align:center;
	background:#ffa6a6;
	border-radius:10px;
	font-size:1.6em;
	color:#ffffff;
}

.list_lable span:nth-of-type(1){ background:#febe4d; }
.list_lable span:nth-of-type(2){ background:#fbe539; }
.list_lable span:nth-of-type(3){ background:#3c92e9; }
.list_lable span:nth-of-type(4){ background:#4aed3a; }
.list_lable span:nth-of-type(5){ background:#ffa6a6; }
.list_lable span:nth-of-type(6){ background:#76d2fb; }
.list_lable span:nth-of-type(7){ background:#febe4d; }
.list_lable span:nth-of-type(8){ background:#fbe539; }
.list_lable span:nth-of-type(9){ background:#3c92e9; }
.list_lable span:nth-of-type(10){ background:#4aed3a; }
.list_lable span:nth-of-type(11){ background:#ffa6a6; }
.list_lable span:nth-of-type(12){ background:#76d2fb; }
.list_lable span:nth-of-type(13){ background:#febe4d; }
.list_lable span:nth-of-type(14){ background:#fbe539; }
.list_lable span:nth-of-type(15){ background:#3c92e9; }
.list_lable span:nth-of-type(16){ background:#4aed3a; }
.list_lable span:nth-of-type(17){ background:#ffa6a6; }
.list_lable span:nth-of-type(18){ background:#76d2fb; }
.clear { zoom:1; }
.clear:after { content:''; display:block; clear:both; }

.lu_paging {
	text-align: center;
}

.lu_paging a {
	margin: 2em auto;
	display: block;
	padding: 0.2em 0.5em;
	top:14px;
	right:10px;
	width:70px;
	height:24px;
	line-height:24px;
	text-align:center;
	font-size:1.4em;
	font-weight:bold;
	color:#ffffff;
	background:#f76c6c;
	border-radius:3px;
}
</style>
<body>
	<div id="top">
		{{ HTML::image('assets/images/wechat/logo.png', '', array('id' => 'top_logo')) }}
		<span id="top_slogans"><h3>{{ Lang::get('navigation.pinai') }}</h3>让你的大学不留白</span>
		<a id="top_download" href="{{ route('home') }}">下载{{ Lang::get('navigation.pinai') }}</a>
	</div>
	<ul id="list">
		@foreach($users as $id)
		<?php
			$data				= User::where('id', $id)->first();
			$profile			= Profile::where('user_id', $id)->first();

			// Get user's constellation
			$constellationInfo	= getConstellation($profile->constellation);
			$tag_str			= array_unique(explode(',', substr($profile->tag_str, 1)));
		?>
		<a href="{{ route('wap.show', $data->id) }}">
			<li class="clear">
				<span class="list_head">
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
				<div class="list_introduction">
					@if($data->sex == 'M')
					{{ HTML::image('assets/images/sex/male_icon.png') }}
					@elseif($data->sex == 'F')
					{{ HTML::image('assets/images/sex/female_icon.png') }}
					@else
					{{ HTML::image('assets/images/sex/no_icon.png') }}
					@endif
					<span>{{ $data->nickname }}</span>
					<br />
					<span>{{ $data->school }}</span>
				</div>
				<div class="list_lable">
					@foreach($tag_str as $tag)
						<span>{{ getTagName($tag) }}</span>
					@endforeach
				</div>
			</li>
		</a>
		@endforeach
	</ul>
	<div class="lu_paging"><a href="{{ route('wap.more') }}">查看更多</a></div>

	@include('layout.analytics')
	@yield('content')

</body>
</html>