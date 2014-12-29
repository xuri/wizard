<!DOCTYPE html>
<html>
<head>
	<title>我的帖子 | 聘爱网</title>

	@include('layout.meta')
	@yield('content')

	{{ HTML::style('assets/css/reset.css') }}
	{{ HTML::style('assets/css/preInfo.css') }}
	{{ HTML::style('assets/css/myposts.css') }}
	{{ HTML::style('assets/css/nav.css') }}
	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}

	@include('layout.theme')
	@yield('content')
</head>
<body>