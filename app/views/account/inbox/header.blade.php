<!DOCTYPE html>
<html>
<head>
	<title>追我的人 | 聘爱网</title>

	@include('layout.meta')
	@yield('content')

	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}

	{{ Minify::stylesheet(array(
		'/assets/css/reset.css',
		'/assets/css/nav.css',
		'/assets/css/courtship.css',
		'/assets/remodal-0.3.0/jquery.remodal.css',
		'/assets/css/chat.css'
	)) }}

	@include('layout.theme')
	@yield('content')
</head>
<body>