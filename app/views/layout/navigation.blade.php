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
			<li class="cl-effect-1"><a href="{{ route('account') }}" class="a">我的资料</a></li>
			@elseif(Auth::user()->is_admin) {{-- 管理员 --}}
			<li class="cl-effect-1"><a href="{{ route('account') }}" class="a">我的资料</a></li>
			@endif
			<li class="cl-effect-1"><a href="{{ route('home') }}/article/about.html" class="a">关于我们</a></li>
			<li class="cl-effect-1"><a href="{{ route('signout') }}" class="a">退出登陆</a></li>
		</ul>
		<p>
			@if(Auth::guest()){{-- Guest --}}
			@elseif(Auth::user()->is_admin) {{-- Administrator --}}
				<a href="{{ route('admin') }}" id="signUp">控制面板</a>
			@endif
		</p>
	</div>
</div>
{{-- HTML::script('assets/js/nav.js') --}}