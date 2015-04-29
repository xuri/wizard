<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
	<title>{{ Lang::get('navigation.pinai') }}丨全国首个大学生恋爱APP</title>
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
</style>
<body>
	{{ HTML::image('assets/images/wap/corner.png', '', array('class' => 'corner')) }}
	<div class="top">
		<a href="{{ route('wap.members') }}/?sex=M">{{ HTML::image('assets/images/wap/male.png', '', array('class' => 'sex_btn')) }}</a>
	</div>
	<div class="bottom">
		<a href="{{ route('wap.members') }}/?sex=F">{{ HTML::image('assets/images/wap/female.png', '', array('class' => 'sex_btn')) }}</a>
	</div>
	@include('layout.analytics')
	@yield('content')
</body>
</html>