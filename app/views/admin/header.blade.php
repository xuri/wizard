<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    {{-- Favicons --}}
    <link rel="shortcut icon" href="{{ route('home') }}/assets/images/icons/favicon.png" sizes="32x32">
    {{-- For Chrome for Android: --}}
    <link rel="icon" sizes="192x192" href="{{ route('home') }}/assets/images/icons/touch-icon-192x192.png">
    {{-- For iPhone 6 Plus with @3× display: --}}
    <link rel="apple-touch-icon-precomposed" sizes="180x180" href="{{ route('home') }}/assets/images/icons/apple-touch-icon-180x180-precomposed.png">
    {{-- For iPad with @2× display running iOS ≥ 7: --}}
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ route('home') }}/assets/images/icons/apple-touch-icon-152x152-precomposed.png">
    {{-- For iPad with @2× display running iOS ≤ 6: --}}
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ route('home') }}/assets/images/icons/apple-touch-icon-144x144-precomposed.png">
    {{-- For iPhone with @2× display running iOS ≥ 7: --}}
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="{{ route('home') }}/assets/images/icons/apple-touch-icon-120x120-precomposed.png">
    {{-- For iPhone with @2× display running iOS ≤ 6: --}}
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ route('home') }}/assets/images/icons/apple-touch-icon-114x114-precomposed.png">
    {{-- For the iPad mini and the first- and second-generation iPad (@1× display) on iOS ≥ 7: --}}
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="{{ route('home') }}/assets/images/icons/apple-touch-icon-76x76-precomposed.png">
    {{-- For the iPad mini and the first- and second-generation iPad (@1× display) on iOS ≤ 6: --}}
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ route('home') }}/assets/images/icons/apple-touch-icon-72x72-precomposed.png">
    {{-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: --}}
    <link rel="apple-touch-icon-precomposed" href="{{ route('home') }}/assets/images/icons/apple-touch-icon-precomposed.png">{{-- 57×57px --}}
    <title>聘爱网 | 管理中心</title>

    <!-- Bootstrap Core CSS -->
    {{ HTML::style('assets/bootstrap-3.3.0/css/bootstrap.min.css') }}


    {{-- MetisMenu CSS --}}
    {{ HTML::style('assets/css/admin/plugins/metisMenu/metisMenu.min.css') }}


    <!-- Timeline CSS -->
    {{ HTML::style('assets/css/admin/plugins/timeline.css') }}

    <!-- Custom CSS -->
    {{ HTML::style('assets/css/admin/admin.css') }}


    <!-- Morris Charts CSS -->
    {{ HTML::style('assets/css/admin/plugins/morris.css') }}

    <!-- Custom Fonts -->
    {{ HTML::style('assets/font-awesome-4.1.0/css/font-awesome.min.css') }}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
