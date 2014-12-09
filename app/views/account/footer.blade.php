</body>
{{ HTML::script('assets/js/jingling.js') }}
{{ HTML::script('assets/js/color.js') }}
{{ HTML::script('assets/js/preInfo.js') }}
<script>

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
	clickon.onclick = function(){
		var to_ken= getById('province_token').value;

		$.post("{{ route('postrenew') }}",{
			'_token' : to_ken
		},function(jdata){
			if(jdata['success']){
				pillars_auto.style.width = pillars_auto.offsetWidth + 10 + 'px';
				days.innerHTML = parseInt(days.innerHTML) + 1;
			}
		});
	}


	//
	// 性别选择表单
	var sex_select = getById('sex_select');
	var sex_options = sex_select.getElementsByTagName('option');
	var sex_db_name = "{{ Input::old('sex', Auth::user()->sex) }}";
	//alert(sex_options[2].innerHTML);
	if(sex_db_name == 'M'){
		sex_options[1].selected = true;
		sex_options[2].selected = false;
	}else if(sex_db_name == 'F'){
		sex_options[2].selected = true;
		sex_options[1].selected = false;
	}

	//
	// 出生年选择表单
	var born_select = getById('born_select');
	var born_options = born_select.getElementsByTagName('option');
	var born_db_name = "{{ Input::old('born_year', $profile->born_year) }}";

	for(var i = 0; i < born_options.length; i++){
		if(born_options[i].innerHTML == born_db_name){
			born_options[i].selected = true;
		}
	}

	// 入学年选择表单
	var grade_select = getById('grade_select');
	var grade_options = grade_select.getElementsByTagName('option');
	var grade_db_name = "{{ Input::old('grade', $profile->grade) }}";

	for(var i = 0; i < grade_options.length; i++){
		if(grade_options[i].innerHTML == grade_db_name){
			grade_options[i].selected = true;
		}
	}

	//
	// 选择学校隐藏表单的值
	var school_str = getById('school_str').value;
	// 获取选择学校按钮
	var check_school = getById('check_school');
	//alert(school_str);
	if(school_str){
		check_school.innerHTML = school_str;
	}



	// 获取星座的隐藏表单
	var constellation = getById('constellation').value;
	if(constellation){
		getById('check_constellation').innerHTML = checkConstrllation(constellation).str;
		getById('con_img').src = "{{ route('home') }}/assets/images/preInfoEdit/constellation/" + checkConstrllation(constellation).img_str + '.png';
	}

	// 检测星座的函数
	function checkConstrllation(num){
		var str = '';
		switch(num){
			case '1' :
				img_str = 'shuipin';
				str = '水平座';
			break;
			case '2' :
				img_str = 'shuangyu';
				str = '双鱼座';
			break;
			case '3' :
				img_str = 'baiyang';
				str = '白羊座';
			break;
			case '4' :
				img_str = 'jinniu';
				str = '金牛座';
			break;
			case '5' :
				img_str = 'shuangzi';
				str = '双子座';
			break;
			case '6' :
				img_str = 'juxie';
				str = '巨蟹座';
			break;
			case '7' :
				img_str = 'shizi';
				str = '狮子座';
			break;
			case '8' :
				img_str = 'chunv';
				str = '处女座';
			break;
			case '9' :
				img_str = 'tiancheng';
				str = '天秤座';
			break;
			case '10' :
				img_str = 'tianxie';
				str = '天蝎座';
			break;
			case '11' :
				img_str = 'sheshou';
				str = '射手座';
			break;
			case '12' :
				img_str = 'mojie';
				str = '魔蝎座';
			break;
		}

		return {
			'str' : str,
			'img_str' : img_str
		};
	}
</script>
</html>