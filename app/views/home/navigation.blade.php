

<div class="nav">

	<div class="nav_main">
		{{ HTML::image('assets/images/nav_main_bg.png', '', array('id' => 'nav_main')) }}

		<p>欢迎来到聘爱!
			@if(Auth::guest()){{-- Guest --}}
			<a href="{{ route('signin') }}" id="signIn">登陆</a>
			<a href="#" id="signUp">/</a>
			<a href="{{ route('signup') }}" id="signUp">注册</a>
			@elseif(! Auth::user()->is_admin){{-- 普通登录用户 --}}
				@if(Auth::user()->nickname)
				<a href="{{ route('account') }}" id="signIn">{{ Auth::user()->nickname }}</a>
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