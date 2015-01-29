<div class="nav_left" id="nav_left">
	<ul>
		<li style="margin-top:56px;"><a href="{{ route('home') }}" class="gn-icon fa-home a">前往首页</a></li>
		<li><a href="{{ route('members.index') }}" class="gn-icon fa-users a">缘来在这</a></li>
		@if(Auth::guest()){{-- 游客 --}}
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

	@if(Auth::guest()){{-- Guest --}}
	@else
	{{-- 消息提醒列表 --}}
	<div id="nav_message">
		<h5 class="nav_message_title">暂无消息</h5>
		<ul class="nav_message_list">
		{{ HTML::image('assets/images/nav_mas_j.png', '', array('class' => 'jiao_pic')) }}
		</ul>
	</div>
	@endif

	<div class="nav_main">
		{{ HTML::image('assets/images/nav_main_bg.png', '', array('id' => 'nav_main')) }}

		<ul>
			<li class="cl-effect-1"><a href="{{ route('home') }}" class="a">首 页</a></li>
			<li class="cl-effect-1"><a href="{{ route('members.index') }}" class="a">缘分大厅</a></li>
			<li class="cl-effect-1"><a href="{{ route('forum.index') }}" class="a">单身公寓</a></li>
			<li><a href="{{ route('account') }}" class="a">{{ HTML::image('assets/images/logo.png', '', array('width' => '50')) }}</a></li>
			@if(Auth::guest()){{-- Guest --}}
			<a href="{{ route('signin') }}" id="signIn">登 陆</a>
			<a href="#" id="signUp">/</a>
			<a href="{{ route('signup') }}" id="signUp">注 册</a>
			@elseif(! Auth::user()->is_admin){{-- 普通登录用户 --}}
				@if(Auth::user()->nickname)
					<li class="cl-effect-1"><a href="{{ route('account') }}" class="a">{{ Auth::user()->nickname }}</a></li>
				@else
					<li class="cl-effect-1"><a href="{{ route('account') }}" class="a">我的资料</a></li>
				@endif
			<li class="cl-effect-1"><a href="{{ route('signout') }}" class="a">退出登陆</a></li>
			@elseif(Auth::user()->is_admin) {{-- 管理员 --}}
			<li><a href="{{ route('account') }}" class="a">我的资料</a></li>
			<li><a href="{{ route('signout') }}" class="a">退出登陆</a></li>
			@endif
			<li class="cl-effect-1"><a href="{{ route('home') }}/article/about.html" class="a">关于我们</a></li>
		</ul>
		<p>
			@if(Auth::user()->is_admin) {{-- 管理员 --}}
				<a href="{{ route('admin') }}" id="signUp">控制面板</a>
				<a href="#" id="signUp">/</a>
				<a href="{{ route('signout') }}" id="signUp">退出登陆</a>
			@endif
		</p>
	</div>
</div>
{{ HTML::script('assets/js/account_inbox_sent_nav.js') }}