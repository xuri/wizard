@include('authority.header')
@yield('content')

    @include('layout.navigation')
    @yield('content')

    <div id="login_wrap">
        <h2 id="login_title_2">{{ Lang::get('authority.slogan') }}</h2>

        <a id="login_index" href="{{ route('home') }}">{{ Lang::get('navigation.go_home') }}</a>
        <a id="login_tab" href="javascript:;">{{ Lang::get('navigation.signin') }}</a>
        <div id="login_main_3d">
            <div id="login_main_wrap">
                <div id="rgs_main">
                    <form id="login_in" action="#" method="">
                        <div class="login_window">{{ Lang::get('authority.signin_title') }}</div>
                        <ul class="login_form">
                            <li class="login_li">
                                <span>{{ Lang::get('authority.account') }}:</span>
                                <input type="email" name="username" autofocus placeholder="{{ Lang::get('authority.account_input') }}" required="required" >
                            </li>
                            <li class="login_li">
                                <span>{{ Lang::get('authority.password') }}:</span>
                                <input type="password" name="password" required="required" placeholder="{{ Lang::get('authority.password_input') }}">
                            </li>
                        </ul>
                        <div class="login_clause">
                            <input class="login_check" type="checkbox" name="checkbox" checked >
                            <span>{{ Lang::get('authority.keep_signin') }}</span>
                        </div>
                        <a id="login_forget" href="#">{{ Lang::get('authority.forgot_password') }}</a>
                        <input class="login_submit" type="submit" value="{{ Lang::get('authority.do_signin') }}">
                    </form>
                </div>{{-- end login_main --}}
                <div id="login_main" style="text-align: center;">
                    <p style="color: #ef698a; margin-top: 20%; font-size: 18px;">{{ Lang::get('authority.active_success') }}</p>
                    <a href="{{ route('members.index') }}" class="login_submit" style="line-height: 35px;">{{ Lang::get('index.get_start') }}</a>
                </div>
                {{-- rgs_main --}}
            </div>
            {{-- login_main_wrap --}}
        </div>
        {{-- login_main_3d --}}
    </div>

    @include('layout.copyright')
    @yield('content')

@include('authority.footer')
@yield('content')