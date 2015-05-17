<!DOCTYPE html>
<html lang="{{ Session::get('language', Config::get('app.locale')) }}">
<head>
    <title>{{ Lang::get('navigation.forum') }} | {{ Lang::get('navigation.pinai') }}</title>

    @include('layout.meta')
    @yield('content')


    {{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}

    {{ HTML::style('assets/fancybox-2.1.5/jquery.fancybox.css') }}

    {{ Minify::stylesheet(array(
        '/assets/css/reset.css',
        '/assets/css/nav.css',
        '/assets/css/lu-public.css',
        '/assets/css/forum.css'
    )) }}

    @include('layout.theme')
    @yield('content')

    {{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
</head>
<body>