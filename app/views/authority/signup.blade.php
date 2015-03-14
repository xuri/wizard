@include('authority.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="login_wrap">
		<h2 id="login_title_2">{{ Lang::get('authority.slogan') }}</h2>

		<a id="login_index" href="{{ route('home') }}">{{ Lang::get('navigation.go_home') }}</a>
		<a id="login_tab" href="javascript:void(0);">{{ Lang::get('navigation.signin') }}</a>
		<div id="login_main_3d">
			<div id="login_main_wrap">
				<div id="rgs_main">
					{{ Form::open(array(
						'id'           => 'login_in',
						'autocomplete' => 'off',
						'action'       => 'AuthorityController@postSignin'
						)) }}

						<div class="login_window">{{ Lang::get('authority.signin_title') }}</div>
						<p class="signin_error" style="text-align: center;"></p>
						<ul class="login_form">
							<li class="login_li">
								<span>{{ Lang::get('authority.account') }}:</span>
								<input type="text" name="email" autofocus placeholder="{{ Lang::get('authority.account_input') }}" required="required" >
							</li>
							<li class="login_li">
								<span>{{ Lang::get('authority.password') }}:</span>
								<input type="password" name="password" required="required" placeholder="{{ Lang::get('authority.password_input') }}">
							</li>
						</ul>
						<div class="login_clause">
							<input class="login_check" type="checkbox" name="checkbox" checked >
							<span>{{ Lang::get('authority.keep_signin') }}</span>
						</div>
						<a id="login_forget" href="{{ route('forgotPassword') }}">{{ Lang::get('authority.forgot_password') }}</a>
						<a class="login_submit signin_submit">{{ Lang::get('authority.do_signin') }}</a>
					{{ Form::close() }}

				</div>
				{{-- end login_main --}}
				<div id="login_main">

					<a href="javascript:void(0);" id="rgs_phone" class="login_button">
						<i class="fa fa-mobile"></i>
						{{ Lang::get('authority.phone_signup') }}</a>
					<a href="javascript:void(0);" id="rgs_email" class="login_button">
						<i class="fa fa-envelope-o"></i>
						{{ Lang::get('authority.email_signup') }}</a>

					{{ Form::open(array('id' => 'rgs_tab1', 'autocomplete' => 'off')) }}

						<p style="text-align: center;" class="phone_error"></p>

						<ul class="login_form">
							<li class="rgs_li">
								<span>{{ Lang::get('authority.phone') }}:</span>
								<input type="text" id="phone" name="phone" autofocus required="required" placeholder="{{ Lang::get('authority.phone_input') }}" value="{{ Input::old('phone') }}"/>
							</li>
							<li class="rgs_li">
								<span>{{ Lang::get('authority.captcha_code') }}:</span>
								<input type="text" class="captcha_code" name="captcha" required="required" placeholder="{{ Lang::get('authority.captcha_code_input') }}">
								<span class="load_captcha">
									{{ HTML::image(URL::to('simplecaptcha'),'Captcha', array('class' => 'captcha_img')) }}
								</span>
							</li>
							<li class="rgs_li">
								<span>{{ Lang::get('authority.sms') }}:</span>
								<input class="rgs_code" type="text" name="sms_code" required="required" placeholder="{{ Lang::get('authority.verify_code') }}" value="{{ Input::old('sms_code') }}">
								<input type="button" class="login_button count-send" style="height: 2.4em; padding-top: 7px; color: #fff;" value="{{ Lang::get('authority.send') }}" />
							</li>
							<li class="rgs_li">
								<span>{{ Lang::get('authority.password') }}:</span>
								<input type="password" name="phone_signup_password" required="required" placeholder="{{ Lang::get('authority.password_input') }}">
							</li>
							<li class="rgs_li">
								<span>{{ Lang::get('authority.repet_password') }}:</span>
								<input type="password" name="phone_signup_password_confirmation" required="required" placeholder="{{ Lang::get('authority.repet_password_input') }}">
							</li>
						</ul>
						<div class="login_clause">
							{{ Lang::get('authority.im') }}: <input type="radio" name="phone_signup_sex" value="M" /> {{ Lang::get('authority.sex_male') }}
							&nbsp; <input type="radio" name="phone_signup_sex" value="F" /> {{ Lang::get('authority.sex_female') }}
						</div>
						<div class="login_clause">

							<input class="login_check" type="checkbox" name="checkbox" checked >
							<span>{{ Lang::get('authority.agree') }}</span>
							<a class="rgs_agree" href="{{ route('home') }}/article/privacy.html" target="_blank">{{ Lang::get('authority.terms_of_service') }}</a>
						</div>
						<a class="login_submit phone_signup_submit">{{ Lang::get('authority.do_signup') }}</a>
					{{ Form::close() }}

					{{ Form::open(array('id' => 'rgs_tab2', 'autocomplete' => 'off')) }}

						<p style="text-align: center;" class="mail_error"></p>

						<ul class="login_form">
							<li class="rgs_li">
								<span>{{ Lang::get('authority.email') }}:</span>
								<input type="text" name="signup_email" autofocus required="required" placeholder="{{ Lang::get('authority.email_input') }}" value="{{ Input::old('email') }}">
							</li>
							<li class="rgs_li">
								<span>{{ Lang::get('authority.password') }}:</span>
								<input type="password" name="mail_signup_password" required="required" placeholder="{{ Lang::get('authority.password_input') }}">
							</li>
							<li class="rgs_li">
								<span>{{ Lang::get('authority.repet_password') }}:</span>
								<input type="password" name="mail_signup_password_confirmation" required="required" placeholder="{{ Lang::get('authority.repet_password_input') }}">
							</li>
						</ul>
						<div class="login_clause">
							{{ Lang::get('authority.im') }}： <input type="radio" name="mail_signup_sex" value="M" /> {{ Lang::get('authority.sex_male') }}
							&nbsp; <input type="radio" name="mail_signup_sex" value="F" /> {{ Lang::get('authority.sex_female') }}
						</div>
						<div class="login_clause">
							<input class="login_check" type="checkbox" name="checkbox" checked >
							<span>{{ Lang::get('authority.agree') }}</span>
							<a class="rgs_agree" href="{{ route('home') }}/article/privacy.html" target="_blank">{{ Lang::get('authority.terms_of_service') }}</a>
						</div>
						<a class="login_submit mail_signup_submit">{{ Lang::get('authority.do_signup') }}</a>
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
		var signin_url				= '{{ route("signin") }}';
		var signup_url				= '{{ route("signup") }}';
		var verifycode				= '{{ route("verifycode") }}';
		var csrf_token				= '{{ csrf_token() }}';
		var captcha_url				= '{{ route("captcha") }}';
		var lang_signin				= "{{ Lang::get('navigation.signin') }}";
		var lang_signup				= "{{ Lang::get('navigation.signup') }}";
		var lang_resent_sms			= "{{ Lang::get('authority.resent_sms') }}";
		var lang_resent_sms_time	= "{{ Lang::get('authority.resent_sms_time') }}";
	</script>

@include('authority.footer')
@yield('content')