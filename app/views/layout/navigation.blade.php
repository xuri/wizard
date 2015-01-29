<div class="nav_left" id="nav_left">
	<ul>
		<li style="margin-top:56px;"><a href="{{ route('home') }}" class="gn-icon fa-home a">前往首页</a></li>
		<li><a href="{{ route('members.index') }}" class="gn-icon fa-users a">缘来在这</a></li>
		@if(Auth::guest()){{-- Guest --}}
		<li><a href="{{ route('home') }}" class="gn-icon fa-download a">App下载</a></li>
		<li><a href="{{ route('signin') }}" class="gn-icon fa-sign-in a">登陆</a></li>
		<li><a href="{{ route('signup') }}" class="gn-icon fa-user a">注册</a></li>
		@elseif(! Auth::user()->is_admin){{-- 普通登录用户 --}}
		<li><a href="{{ route('account') }}" class="gn-icon fa-tasks a">我的资料</a></li>
		<li><a href="{{ route('account.sent') }}" class="gn-icon fa-heart-o a">我追的人</a></li>
		<li><a href="{{ route('account.inbox') }}" class="gn-icon fa-star a">追我的人</a></li>
		<li><a href="{{ route('account.notifications') }}" class="gn-icon fa-inbox a">我的来信</a></li>
		<li><a href="{{ route('forum.index') }}" class="gn-icon fa-user a">单身公寓</a></li>
		<li><a href="{{ route('account.posts') }}" class="gn-icon fa-flag-o a">我的帖子</a></li>
		<li><a href="{{ route('support.index') }}" class="gn-icon fa-support a">联系客服</a></li>
		<li><a href="{{ route('home') }}" class="gn-icon fa-download a">App下载</a></li>
		<li><a href="{{ route('signout') }}" class="gn-icon fa-sign-out a">退出登录</a></li>
		<li><a href="{{ route('home') }}" class="gn-icon fa-bookmark a">关于我们</a></li>
		@elseif(Auth::user()->is_admin) {{-- 管理员 --}}
		<li><a href="{{ route('account') }}" class="gn-icon fa-tasks a">我的资料</a></li>
		<li><a href="{{ route('account.sent') }}" class="gn-icon fa-heart-o a">我追的人</a></li>
		<li><a href="{{ route('account.inbox') }}" class="gn-icon fa-star a">追我的人</a></li>
		<li><a href="{{ route('account.notifications') }}" class="gn-icon fa-inbox a">我的来信</a></li>
		<li><a href="{{ route('forum.index') }}" class="gn-icon fa-user a">单身公寓</a></li>
		<li><a href="{{ route('account.posts') }}" class="gn-icon fa-flag-o a">我的帖子</a></li>
		<li><a href="{{ route('support.index') }}" class="gn-icon fa-support a">联系客服</a></li>
		<li><a href="{{ route('home') }}" class="gn-icon fa-download a">App下载</a></li>
		<li><a href="{{ route('admin') }}" class="gn-icon fa-dashboard a">控制面板</a></li>
		<li><a href="{{ route('signout') }}" class="gn-icon fa-sign-out a">退出登录</a></li>
		<li><a href="{{ route('home') }}" class="gn-icon fa-bookmark a">关于我们</a></li>
		@endif
	</ul>
</div>

<div class="nav">

	<div class="nav_main">
		{{ HTML::image('assets/images/nav_main_bg.png', '', array('id' => 'nav_main')) }}
		<ul>
			<li class="cl-effect-1"><a href="{{ route('home') }}" class="a">首 页</a></li>
			<li class="cl-effect-1"><a href="{{ route('members.index') }}" class="a">缘分大厅</a></li>
			<li class="cl-effect-1"><a href="{{ route('forum.index') }}" class="a">单身公寓</a></li>
			<li><a href="{{ route('account') }}" class="a">{{ HTML::image('assets/images/logo.png', '', array('width' => '40', 'style' => 'margin-top: 0.3em;')) }}</a></li>
			@if(Auth::guest()){{-- Guest --}}
			<li class="cl-effect-1"><a href="{{ route('signin') }}" class="a">登 陆</a></li>
			<li class="cl-effect-1"><a href="{{ route('signup') }}" class="a">注 册</a></li>
			@elseif(! Auth::user()->is_admin){{-- 普通登录用户 --}}
				@if(Auth::user()->nickname)
					<li class="cl-effect-1"><a href="{{ route('account') }}" class="a">{{ Auth::user()->nickname }}</a></li>
				@else
					<li class="cl-effect-1"><a href="{{ route('account') }}" class="a">我的资料</a></li>
				@endif
			<li class="cl-effect-1"><a href="{{ route('signout') }}" class="a">退出登陆</a></li>
			@elseif(Auth::user()->is_admin) {{-- 管理员 --}}
			<li class="cl-effect-1"><a href="{{ route('account') }}" class="a">我的资料</a></li>
			<li class="cl-effect-1"><a href="{{ route('signout') }}" class="a">退出登陆</a></li>
			@endif
			<li class="cl-effect-1"><a href="{{ route('home') }}/article/about.html" class="a">关于我们</a></li>
		</ul>
		<p>
			@if(Auth::guest()){{-- Guest --}}
			@elseif(Auth::user()->is_admin) {{-- Administrator --}}
				<a href="{{ route('admin') }}" id="signUp">控制面板</a>
			@endif
		</p>
	</div>
</div>
{{ HTML::script('assets/js/nav.js') }}