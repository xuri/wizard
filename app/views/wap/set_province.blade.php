<!DOCTYPE html>
<html>
<head>
    <title>聘爱 | 选择省市</title>

    @include('wap.meta')
    @yield('content')

    {{ HTML::style('assets/css/wap/public.css') }}
    {{ HTML::style('assets/css/wap/set_province.css') }}

</head>

<body>

    <h2 class="data-top">{{ HTML::image('assets/images/wap/imgs/jingling.png') }}<span>聘爱</span></h2>
    <p class="intr-title">全国首个大学生情侣招聘app</p>

    <ul class="tag-list clear">
        @foreach($provinces as $province)
        <a href="{{ route('wap.set_province', $id) }}?province_id={{ $province->id }}" class="per-tag" data-id="1">{{ $province->province }}</a>
        @endforeach
    </ul>

</body>

</html>