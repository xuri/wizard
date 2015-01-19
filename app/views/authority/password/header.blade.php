<!DOCTYPE html>
<html>
<head>
	<title>重置密码 | 聘爱网</title>
	@include('layout.meta')
	@yield('content')

	{{ Minify::stylesheet(array(
		'/assets/css/reset.css',
		'/assets/css/nav.css',
		'/assets/css/getPasswordBack.css'
	)) }}

	{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
</head>
<body>