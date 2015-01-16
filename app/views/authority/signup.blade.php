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
					{{ Form::open(array(
						'id'           => 'login_in',
						'autocomplete' => 'off',
						'action'       => 'AuthorityController@postSignin'
						)) }}

						<div class="login_window">登录窗口</div>
						<ul class="login_form">
							<li class="login_li">
								<span>账号:</span>
								<input type="text" name="username" autofocus placeholder="邮箱 / 手机号" required="required" >
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
						<input class="login_submit" type="submit" value="立即登录">
					{{ Form::close() }}

				</div>
				{{-- end login_main --}}
				<div id="login_main">

					<a href="#" id="rgs_phone" class="login_button">
						<i class="fa fa-mobile"></i>
						手机注册</a>
					<a href="#" id="rgs_email" class="login_button">
						<i class="fa fa-envelope-o"></i>
						邮箱注册</a>
					<p style="text-align: center;">
						<strong class="phone_error"></strong>
						{{ $errors->first('phone', '<strong class="error">:message</strong>') }}

						{{ $errors->first('sms_code', '<strong class="error">:message</strong>') }}

						{{ $errors->first('email', '<strong class="error">:message</strong>') }}

						{{ $errors->first('password', '<strong class="error">:message</strong>') }}

						{{ $errors->first('sex', '<strong class="error">:message</strong>') }}
					</p>
					{{ Form::open(array('id' => 'rgs_tab1', 'autocomplete' => 'off')) }}

						<ul class="login_form">
							<li class="rgs_li">
								<span>手机:</span>
								<input type="text" id="phone" name="phone" autofocus required="required" placeholder="请输入11位手机号" value="{{ Input::old('phone') }}"/>
							</li>
							<li class="rgs_li">
								<span>验证码:</span>
								<input id="rgs_code" type="text" name="sms_code" required="required" placeholder="短信验证码" value="{{ Input::old('sms_code') }}">
								<input type="button" class="login_button count-send" style="height: 2.6em; color: #fff;" value="发送验证码" />
							</li>
							<li class="rgs_li">
								<span>密码:</span>
								<input type="password" name="password" required="required" placeholder="6-16位字母或数字的组合">
							</li>
							<li class="rgs_li">
								<span>重复密码:</span>
								<input type="password" name="password_confirmation" required="required" placeholder="确认您的密码">
							</li>
						</ul>
						<div class="login_clause">
							我 是： <input type="radio" name="sex" value="M" /> 男 生
							&nbsp; <input type="radio" name="sex" value="F" /> 女 生
						</div>
						<div class="login_clause">

							<input class="login_check" type="checkbox" name="checkbox" checked >
							<span>同意</span>
							<a class="rgs_agree" href="{{ route('home') }}/article/privacy.html" target="_blank">使用条款</a>
						</div>
						<input class="login_submit" type="submit" value="立即注册">
					{{ Form::close() }}

					{{ Form::open(array('id' => 'rgs_tab2', 'autocomplete' => 'off')) }}

						<input name="type" type="hidden" value="email" />
						<ul class="login_form">
							<li class="rgs_li">
								<span>邮箱:</span>
								<input type="text" name="email" autofocus required="required" placeholder="请输入您的常用电子邮箱" value="{{ Input::old('email') }}">
							</li>
							<li class="rgs_li">
								<span>密码:</span>
								<input type="password" name="password" required="required" placeholder="6-16位字母或数字的组合">
							</li>
							<li class="rgs_li">
								<span>重复密码:</span>
								<input type="password" name="password_confirmation" required="required" placeholder="确认你的密码">
							</li>
						</ul>
						<div class="login_clause">
							我 是： <input type="radio" name="sex" value="M" /> 男 生
							&nbsp; <input type="radio" name="sex" value="F" /> 女 生
						</div>
						<div class="login_clause">
							<input class="login_check" type="checkbox" name="checkbox" checked >
							<span>同意</span>
							<a class="rgs_agree" href="{{ route('home') }}/article/privacy.html" target="_blank">使用条款</a>
						</div>
						<input class="login_submit" type="submit" value="立即注册">
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

@include('authority.footer')
@yield('content')

<script type="text/javascript">

		$(function(){
			var times=60; //60秒后重新发送，保存到变量
			$('.count-send').click(function(){
				// this 指向
				var _this = this;
				// 获取手机号码
				var phone = $('#phone').val();

				$.post('{{ route("verifycode") }}',
			    {
			      phone : phone
			    },function(jdata){
			    	// send message success
			    	if(jdata.length != undefined){
						var that=$(_this);
						timeSend(that);
			    	}else{
			    		// send error
						$('.phone_error').html(jdata.errors.phone);
			    	}

			    });

			});

			function timeSend(that){
				if(times==0){
					that.removeAttr('disabled').val('重新发送验证码');
					times=60;
				}else{
					that.attr('disabled',true).val(times+'秒后重新发送');
					times--;
					setTimeout(function(){
					 timeSend(that);
					},1000);
				}
			}
		})

</script>