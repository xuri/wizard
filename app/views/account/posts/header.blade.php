<!DOCTYPE html>
<html lang="{{ Session::get('language', Config::get('app.locale')) }}">
<head>
    <title>{{ Lang::get('navigation.my_posts') }} | {{ Lang::get('navigation.pinai') }}</title>

    @include('layout.meta')
    @yield('content')

    {{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}

    {{ Minify::stylesheet(array(
        '/assets/css/reset.css',
        '/assets/css/nav.css',
        '/assets/css/preInfo.css',
        '/assets/css/myposts.css'
    )) }}

    @include('layout.theme')
    @yield('content')
</head>
<body>