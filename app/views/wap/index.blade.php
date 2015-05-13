<?php
	include_once( app_path('api/wechat/jssdk.php') );
	$jssdk			= new JSSDK("wx85a303018cc9100b", "be2909ec1f4f590feb25aa6638a63d5f");
	$signPackage	= $jssdk->GetSignPackage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ Lang::get('navigation.pinai') }}丨全国首个大学生恋爱APP</title>
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
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript">
	  wx.config({
		debug: false,
		appId: '<?php echo $signPackage["appId"];?>',
		timestamp: <?php echo $signPackage["timestamp"];?>,
		nonceStr: '<?php echo $signPackage["nonceStr"];?>',
		signature: '<?php echo $signPackage["signature"];?>',
		jsApiList: [
		  // 所有要调用的 API 都要加到这个列表中
		'onMenuShareTimeline'
		]
	  });

	 wx.ready(function () {
			// 在这里调用 API
			wx.onMenuShareTimeline({
			title: '聘爱丨全国首个大学生恋爱APP', // 分享标题
			link: 'http://www.pinai521.com/wap', // 分享链接
			imgUrl: "http://www.pinai521.com/assets/images/wechat/boy.jpg", // 分享图标
			success: function () {
			},
			cancel: function () {
			},
			fail: function (res) {
			alert('wx.onMenuShareTimeline:fail: '+JSON.stringify(res));
			}
			});
	  });
	 wx.error(function (res) {
			alert('wx.error: '+JSON.stringify(res));
	  });
	</script>

	@include('layout.analytics')
	@yield('content')

</body>
</html>