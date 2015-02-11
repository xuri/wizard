window.onload = function(){
// 加油按钮
var clickon = getById('clickon');
// 能量柱
var pillars_auto = getById('pillars_auto');
// 能量底柱
var pillars_fixed = getById('pillars_fixed');
// 显示已经签到几天的数字
var days = pillars_auto.getElementsByTagName('span')[0];
// 解释说明点击加油福利的框框
var instr = getById('instr');

// 点击签到按钮
// 点击签到按钮
clickon.onclick = function(){

	$.post(post_renew_url,{
		'_token' : token
	},function(jdata){
		if(jdata['success']){
			pillars_auto.style.width = pillars_auto.offsetWidth + 10 + 'px';
			days.innerHTML = parseInt(days.innerHTML) + 1;
		} else {
			$('.renew_error').html('<div class="sgnin_top" style="margin:0 0 10px 0"><div><span><a href="" style="color: #297fb8;">&times; &nbsp; </a>亲，今天已经签到过啦。</span></div></div>');
		}
	});

	// 如果parseInt(days.innerHTML) % 50 == 0成立，说明能量柱到头了
	if(parseInt(days.innerHTML) % 50 == 0){

	}
}

//鼠标移入加油按钮，显示解释说明福利的框
clickon.onmouseover = function(){
	instr.style.display = 'block';
}
//鼠标移出加油按钮，隐藏解释说明福利的框
clickon.onmouseout = function(){
	instr.style.display = 'none';
}

}

