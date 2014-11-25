@include('authority.header')
@yield('content')

	<div id="login_wrap">
		<h2 id="login_title_1">让爱从心开始</h2>
		<h2 id="login_title_2">爱情起航</h2>
		{{ HTML::image('assets/images/login_heart.png', '', array('id' => 'login_heart')) }}

		{{ HTML::image('assets/images/login_ship.png', '', array('id' => 'login_ship')) }}

		<a id="login_index" href="{{ route('home') }}">回到首页</a>
		<a id="login_tab" href="javascript:;">登陆</a>
		<div id="login_main_3d">
			<div id="login_main_wrap">
				<div id="rgs_main">
					{{ Form::open(array(
							'id'     => 'login_in',
							'action' => 'AuthorityController@postSignin'
						))
					}}
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
					{{ Form::close() }}
				</div>
				{{-- end login_main --}}
				<div id="login_main" style="text-align: center;">
					<h2 style="color: #ef698a; margin-top: 20%;">请激活您的账号</h2>
					<p style="color: #ef698a; font-size: 14px; margin-top: 5%;">激活邮件已发送，请登录您的邮箱 {{ $email }} 激活账号</p>
				</div>
				{{-- end rgs_main --}}
			</div>
			{{-- end login_main_wrap --}}
		</div>
		{{-- end login_main_3d --}}
	</div>

@include('authority.footer')
@yield('content')