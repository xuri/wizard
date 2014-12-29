<!DOCTYPE html>
<html>
<head>
	<title>单身公寓 | 聘爱网</title>

	@include('layout.meta')
	@yield('content')

	{{ HTML::style('assets/css/reset.css') }}
	{{ HTML::style('assets/css/lu-public.css') }}
	{{ HTML::style('assets/css/forum.css') }}
	{{ HTML::style('assets/css/nav.css') }}
	{{ HTML::style('assets/fancybox-2.1.5/jquery.fancybox.css') }}
	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}

	@include('layout.theme')
	@yield('content')

	{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
</head>
<body>