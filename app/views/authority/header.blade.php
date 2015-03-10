<!DOCTYPE html>
<html lang="zh_CN">
<head>
	<title>注册登陆 | 聘爱</title>

	@include('layout.meta')
	@yield('content')

	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}

	{{ Minify::stylesheet(array(
		'/assets/css/reset.css',
		'/assets/css/nav.css',
		'/assets/css/signin.css'
	)) }}
</head>
<body>
	<noscript>
		@include('system.javascriptNotSupport')
		@yield('content')
		<style type="text/css">
			.nav, #login_wrap, .footer {
				display: none;
			}
		</style>
	</noscript>