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