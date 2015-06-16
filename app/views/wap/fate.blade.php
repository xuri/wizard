<!DOCTYPE html>
<html>
<head>
    <title>聘爱 | 简历缘</title>

    @include('wap.meta')
    @yield('content')

    {{ HTML::style('assets/css/wap/swiper.min.css') }}
    {{ HTML::style('assets/css/wap/public.css') }}
    {{ HTML::style('assets/css/wap/fate.css') }}

</head>

<body>

    <header class="common-top boxSha">
        <a href="#">返回</a>
        简历缘
    </header>

    <article class="fate-con">
        <!-- 轮播图 -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach($match_users as $match_user)
                <div class="swiper-slide boxSha-all" data-ready="0">
                    <div class="pai-box">
                        <section class="pai1 pai-yes">
                            @if($match_user->portrait)
                                @if (File::exists('portrait/'.$match_user->portrait) && File::size('portrait/' . $match_user->portrait) > 0)
                                    {{ HTML::image('portrait/'.$match_user->portrait, '', array('class' => 'pi_userhead lu_left')) }}
                                @else
                                    {{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('class' => 'pi_userhead lu_left')) }}
                                @endif
                            @else
                            {{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('class' => 'pi_userhead lu_left')) }}
                            @endif
                            <div class="pai-intr">
                                <p>{{ $match_user->nickname }}
                                    @if($match_user->sex == 'M')
                                        {{ HTML::image('assets/images/sex/male_icon.png') }}
                                    @elseif($match_user->sex == 'F')
                                        {{ HTML::image('assets/images/sex/female_icon.png') }}
                                    @else
                                        {{ HTML::image('assets/images/sex/no_icon.png') }}
                                    @endif
                                </p>
                                <em>{{ Profile::where('id', $match_user->id)->first()->grade }}级</em>
                            </div>
                        </section>
                        <section class="pai2 pai-no">
                            {{ HTML::image('assets/images/wap/imgs/positive.png') }}
                            <div class="pai-txt">
                                说不定是个美女or帅哥哦！
                            </div>
                        </section>
                    </div>
                </div>
                @endforeach

            </div>
            <!-- 页码 -->
            <div class="swiper-pagination"></div>
        </div>
    </article>

    <a href="javascript:void(0);" class="rotate-btn borR-4">就翻 TA</a>

    <p class="pai-tip">今日还剩  <em class="pai-nums">3</em> 次翻牌机会</p>

    <footer class="common-foot">
        <a href="{{ route('wap.get_like_jobs', $id) }}"><p>招聘会</p></a>
        <a href="{{ route('wap.get_members_index', $id) }}"><p>淘简历</p></a>
        <a href="{{ route('wap.office', $id) }}" class="active"><p>办公室</p></a>
        <a href="{{ route('wap.get_download_app', $id) }}?type=tab"><p>下载聘爱</p></a>
    </footer>

    {{ HTML::script('assets/js/wap/zepto.min.js') }}
    {{ HTML::script('assets/js/wap/swiper.min.js') }}
    {{ HTML::script('assets/js/wap/fate.js') }}
</body>

</html>