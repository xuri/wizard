<!DOCTYPE html>
<html>
<head>
	{{-- The Meta --}}
	<title>聘爱网 | 中国首个面向大学为群体的情侣招聘网站</title>

	@include('layout.meta')
	@yield('content')

	{{-- The Stylesheets --}}

	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}

	{{ Minify::stylesheet(array(
		'/assets/css/style.css',
		'/assets/css/nav.css'
	)) }}
</head>
<body>