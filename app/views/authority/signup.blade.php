@include('authority.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="login_wrap">
		<h2 id="login_title_2">让爱情起航，从心开始</h2>

		<a id="login_index" href="{{ route('home') }}">回到首页</a>
		<a id="login_tab" href="javascript:void(0);">登陆</a>
		<div id="login_main_3d">
			<div id="login_main_wrap">
				<div id="rgs_main">
					{{ Form::open(array(
						'id'           => 'login_in',
						'autocomplete' => 'off',
						'action'       => 'AuthorityController@postSignin'
						)) }}

						<div class="login_window">登录窗口</div>
						<p class="signin_error" style="text-align: center;"></p>
						<ul class="login_form">
							<li class="login_li">
								<span>账号:</span>
								<input type="text" name="email" autofocus placeholder="邮箱 / 手机号" required="required" >
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
						<a id="login_forget" href="{{ route('forgotPassword') }}">忘记密码</a>
						<a class="login_submit signin_submit">立即登录</a>
					{{ Form::close() }}

				</div>
				{{-- end login_main --}}
				<div id="login_main">

					<a href="javascript:void(0);" id="rgs_phone" class="login_button">
						<i class="fa fa-mobile"></i>
						手机注册</a>
					<a href="javascript:void(0);" id="rgs_email" class="login_button">
						<i class="fa fa-envelope-o"></i>
						邮箱注册</a>

					{{ Form::open(array('id' => 'rgs_tab1', 'autocomplete' => 'off')) }}

						<p style="text-align: center;" class="phone_error"></p>

						<ul class="login_form">
							<li class="rgs_li">
								<span>手机:</span>
								<input type="text" id="phone" name="phone" autofocus required="required" placeholder="请输入11位手机号" value="{{ Input::old('phone') }}"/>
							</li>
							<li class="rgs_li">
								<span>图形验证码:</span>
								<input type="text" class="captcha_code" name="captcha" required="required" placeholder="区分大小写">
								<span class="load_captcha">
									{{ HTML::image(URL::to('simplecaptcha'),'Captcha', array('class' => 'captcha_img')) }}
								</span>
							</li>
							<li class="rgs_li">
								<span>短信验证码:</span>
								<input class="rgs_code" type="text" name="sms_code" required="required" placeholder="短信验证码" value="{{ Input::old('sms_code') }}">
								<input type="button" class="login_button count-send" style="height: 2.4em; padding-top: 7px; color: #fff;" value="发送验证码" />
							</li>
							<li class="rgs_li">
								<span>密码:</span>
								<input type="password" name="phone_signup_password" required="required" placeholder="6-16位字母或数字的组合">
							</li>
							<li class="rgs_li">
								<span>重复密码:</span>
								<input type="password" name="phone_signup_password_confirmation" required="required" placeholder="确认您的密码">
							</li>
						</ul>
						<div class="login_clause">
							我 是： <input type="radio" name="phone_signup_sex" value="M" /> 男 生
							&nbsp; <input type="radio" name="phone_signup_sex" value="F" /> 女 生
						</div>
						<div class="login_clause">

							<input class="login_check" type="checkbox" name="checkbox" checked >
							<span>同意</span>
							<a class="rgs_agree" href="{{ route('home') }}/article/privacy.html" target="_blank">使用条款</a>
						</div>
						<a class="login_submit phone_signup_submit">立即注册</a>
					{{ Form::close() }}

					{{ Form::open(array('id' => 'rgs_tab2', 'autocomplete' => 'off')) }}

						<p style="text-align: center;" class="mail_error"></p>

						<ul class="login_form">
							<li class="rgs_li">
								<span>邮箱:</span>
								<input type="text" name="signup_email" autofocus required="required" placeholder="请输入您的常用电子邮箱" value="{{ Input::old('email') }}">
							</li>
							<li class="rgs_li">
								<span>密码:</span>
								<input type="password" name="mail_signup_password" required="required" placeholder="6-16位字母或数字的组合">
							</li>
							<li class="rgs_li">
								<span>重复密码:</span>
								<input type="password" name="mail_signup_password_confirmation" required="required" placeholder="确认你的密码">
							</li>
						</ul>
						<div class="login_clause">
							我 是： <input type="radio" name="mail_signup_sex" value="M" /> 男 生
							&nbsp; <input type="radio" name="mail_signup_sex" value="F" /> 女 生
						</div>
						<div class="login_clause">
							<input class="login_check" type="checkbox" name="checkbox" checked >
							<span>同意</span>
							<a class="rgs_agree" href="{{ route('home') }}/article/privacy.html" target="_blank">使用条款</a>
						</div>
						<a class="login_submit mail_signup_submit">立即注册</a>
					{{ Form::close(); }}

				</div>
				{{-- end rgs_main --}}
			</div>
			{{-- end login_main_wrap --}}
		</div>
		{{-- end login_main_3d --}}
	</div>

	@include('layout.copyright')
	@yield('content')

	<script type="text/javascript">
		var signin_url 	= '{{ route("signin") }}';
		var signup_url 	= '{{ route("signup") }}';
		var verifycode	= '{{ route("verifycode") }}';
		var csrf_token	= '{{ csrf_token() }}';
		var captcha_url	= '{{ route("captcha") }}';
	</script>

@include('authority.footer')
@yield('content')