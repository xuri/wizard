@include('authority.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="login_wrap">
		<h2 id="login_title_2">让爱情起航，从心开始</h2>

		<a id="login_index" href="{{ route('home') }}">回到首页</a>
		<a id="login_tab" href="javascript:;">登陆</a>
		<div id="login_main_3d">
			<div id="login_main_wrap">
				<div id="rgs_main">
					<form id="login_in" action="#" method="">
						<div class="login_window">登录窗口</div>
						<ul class="login_form">
							<li class="login_li">
								<span>账号:</span>
								<input type="email" name="username" autofocus placeholder="邮箱 / 手机号" required="required" >
							</li>
							<li class="login_li">
								<span>密码:</span>
								<input type="password" name="password" required="required" placeholder="您的密码">
							</li>
						</ul>
						<div class="login_clause">
							<input class="login_check" type="checkbox" name="checkbox" checked >
							<span>记住我</span>
						</div>
						<a id="login_forget" href="#">忘记密码</a>
						<input class="login_submit" type="submit" value="立即登录">
					</form>
				</div><!-- end login_main -->
				<div id="login_main" style="text-align: center;">
					<p style="color: #ef698a; margin-top: 20%; font-size: 18px;">账号激活成功</p>
					<a href="{{ route('home') }}" class="login_submit" style="line-height: 35px;">开始体验</a>
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