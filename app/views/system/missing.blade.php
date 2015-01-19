<!DOCTYPE html>
<html>
<head>
	<title>找不到页面 | 聘爱网</title>
	<meta content="20; {{ route('home') }}" http-equiv="refresh">
	@include('layout.meta')
	@yield('content')
	{{-- Included CSS Files --}}
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
					<a href="{{ route('home') }}/article/privacy.html" target="_blank">服务条款</a>
					<span class="footer-separator">|</span>
					<a href="{{ route('support.index') }}" target="_blank">意见反馈</a>
					<span class="footer-separator">|</span>
					<a href="{{ route('home') }}/article/about.html" rel="nofollow" target="_blank">关于我们</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>