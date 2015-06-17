<!DOCTYPE html>
<html lang="{{ Session::get('language', Config::get('app.locale')) }}">
<head>
    {{-- The Meta --}}
    <title>{{ Lang::get('navigation.pinai') }} | {{ Lang::get('index.title') }}</title>

    @include('layout.meta')
    @yield('content')

    <!--[if lte IE 9]>
        <script type=text/javascript>window.location.href="{{ route('browser_not_support') }}";  </script>
    <![endif]-->

    {{-- The Stylesheets --}}

    {{ HTML::style('assets/font-awesome-4.3.0/css/font-awesome.min.css') }}

    {{ Minify::stylesheet(array(
        '/assets/css/style.css',
        '/assets/css/nav.css'
    )) }}
</head>
<body>