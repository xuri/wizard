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