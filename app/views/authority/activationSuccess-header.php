<!DOCTYPE html>
<html lang="zh_CN">
<head>
	<title>激活成功 | 聘爱网</title>

	@include('layout.meta')
	@yield('content')

	<meta content="3; {{ route('account') }}" http-equiv="refresh">

	{{ HTML::style('assets/css/reset.css') }}
	{{ HTML::style('assets/css/nav.css') }}
	{{ HTML::style('assets/css/signin.css') }}
	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}
</head>
<body>