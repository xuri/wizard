<div class="nav_left" id="nav_left">
	<ul>
		<li style="margin-top:56px;"><a href="{{ route('home') }}" class="gn-icon fa-home a">前往首页</a></li>
		<li><a href="{{ route('members.index') }}" class="gn-icon fa-users a">缘来在这</a></li>
		@if(Auth::guest()){{-- 游客 --}}
		<li><a href="{{ route('home') }}" class="gn-icon fa-download a">App下载</a></li>
		<li><a href="{{ route('signin') }}" class="gn-icon fa-sign-in a">登陆</a></li>
		<li><a href="{{ route('signup') }}" class="gn-icon fa-user a">注册</a></li>
		@elseif(! Auth::user()->is_admin){{-- 普通登录用户 --}}
		<li><a href="{{ route('account') }}" class="gn-icon fa-user a">个人信息</a></li>
		<li><a href="{{ route('home') }}" class="gn-icon fa-download a">App下载</a></li>
		<li><a href="{{ route('signout') }}" class="gn-icon fa-sign-out a">退出登录</a></li>
		@elseif(Auth::user()->is_admin) {{-- 管理员 --}}
		<li><a href="{{ route('account') }}" class="gn-icon fa-user a">个人信息</a></li>
		<li><a href="{{ route('home') }}" class="gn-icon fa-download a">App下载</a></li>
		<li><a href="{{ route('admin') }}" class="gn-icon fa-user a">控制面板</a></li>
		<li><a href="{{ route('signout') }}" class="gn-icon fa-sign-out a">退出登录</a></li>
		@endif
	</ul>
</div>

<div class="nav">

	{{-- 消息提醒列表 --}}
	<div id="nav_message">
		<h5 class="nav_message_title">暂无消息</h5>
		<ul class="nav_message_list">
		{{ HTML::image('assets/images/nav_mas_j.png', '', array('class' => 'jiao_pic')) }}
		</ul>
	</div>

	<div class="nav_main">
		{{ HTML::image('assets/images/nav_main_bg.png', '', array('id' => 'nav_main')) }}

		<p>欢迎来到聘爱网!
			@if(Auth::guest()){{-- 游客 --}}
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
{{ HTML::script('assets/js/nav.js') }}