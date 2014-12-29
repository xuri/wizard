<!DOCTYPE html>
<html>
<head>
	<title>个人中心</title>
	<meta charset="utf-8" />
	{{ HTML::style('assets/css/reset.css') }}
	{{ HTML::style('assets/css/nav.css') }}
	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}
	{{ HTML::style('assets/css/lu-public.css') }}
	{{ HTML::style('assets/css/details.css') }}
	@include('layout.theme')
	@yield('content')
</head>
<body>