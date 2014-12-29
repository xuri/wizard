<!DOCTYPE html>
<html>
<head>
	<title>追我的人 | 聘爱网</title>

	@include('layout.meta')
	@yield('content')

	{{ HTML::style('assets/css/reset.css') }}
	{{ HTML::style('assets/css/courtship.css') }}
	{{ HTML::style('assets/css/nav.css') }}
	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}
	{{ HTML::style('assets/css/chat.css') }}

	@include('layout.theme')
	@yield('content')
</head>
<body>