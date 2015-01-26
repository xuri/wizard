var bPhone = true,
	now = 0,
	iIndex = 2;

$('#rgs_phone').addClass('active');

$('#rgs_phone').click(function(){
	if (!bPhone) {
		$('#rgs_phone').addClass('active');
		$('#rgs_email').removeClass('active');
		$('#rgs_tab1').css('display', 'block');
		$('#rgs_tab2').css('display', 'none');
		bPhone = !bPhone;
	}
});

$('#rgs_email').click(function(){
	if (bPhone) {
		$('#rgs_email').addClass('active');
		$('#rgs_phone').removeClass('active');
		$('#rgs_tab1').css('display', 'none');
		$('#rgs_tab2').css('display', 'block');
		bPhone = !bPhone;
	}
});

$('#login_tab').click(function(){
	if(now==0){
		now=-180;
		setTimeout(function(){
			iIndex+=1;
			$('#rgs_main').css('zIndex', iIndex);
		}, 650);
		$('#login_tab').html('登录');
	}else{
		now=0;
		setTimeout(function(){
			iIndex+=1;
			$('#login_main').css('zIndex', iIndex);
		}, 650);
		$('#login_tab').html('注册');
	}
	$('#login_main_wrap').css({
		webkitTransform : function(){ return 'rotateY('+now+'deg)'; },
		oTransform : function(){ return 'rotateY('+now+'deg)'; },
		mozTransform : function(){ return 'rotateY('+now+'deg)'; },
		msTransform : function(){ return 'rotateY('+now+'deg)'; },
		transform : function(){ return 'rotateY('+now+'deg)'; }
	});
});

$(function(){
	$('.load_captcha').click(function(){
		var formData = {
			_token 			: csrf_token // CSRF token
		};
		$.ajax({
			url 	: captcha_url, // the url where we want to POST
			type 	: "POST",  // define the type of HTTP verb we want to use (POST for our form)
			data : formData
		}).done(function(data) {

			// Here we will handle errors and validation messages
			if (data.success) {
				// Handle errors
				$('.captcha_img').replaceWith(data.captcha);
			} else { // Ajax success
				// Remove post editor content
				// Ajax reload
			}
		});
	});

	// Ajax login with E-mail or phone
	$('.signin_submit').click(function(){
		var formData = {
			_token 			: csrf_token, // CSRF token
			username 		: $('input[name=email]').val(),
			password		: $('input[name=password]').val(),
		};
		$.ajax({
			url 	: signin_url, // the url where we want to POST
			type 	: "POST",  // define the type of HTTP verb we want to use (POST for our form)
			data 	: formData
		}).done(function(data) {

			// Here we will handle errors and validation messages
			if (!data.success) {
				$('.signin_error').html(data.attempt);
			} else {
				location.reload();
			}
		});
	});

	// Ajax signup with phone
	$('.phone_signup_submit').click(function(){
		var formData = {
			_token 					: csrf_token, // CSRF token
			phone 					: $('input[name=phone]').val(),
			sms_code 				: $('input[name=sms_code]').val(),
			password				: $('input[name=phone_signup_password]').val(),
			sex 					: $('input[name=phone_signup_sex]:checked').val(),
			password_confirmation	: $('input[name=phone_signup_password_confirmation]').val(),
			captcha					: $('input[name=captcha]').val(),
		};
		$.ajax({
			url 	: signup_url, // the url where we want to POST
			type 	: "POST",  // define the type of HTTP verb we want to use (POST for our form)
			data 	: formData
		}).done(function(data) {

			// Here we will handle errors and validation messages
			if (!data.success) {
				if(data.error_info){
					if(data.error_info.phone){
						var errors_phone = data.error_info.phone;
					} else {
						var errors_phone = '';
					}

					if(data.error_info.password){
						var error_password = data.error_info.password;
					} else {
						var error_password = '';
					}

					if(data.error_info.sms_code){
						var error_sms_code = data.error_info.sms_code;
					} else {
						var error_sms_code = '';
					}

					if(data.error_info.sex){
						var error_sex = data.error_info.sex;
					} else {
						var error_sex = '';
					}

					$('.phone_error').html(errors_phone + error_password + error_sms_code + error_sex);
				}
				$('.phone_error').html(data.attempt);
			} else {
				window.location.href=data.attempt;
			}
		});
	});

	// Ajax signup with E-mail
	$('.mail_signup_submit').click(function(){
		var formData = {
			_token 					: csrf_token, // CSRF token
			email 					: $('input[name=signup_email]').val(),
			password				: $('input[name=mail_signup_password]').val(),
			sex 					: $('input[name=mail_signup_sex]:checked').val(),
			password_confirmation	: $('input[name=mail_signup_password_confirmation]').val(),
			type 					: 'email'
		};
		$.ajax({
			url 	: signup_url, // the url where we want to POST
			type 	: "POST",  // define the type of HTTP verb we want to use (POST for our form)
			data 	: formData
		}).done(function(data) {

			// Here we will handle errors and validation messages
			if (!data.success) {
				if(data.error_info){
					if(data.error_info.email){
						var errors_email = data.error_info.email;
					} else {
						var errors_email = '';
					}

					if(data.error_info.password){
						var error_password = data.error_info.password;
					} else {
						var error_password = '';
					}

					if(data.error_info.sex){
						var error_sex = data.error_info.sex;
					} else {
						var error_sex = '';
					}

					$('.mail_error').html(errors_email + error_password + error_sex);
				}
				$('.mail_error').html(data.attempt);
			} else {
				window.location.href=data.attempt;
			}
		});
	});

	var times=60; // Set Count time
	$('.count-send').click(function(){
		// this point
		var _this = this;
		// Get phone number
		var phone = $('#phone').val();

		$.post(verifycode,
		{
		  phone : phone
		},function(jdata){
			// Send message success
			if(jdata.length != undefined){
				var that=$(_this);
				timeSend(that);
			}else{
				//Send error
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