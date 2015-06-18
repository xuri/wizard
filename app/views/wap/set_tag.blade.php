<!DOCTYPE html>
<html>
<head>
    <title>聘爱 | 选择标签</title>

    @include('wap.meta')
    @yield('content')

    {{ HTML::style('assets/css/wap/public.css') }}
    {{ HTML::style('assets/css/wap/tag.css') }}
</head>

<body>

    <h2 class="data-top">{{ HTML::image('assets/images/wap/imgs/jingling.png') }}<span>聘爱</span></h2>
    <p class="intr-title">全国首个大学生情侣招聘app</p>

    <ul class="tag-list clear">
        <li class="per-tag" data-id="1">咩黎</li>
        <li class="per-tag selectting" data-id="1">咩黎</li>
        <li class="per-tag" data-id="1">咩黎</li>
        <li class="per-tag" data-id="1">咩黎</li>
        <li class="per-tag" data-id="1">咩黎</li>
        <li class="per-tag" data-id="1">咩黎</li>
    </ul>

    <button class="submit-data borR-4">下一步</button>

    <script type="text/javascript">
        var set_tag_url = "{{ route('wap.set_tag', $id) }}";
    </script>
    {{ HTML::script('assets/js/wap/zepto.min.js') }}
    {{ HTML::script('assets/js/wap/tag.js') }}

    @include('wap.wechat_share')
    @yield('content')

    @include('layout.analytics')
    @yield('content')

</body>

</html>