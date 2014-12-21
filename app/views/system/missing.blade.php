<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0046)http://www.kiss.com/ildfjglkdjg/ksdjfhsdj.html -->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title>找不到页面 | 聘爱网</title>
	<!-- Uncomment to make IE8 render like IE7 -->
	<!-- <meta http-equiv="X-UA-Compatible" content="IE=7" /> -->
	<!-- Set the viewport width to device width for mobile -->
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="20; {{ route('home') }}" http-equiv="refresh">
	<!-- Included CSS Files -->
	{{ HTML::style('assets/css/404.css') }}
</head>
<body class="" screen_capture_injected="true" cz-shortcut-listen="true">
	<header class="primary-header">
		<div class="row">
			<div class="twelve columns">
				<div class="column-inner clearfix">
					<a href="{{ route('home') }}" class="primary-header-logo fl">
						{{ HTML::image('assets/images/404_logo.png', '', array('width' => '163', 'id' => 'primry-header-logo-kiss', 'height' => '31')) }}
					</a>
					<a href="{{ route('signin') }}" class="primary-header-signin fr typeface-bold underlined-link">登陆</a>
				</div>
			</div>
		</div>
		<hr class="rainbow">
	</header>

	<div class="row">
		<div class="twelve columns">
			<div class="column-inner"></div>
		</div>
	</div>
	<div class="row">
		<div class="eight columns centered">
			<div class="column-inner">
				<div class="panel">
					<div class="panel-body">
						<div class="ibc" style="margin: 20px 0">
							<div class="ibc-content">
								{{ HTML::image('assets/images/404.png', '', array('width' => '200', 'height' => '200')) }}
							</div>
						</div>
						<div class="ibc" style="margin: 20px 0">
							<div class="ibc-content">
								<h1 class="bold">找不到页面</h1>
							</div>
						</div>
						<p style="margin: 10px 44px; text-align: left; font-size: 14px;">很遗憾，您访问的页面找不到了，您可以访问 <a href="{{ route('home') }}">网站首页</a> 或 <a href="{{ route('signup') }}">新用户注册</a>.</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="eight columns hack-ie-z-index">
			<article class="page column-inner"></article>
		</div>
		<div class="four columns">
			<aside class="column-inner sidebar"></aside>
		</div>
	</div>
	<div class="row">
		<div class="centered columns eight">
			<div class="column-inner footer">
				<div class="footer-copyright">Copyright &copy; 2013 - <?php echo date('Y'); ?> pinai521.com. All rights reserved.</div>
				<hr class="footer-hr">
				<div class="footer-nav">
					<a href="#" target="_blank">使用帮助</a>
					<span class="footer-separator">|</span>
					<a href="#" target="_blank">意见反馈</a>
					<span class="footer-separator">|</span>
					<a href="#" rel="nofollow" target="_blank">关于我们</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>