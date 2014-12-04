@include('authority.password.header')
@yield('content')

	<div class="fp_box">
		<h1>找回密码</h1>
		<a href="{{ route('signin') }}" class="fp_back">登陆</a>
		<a href="{{ route('home') }}" class="fp_back">回到首页</a>
		<div class="fp_passWord">
			<a href="javascript:;" class="fp_tab fp_tab2" id="tab2">邮箱找回</a>
			<a href="javascript:;" class="fp_tab fp_tab1" id="tab1">手机找回</a>
			<span class="fp_bottom" id="bottom"></span>
			<div style="width:820px;">
				<div class="fp_emile" id="emile">
					@if( Session::get('error') )
						<p style="text-align: center; margin: 20px 0 0 0;">
							{{ Session::get('error') }}

						</p>
					@elseif( Session::get('status') )
						<p style="text-align: center; margin: 20px 0 0 0;">
							{{ Session::get('status') }}

						</p>
					@endif
					{{ Form::open() }}

					<p class="fp_p">邮箱号：</p>
					<input type="text" name="email" class="fp_text"/>
					<input type="submit" value="下一步" class="fp_next" />
					{{ Form::close() }}

				</div>
				<div class="fp_phone" id="phone">
					<p style="text-align: center;  margin: 20px 0 -10px 0;">
						<strong class="phone_error"></strong>
						{{ $errors->first('phone', '<strong class="error">:message</strong>') }}

						{{ $errors->first('sms_code', '<strong class="error">:message</strong>') }}

						{{ $errors->first('email', '<strong class="error">:message</strong>') }}

						{{ $errors->first('password', '<strong class="error">:message</strong>') }}

					</p>
					{{ Form::open(array(
						'autocomplete' => 'off',
						'action'       => 'HomeController@getIndex',
						'id'           => 'fp_form'
						)) }}

					<p id="push_error"></p>
					<input type="hidden" id="forgot_password" value="forgot_password" />
					<div>
						<p class="fp_p">手机号：</p>
						<input type="text" class="fp_text" id="phone_number" name="phone" required="required" placeholder="请输入11位手机号"/>
					</div>
					<div>
						<p class="fp_p">验证码：</p><input type="text" class="fp_code" name="sms_code" required="required" value="{{ Input::old('sms_code') }}" />
						<input type="button" class="fp_button count-send" style="height: 2.6em; color: #fff;" value="获取验证码" />
					</div>
					<div class="rgs_li">
						<span>新密码:</span>
						<input type="password" name="password" required="required" placeholdr="6-16位字母或数字的组合">
					</div>
					<div class="rgs_li">
						<span>重复密码:</span>
						<input type="password" name="password_confirmation" required="required" placeholder="确认你的密码">
					</div>
					<input type="button" value="修改密码" id="submit" class="fp_next" />
					{{ Form::close() }}

				</div>
			</div>
		</div>
	</div>
</body>
<script>
	var oTab1=document.getElementById('tab1'),
		oTab2=document.getElementById('tab2'),
		oBottom=document.getElementById('bottom');
	oTab2.onclick=function(){
		oBottom.style.marginLeft=15+'px';
		sw(2);
	}
	oTab1.onclick=function(){
		oBottom.style.marginLeft=128+'px';
		sw(1);
	}
	function sw(num){
		var arr={'phone':['-430px','0','1'],'emile':['0px','1','0']};
		var oPhone=document.getElementById('phone'),
			oEmile=document.getElementById('emile');
		if(num==1){
			oEmile.style.marginLeft=arr.phone[0];
			oEmile.style.opacity=arr.phone[1];
			oPhone.style.opacity=arr.phone[2];
		}else if(num==2){
			oEmile.style.marginLeft=arr.emile[0];
			oEmile.style.opacity=arr.emile[1];
			oPhone.style.opacity=arr.emile[2];
		}
	}

	$(function(){
		{{-- Verify Code Count --}}
		var times=60; {{-- Set count time --}}
		$('.count-send').click(function(){
			{{-- this point --}}
			var _this = this;
			{{-- Get phone number --}}
			var phone = $('#phone_number').val();
			var forgot_password = $('#forgot_password').val();
			$.post('{{ route("verifycode") }}',
			{
			  phone : phone,
			  forgot_password : forgot_password
			},function(jdata){
				{{-- Send message success --}}
				if(jdata.length != undefined){
					var that=$(_this);
					timeSend(that);
				}else{
					{{-- Send error --}}
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

		{{-- Modify Ajax request --}}


		{{-- Get submit button --}}
		var pwd_submit = $("#submit");

		pwd_submit.click(function(){
			{{-- Get form value --}}
			var token_val = $("input[name=_token]").val();
			var phone_val = $('#phone_number').val();
			var sms_code_val = $("input[name=sms_code]").val();
			var password_val = $("input[name=password]").val();
			var password_conf_val = $("input[name=password_confirmation]").val();

			{{-- Send POST request --}}

			$.post("{{ route('postsmsreset') }}", {
				"_token" : token_val,
				"phone" : phone_val,
				"sms_code" : sms_code_val,
				"password" : password_val,
				"password_confirmation" : password_conf_val
			}, function(jdata){
				if(jdata.success){
					location.href = "<?php echo route('home'); ?>";

				}else{
					if(jdata.errors.hasOwnProperty("phone")){
						$('#push_error').html(jdata.errors.phone);
					}else if(jdata.errors.hasOwnProperty("sms_code")){
						$('#push_error').html(jdata.errors.sms_code);
					}else if(jdata.errors.hasOwnProperty("password_confirmation")){
						$('#push_error').html(jdata.errors.password_confirmed);
					}else if(jdata.errors.hasOwnProperty("password")){
						$('#push_error').html(jdata.errors.password);
					}
				}

			});
		});


	})

</script>
</html>