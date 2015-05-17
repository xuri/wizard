@include('support.header')
@yield('content')

    @include('layout.navigation')
    @yield('content')

    <div id="content" class="clear">
        <div class="con_title">{{ Lang::get('navigation.support') }}</div>
        <div class="con_img">
            <span class="line1"></span>
            <span class="line2"></span>
            {{ HTML::image('assets/images/preInfoEdit/hert.png') }}
        </div>

        <div id="wrap" class="clear">
            <div class="w_left">
                <ul class="w_nav">
                    <li><a href="{{ route('account') }}" class="a1 fa fa-tasks">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.profile') }}</a></li>
                    <li><a href="{{ route('account.sent') }}" class="a2 fa fa-heart-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.friends') }}</a></li>
                    <li><a href="{{ route('account.inbox') }}" class="a2 fa fa-star">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.followers') }}</a></li>
                    <li><a href="{{ route('account.notifications') }}" class="a3 fa fa-inbox">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.inbox') }}</a></li>
                    <li><a href="{{ route('account.posts') }}" class="a3 fa fa-flag-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.my_posts') }}</a></li>
                    <li><a href="{{ route('support.index') }}" class="a5 fa fa-life-ring active">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.support') }}</a></li>
                    <li><a href="{{ route('home') }}/article/about.html" class="a5 fa fa-bookmark-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.about') }}</a></li>
                </ul>
                @include('account.qrcode')
                @yield('content')
            </div>
            <div class="w_right">

                @if ($message = Session::get('success'))
                    <div class="callout-warning">
                        <a class="fa fa-check-circle" style="color: #ef698a"></a> {{ $message }}
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="callout-warning">
                        <a class="fa fa-times-circle" style="color: #ef698a"></a> {{ $message }}
                    </div>
                @endif
                {{ $errors->first('content', '<div class="callout-warning"><a class="fa fa-times-circle" style="color: #ef698a"></a> :message</div>') }}


                <div id="data">

                    <div class="data_top clear">
                        <span></span> {{-- Left pink border --}}
                        <p><a class="fa fa-phone"></a> {{ Lang::get('support/index.phone') }}: 15636129303</p>

                    </div>
                    <div class="content">
                        <p>{{ Lang::get('support/index.phone_text') }}</p>
                    </div>
                    <div class="data_top clear">
                        <span></span> {{-- Left pink border --}}
                        <p>
                            <a target="_blank" class="qq_chat" href="http://wpa.qq.com/msgrd?v=3&uin=523591643&site=qq&menu=yes"><i class="fa fa-qq"></i> {{ Lang::get('support/index.qq') }}: 523591643</a>
                        </p>
                    </div>
                    <div class="content">
                        <p>{{ Lang::get('support/index.qq_text') }}</p>
                    </div>
                    <div class="data_top clear">
                        <span></span> {{-- Left pink border --}}
                        <p><a class="fa fa-envelope-o"></a> {{ Lang::get('support/index.email') }}: support@pinai521.com</p>
                    </div>
                    <div class="content">
                        <p>{{ Lang::get('support/index.email_text') }}</p>
                    </div>
                    <div class="data_top clear">
                        <span></span> {{-- Left pink border --}}
                        <p><a class="fa fa-quote-left"></a> {{ Lang::get('support/index.feedback') }}</p>
                    </div>
                    <div class="content">
                        {{ Form::open() }}
                        <p>{{ Lang::get('support/index.feedback_text') }}</p>
                        <textarea class="support" name="content" rows="6"></textarea>
                        <input type="submit" value="{{ Lang::get('support/index.send') }}" class="submit_support">
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layout.copyright')
    @yield('content')

@include('support.footer')
@yield('content')