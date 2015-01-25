<!DOCTYPE html>
<html>
<head>
	{{-- The Meta --}}
	<title>聘爱 | 专注于大学生的恋爱平台</title>

	@include('layout.meta')
	@yield('content')

	{{-- The Stylesheets --}}
	{{ HTML::style('assets/css/mobile.css') }}
	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}
</head>
<body>
	<div class="nav">
		<center class="nav-content">聘爱 —— 专注于大学生的恋爱平台</center>
	</div>
	<center id="content">
		<p>聘爱网，我们主张“心灵美”, &nbsp; 我们真正为你寻找靠谱的爱</p>
		<a class="download-app" href="#"><i class="fa fa-apple"></i> &nbsp;苹果客户端下载</a>
		<a class="download-app ios" href="#"><i class="fa fa-android"></i> &nbsp;安卓客户端下载</a>
		<div id="iphone">{{ HTML::image('assets/images/mobile-iphone.png', 'alt content', array('width' => '200')); }}</div>
	</center>

	<div class="footer">
		<center class="nav-content">
			Copyright &copy; 2013 - <?php echo date('Y'); ?> <a href="http://www.jinglingkj.com" target="_blank">哈尔滨精灵科技有限责任公司</a> All rights reserved. <a href="http://www.miitbeian.gov.cn/" target="_blank">黑ICP备14007294号</a>
		</center>
	</div>
</body>
</html>