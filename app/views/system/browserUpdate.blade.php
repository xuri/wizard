<!DOCTYPE HTML PUBliC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html lang="zh-CN">

<head>
	<title>浏览器升级建议 | 聘爱</title>

	@include('layout.meta')
	@yield('content')

	<style type="text/css">
		body {
			background-color: #eff1f2;
		}
		.not-support-browser {
			border-right: #ced0d3 1px solid;
			padding-right: 0px;
			border-top: #ced0d3 1px solid;
			padding-left: 0px;
			padding-bottom: 0px;
			margin: 100px auto;
			border-left: #ced0d3 1px solid;
			width: 600px;
			padding-top: 0px;
			border-bottom: #ced0d3 1px solid;
			height: 350px;
			background-color: #ffffff;
			border-radius: 5px;
			moz-border-radius: 5px;
			webkit-border-radius: 5px;
		}
		.rixuonline-logo {
			background-color: #333;
		}
		.not-support-browser-contant {
			font-size: 18px;
			margin: 35px 25px;
			color: #666666;
		}
		.not-support-browser-contant span {
			display: block;
			font-weight: bold;
			font-size: 18px;
			color: #565656;
		}
		.not-support-browser-contant ul {
			padding-right: 0px;
			list-style: none none outside;
			display: inline-block;
			padding-left: 0px;
			padding-bottom: 0px;
			margin: 35px 80px;
			padding-top: 0px;
		}
		.not-support-browser-contant ul li {
			float: left;
			margin-left: 10px;
		}
		.not-support-browser-contant ul li a {
			text-decoration: none;
		}
		.not-support-browser-contant ul li img {
			width: 64px;
			height: 64px;
		}
		.tech-support a {
			float: right;
			color: #565656;
			margin-right: 40px;
			font-family: arial, sans-serif;
			text-decoration: none;
		}
		/* Hide Analitycs Code */
		span#cnzz_stat_icon_1252933521 {
			display: none;
		}
	</style>
</head>

<body>
	{{-- Content --}}
	<div class="not-support-browser">
		<div class="rixuonline-logo">
			{{ HTML::image('assets/images/browser_not_support/pinai.png') }}
		</div>
		<div class="not-support-browser-contant">
			<span>欢迎来到聘爱，为了获得更好的用户体验，我们建议您使用下列浏览器的最新版本浏览。</span>
			<ul>
				<li>
					<a href="http://www.google.com/chrome/eula.html?hl=en&standalone=1" target="_blank">
						{{ HTML::image('assets/images/browser_not_support/chromelogo.png', 'Google Chrome', array('border' => '0', 'title' => 'Google Chrome')) }}
					</a>
				</li>
				<li>
					<a href="http://www.mozilla.org/en-US/firefox/fx/" target="_blank">
						{{ HTML::image('assets/images/browser_not_support/firefoxlogo.png', 'Mozilla Firefox', array('border' => '0', 'title' => 'Mozilla Firefox')) }}
					</a>
				</li>
				<li>
					<a href="http://www.apple.com/safari/download/" target="_blank">
						{{ HTML::image('assets/images/browser_not_support/safari_logo.png', 'Apple Safari', array('border' => '0', 'title' => 'Apple Safari')) }}
					</a>
				</li>
				<li>
					<a href="http://www.opera.com/products/" target="_blank">
						{{ HTML::image('assets/images/browser_not_support/operalogo.png', 'Opera', array('border' => '0', 'title' => 'Opera')) }}
					</a>
				</li>
				<li>
					<a href="http://windows.microsoft.com/en-us/internet-explorer/products/ie/home" target="_blank">
						{{ HTML::image('assets/images/browser_not_support/ielogo.png', 'Internet Explorer 9+', array('border' => '0', 'title' => 'Internet Explorer 9+')) }}
					</a>
				</li>
			</ul>
		</div>
		<div class="tech-support">
			<a href="{{ route('home') }}" target="_blank" title="聘爱" alt="聘爱">www.pinai521.com</a>
		</div>
	</div>
	{{-- not-support-browser div end --}}
	@include('layout.analytics')
	@yield('content')
</body>
</html>