<!DOCTYPE html>
<html>
<head>
    <title>{{ Lang::get('navigation.discover') }} | {{ Lang::get('navigation.pinai') }}</title>

    @include('layout.meta')
    @yield('content')

    {{ HTML::style('assets/font-awesome-4.3.0/css/font-awesome.min.css') }}

    {{ Minify::stylesheet(array(
        '/assets/css/reset.css',
        '/assets/css/nav.css',
        '/assets/css/fatehere.css'
    )) }}

    @include('layout.theme')
    @yield('content')
</head>
<body>