<!DOCTYPE html>
<html>
<head>
	<title>帮助与支持 | 聘爱网</title>

	@include('layout.meta')
	@yield('content')

	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}

	{{ Minify::stylesheet(array(
		'/assets/css/reset.css',
		'/assets/css/nav.css',
		'/assets/css/preInfo.css',
		'/assets/css/support.css'
	)) }}

	@include('layout.theme')
	@yield('content')
</head>
<body>