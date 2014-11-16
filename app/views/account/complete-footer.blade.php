</body>
{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
{{ HTML::script('assets/js/jingling.js') }}
{{ HTML::script('assets/js/color.js') }}
{{ HTML::script('assets/js/preInfoEdit.js') }}
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
</script>
</html>