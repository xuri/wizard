@include('account.header')
@yield('content')

    @include('layout.navigation')
    @yield('content')

    <div id="content" class="clear">
        <div class="con_title">{{ Lang::get('navigation.profile') }}</div>
        <div class="con_img">
            <span class="line1"></span>
            <span class="line2"></span>
            {{ HTML::image('assets/images/preInfoEdit/hert.png') }}
        </div>

        <div id="wrap" class="clear">
            <div class="w_left">
                <ul class="w_nav">
                    <li><a href="{{ route('account') }}" class="active a1 fa fa-tasks">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.profile') }}</a></li>
                    <li><a href="{{ route('account.sent') }}" class="a2 fa fa-heart-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.friends') }}</a></li>
                    <li><a href="{{ route('account.inbox') }}" class="a2 fa fa-star">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.followers') }}</a></li>
                    <li><a href="{{ route('account.notifications') }}" class="a3 fa fa-inbox">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.inbox') }}</a></li>
                    <li><a href="{{ route('account.posts') }}" class="a3 fa fa-flag-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.my_posts') }}</a></li>
                    <li><a href="{{ route('support.index') }}" class="a5 fa fa-life-ring">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.support') }}</a></li>
                    <li><a href="{{ route('home') }}/article/about.html" class="a5 fa fa-bookmark-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.about') }}</a></li>
                </ul>
                @include('account.qrcode')
                @yield('content')
            </div>
            <div class="w_right">
                <div class="clear">
                    <div class="img">
                        @if(Auth::user()->portrait)
                            @if(File::exists('portrait/'.Auth::user()->portrait) && File::size('portrait/' . Auth::user()->portrait) > 0)
                                {{ HTML::image('portrait/'.Auth::user()->portrait) }}
                            @else
                                {{ HTML::image('assets/images/preInfoEdit/peo.png') }}
                            @endif
                        @else
                        {{ HTML::image('assets/images/preInfoEdit/peo.png') }}
                        @endif
                    </div>
                    <div class="sgnin">
                        @if ($message = Session::get('success'))
                        <div class="sgnin_top" style="margin:0 0 10px 0">
                            <div>
                                <span>
                                    <a href="javascript:;" style="color: #297fb8;">&times;</a>
                                    {{ $message }}
                                </span>
                            </div>
                        </div>
                        @endif

                        @include('account.points')
                        @yield('content')
                    </div>
                </div>


                {{-- Profile  --}}
                <div id="data">

                    <a href="{{ route('account.complete') }}" class="editor">{{ Lang::get('account/index.edit_profile') }}</a>
                    <div class="data_top clear">
                        <span></span> {{-- Left pink section --}}
                        <p>{{ Lang::get('navigation.profile') }}</p>
                    </div>
                    <table>
                        <tr>
                            <td class="data_td1">{{ Lang::get('account/index.nickname') }}:</td><td class="data_td2">
                                @if(Auth::user()->nickname)
                                    {{ Auth::user()->nickname }}
                                @else
                                    你还没有设置昵称，快去完善资料吧
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="data_td1">{{ Lang::get('account/index.sex') }}:</td><td class="data_td2">
                                @if(Auth::user()->sex == 'M')
                                    {{ HTML::image('assets/images/sex/male_icon.png', '', array('width' => '18')) }}
                                @elseif(Auth::user()->sex == 'F')
                                    {{ HTML::image('assets/images/sex/female_icon.png', '', array('width' => '18')) }}
                                @else
                                    {{ HTML::image('assets/images/sex/no_icon.png', '', array('width' => '18')) }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="data_td1">{{ Lang::get('account/index.birth') }}:</td><td class="data_td2">
                            {{ Auth::user()->born_year }}
                        </td>
                        </tr>
                        <tr>
                            <td class="data_td1">{{ Lang::get('account/index.school') }}:</td><td class="data_td2">
                            {{ Auth::user()->school }}
                        </td>
                        </tr>
                        <tr>
                            <td class="data_td1">{{ Lang::get('account/index.grade') }}:</td><td class="data_td2">{{ $profile->grade }}</td>
                        </tr>
                        <tr>
                            <td class="data_td1">{{ Lang::get('account/index.constellation') }}:</td><td class="data_td2 constellation">
                                <img src="{{ route('home') }}/assets/images/preInfoEdit/constellation/{{ $constellationIcon }}" width="30" height="30" style="margin: -6px 0 0 0px; -webkit-border-radius: 2px; -moz-border-radius: 2px; border-radius: 2px;">
                                <span style="margin-left: 0.5em;">{{ $constellationName }}</span></td>
                        </tr>
                        <tr>
                            <td class="data_td1">{{ Lang::get('account/index.tags') }}:</td>
                            <td class="data_td2 character tags">
                                @foreach($tag_str as $tag)
                                <span>{{ getTagName($tag) }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class="data_td1 vertical_c">{{ Lang::get('account/index.hobbies') }}:</td><td class="data_td2 vertical_c">
                                {{ $profile->hobbies }}
                            </td>
                        </tr>
                        <tr>
                            <td class="data_td1">{{ Lang::get('account/index.intro') }}:</td><td class="data_td2">
                                {{ $profile->self_intro }}
                            </td>
                        </tr>
                        <tr class="end_tr">
                            <td class="data_td1">{{ Lang::get('account/index.bio') }}:</td><td class="data_td2">{{ Auth::user()->bio }}</td>
                        </tr>
                        <tr class="love_problem">
                            <td class="data_td1">{{ Lang::get('account/index.question') }}:</td><td class="data_td2">{{ $profile->question }}</td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>

    @include('layout.copyright')
    @yield('content')

@include('account.footer')
@yield('content')