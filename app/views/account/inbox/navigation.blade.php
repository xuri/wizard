<div class="nav_left" id="nav_left">
    <ul>
        <li style="margin-top:56px;"><a href="{{ route('home') }}" class="gn-icon fa-home a">{{ Lang::get('navigation.go_home') }}</a></li>
        <li><a href="{{ route('members.index') }}" class="gn-icon fa-users a">{{ Lang::get('navigation.discover') }}</a></li>
        @if(Auth::guest()){{-- Guest --}}
        <li><a href="{{ route('home') }}" class="gn-icon fa-download a">{{ Lang::get('navigation.download_app') }}</a></li>
        <li><a href="{{ route('signin') }}" class="gn-icon fa-sign-in a">{{ Lang::get('navigation.signin') }}</a></li>
        <li><a href="{{ route('signup') }}" class="gn-icon fa-user a">{{ Lang::get('navigation.signup') }}</a></li>
        @elseif(! Auth::user()->is_admin){{-- Users --}}
        <li><a href="{{ route('account') }}" class="gn-icon fa-tasks a">{{ Lang::get('navigation.profile') }}</a></li>
        <li><a href="{{ route('account.sent') }}" class="gn-icon fa-heart-o a">{{ Lang::get('navigation.friends') }}</a></li>
        <li><a href="{{ route('account.inbox') }}" class="gn-icon fa-star a">{{ Lang::get('navigation.followers') }}</a></li>
        <li><a href="{{ route('account.notifications') }}" class="gn-icon fa-inbox a">{{ Lang::get('navigation.inbox') }}</a></li>
        <li><a href="{{ route('forum.index') }}" class="gn-icon fa-user a">{{ Lang::get('navigation.forum') }}</a></li>
        <li><a href="{{ route('account.posts') }}" class="gn-icon fa-flag-o a">{{ Lang::get('navigation.my_posts') }}</a></li>
        <li><a href="{{ route('support.index') }}" class="gn-icon fa-support a">{{ Lang::get('navigation.support') }}</a></li>
        <li><a href="{{ route('home') }}" class="gn-icon fa-download a">{{ Lang::get('navigation.download_app') }}</a></li>
        <li><a href="{{ route('signout') }}" class="gn-icon fa-sign-out a">{{ Lang::get('navigation.signout') }}</a></li>
        <li><a href="{{ route('home') }}" class="gn-icon fa-bookmark a">{{ Lang::get('navigation.about') }}</a></li>
        @elseif(Auth::user()->is_admin) {{-- Admin --}}
        <li><a href="{{ route('account') }}" class="gn-icon fa-tasks a">{{ Lang::get('navigation.profile') }}</a></li>
        <li><a href="{{ route('account.sent') }}" class="gn-icon fa-heart-o a">{{ Lang::get('navigation.friends') }}</a></li>
        <li><a href="{{ route('account.inbox') }}" class="gn-icon fa-star a">{{ Lang::get('navigation.followers') }}</a></li>
        <li><a href="{{ route('account.notifications') }}" class="gn-icon fa-inbox a">{{ Lang::get('navigation.inbox') }}</a></li>
        <li><a href="{{ route('forum.index') }}" class="gn-icon fa-user a">{{ Lang::get('navigation.forum') }}</a></li>
        <li><a href="{{ route('account.posts') }}" class="gn-icon fa-flag-o a">{{ Lang::get('navigation.my_posts') }}</a></li>
        <li><a href="{{ route('support.index') }}" class="gn-icon fa-support a">{{ Lang::get('navigation.support') }}</a></li>
        <li><a href="{{ route('home') }}" class="gn-icon fa-download a">{{ Lang::get('navigation.download_app') }}</a></li>
        <li><a href="{{ route('admin') }}" class="gn-icon fa-dashboard a">{{ Lang::get('navigation.admin') }}</a></li>
        <li><a href="{{ route('signout') }}" class="gn-icon fa-sign-out a">{{ Lang::get('navigation.signout') }}</a></li>
        <li><a href="{{ route('home') }}" class="gn-icon fa-bookmark a">{{ Lang::get('navigation.about') }}</a></li>
        @endif
    </ul>
</div>

<div class="nav">

    @if(Auth::guest()){{-- Guest --}}
    @else
    {{-- Notifications --}}
    <div id="nav_message">
        <h5 class="nav_message_title"></h5>
        <ul class="nav_message_list">
        {{ HTML::image('assets/images/nav_mas_j.png', '', array('class' => 'jiao_pic')) }}
        </ul>
    </div>
    @endif

    <div class="nav_main">
        {{ HTML::image('assets/images/nav_main_bg.png', '', array('id' => 'nav_main')) }}
        <ul>
            <li class="cl-effect-1"><a href="{{ route('home') }}" class="a">{{ Lang::get('navigation.index') }}</a></li>
            <li class="cl-effect-1"><a href="{{ route('members.index') }}" class="a">{{ Lang::get('navigation.discover') }}</a></li>
            <li class="cl-effect-1"><a href="{{ route('forum.index') }}" class="a">{{ Lang::get('navigation.forum') }}</a></li>
            <li><a href="{{ route('account') }}" class="a">{{ HTML::image('assets/images/logo.png', '', array('width' => '40', 'style' => 'margin-top: 0.3em;')) }}</a></li>
            @if(Auth::guest()){{-- Guest --}}
            <li class="cl-effect-1"><a href="{{ route('signin') }}" class="a">{{ Lang::get('navigation.signin') }}</a></li>
            <li class="cl-effect-1"><a href="{{ route('signup') }}" class="a">{{ Lang::get('navigation.signup') }}</a></li>
            @elseif(! Auth::user()->is_admin){{-- Users --}}
            <li class="cl-effect-1"><a href="{{ route('account') }}" class="a">{{ Lang::get('navigation.profile') }}</a></li>
            <li class="cl-effect-1"><a href="{{ route('home') }}/article/about.html" class="a">{{ Lang::get('navigation.about') }}</a></li>
            <li class="cl-effect-1"><a href="{{ route('signout') }}" class="a">{{ Lang::get('navigation.signout') }}</a></li>
            @elseif(Auth::user()->is_admin) {{-- Admin --}}
            <li class="cl-effect-1"><a href="{{ route('account') }}" class="a">{{ Lang::get('navigation.profile') }}</a></li>
            <li class="cl-effect-1"><a href="{{ route('home') }}/article/about.html" class="a">{{ Lang::get('navigation.about') }}</a></li>
            <li class="cl-effect-1"><a href="{{ route('signout') }}" class="a">{{ Lang::get('navigation.signout') }}</a></li>
            @endif
        </ul>
    </div>
</div>
{{ HTML::script('assets/js/account_inbox_sent_nav.js') }}