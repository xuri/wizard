<div class="nav_left" id="nav_left">
	<ul>
		<li style="margin-top:56px;"><a href="#" class="gn-icon fa-home a">首页</a></li>
		<li><a href="#" class="gn-icon fa-users a">去招聘</a></li>
		<li><a href="#" class="gn-icon fa-heart a">晒幸福</a></li>
		<li><a href="#" class="gn-icon fa-user a">个人信息</a></li>
		<li><a href="#" class="gn-icon fa-download a">App下载</a></li>
	</ul>
</div>

<div class="nav">
	<div class="nav_main">
		{{ HTML::image('assets/images/nav_main_bg.png', '', array('id' => 'nav_main')) }}

		<p>欢迎来到聘爱网!
			@if(Auth::guest()){{-- 游客 --}}
			<a href="{{ route('signin') }}" id="signIn">登陆</a>
			<a href="#" id="signUp">/</a>
			<a href="{{ route('signup') }}" id="signUp">注册</a>
			@elseif(! Auth::user()->is_admin){{-- 普通登录用户 --}}
				@if(Auth::user()->nickname) }}
				<a href="{{ route('account') }}" id="signIn">{{-- Auth::user()->nickname --}}</a>
				@else
				<a href="{{ route('account') }}" id="signIn">我的资料</a>
				@endif
				<a href="#" id="signUp">/</a>
				<a href="{{ route('signout') }}" id="signUp">退出登陆</a>
			@elseif(Auth::user()->is_admin) {{-- 管理员 --}}
				@if(Auth::user()->nickname)
				<a href="{{ route('account') }}" id="signIn">{{ Auth::user()->nickname }}</a>
				@else
				<a href="{{ route('account') }}" id="signIn">我的资料</a>
				@endif
				<a href="#" id="signUp">/</a>
				<a href="{{ route('admin') }}" id="signUp">控制面板</a>
				<a href="#" id="signUp">/</a>
				<a href="{{ route('signout') }}" id="signUp">退出登陆</a>
			@endif
		</p>
	</div>
</div>
{{ HTML::script('assets/js/nav.js') }}