<!DOCTYPE html>
<html>
<head>
    <title>聘爱 | 选择学校</title>

    @include('wap.meta')
    @yield('content')

    {{ HTML::style('assets/css/wap/public.css') }}
    {{ HTML::style('assets/css/wap/set_university.css') }}

</head>

<body>

    <h2 class="data-top">{{ HTML::image('assets/images/wap/imgs/jingling.png') }}<span>聘爱</span></h2>
    <p class="intr-title">全国首个大学生情侣招聘app</p>

    <ul class="tag-list clear">
        @foreach($universities as $university)
        <a href="{{ route('wap.set_university', $id) }}?university_id={{ $university->id }}" class="per-tag" data-id="1">{{ $university->university }}</a>
        @endforeach
    </ul>

    <a href="{{ URL::previous() }}" class="submit-data borR-4">上一步</a>

</body>

</html>