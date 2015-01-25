<!DOCTYPE html>
<html>
<head>
	<title>我的来信 | 聘爱</title>

	@include('layout.meta')
	@yield('content')

	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}

	{{ Minify::stylesheet(array(
		'/assets/css/reset.css',
		'/assets/css/nav.css',
		'/assets/css/preInfo.css',
		'/assets/css/notifications.css'
	)) }}

	@include('layout.theme')
	@yield('content')
</head>
<body>