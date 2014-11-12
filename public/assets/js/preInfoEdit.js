

/**
 * canvas 元素宽高180
 * 头元素宽120 高171
 */

 // 私有(块级)作用越
+(function (){
	


	// 定义全局图片路径
	var global_pic_path = '';


	// 获取canvas元素
	var pic_wrap = getById('pre_pic_wrap'); 

	// 获取2d绘图环境
	var context = pic_wrap.getContext('2d');

	// 获取 陈列用户当前选择的图标 div
	var pic_list = getById('pre_pic_list'); 

	// 获取 陈列可选按钮div
	var btn_list = getById('pre_btn_list');

	// 获取按钮组 array
	var btns = btn_list.getElementsByTagName('div');

	// 定义背景颜色数组
	var bgcolor_arr = ['#fff', '#5d6368', '#87151c', '#cf061d', '#d94220', '#e6830a', '#f3b819', 
					   '#d0d0d1', '#8c959c', '#b5051b', '#da5037', '#efa913', '#f0bc38', '#7b7c7e', 
					   '#acb7be', '#b03c3a', '#e79273', '#e5816f', '#f9ce4e', '#f5ce65', '#000000', 
					   '#cfdce3', '#c0675c', '#f3bea1', '#f6cb73', '#f6dd91', '#ffec00', '#88b40b',
					   '#51951b', '#1e7721', '#5ca68a', '#459da1', '#2e70b4', '#fff04a', '#b0c519',
					   '#75a842', '#558d41', '#80b8a0', '#70b0b3', '#5c87c2', '#fff37e', '#dcde50',
					   '#ede223', '#bed49d', '#abc295', '#c3dcce', '#bad7d8', '#adc0df', '#2b88c9',
					   '#24236f', '#253884', '#721e69', '#d00d69', '#4c3c31', '#663d1c', '#30ade3',
					   '#493d81', '#4d5397', '#88447f', '#da5f98', '#645643', '#7a4d2a', '#78c3eb',
					   '#71639e', '#7576b0', '#a26e9e', '#e791b9', '#907765', '#9d6d39', '#cee8f7',
					   '#9a8fbb', '#a09fca', '#bf9cbe', '#f4cedf', '#beae8b', '#b88d53'
					  ];

	// 图片数量定义
	var head_pic_num;
	var eyebrows_pic_num;
	var ears_pic_num;
	var eyes_pic_num;
	var nose_pic_num;
	var mouth_pic_num;
	var hair_pic_num;
	var bgcolor_pic_num;

	// 全局记忆变量定义
	var global_tou; // 头的默认地址
	var global_er; // 耳的默认地址
	var global_mei; // 眉的默认地址
	var global_yan; // 眼的默认地址
	var global_zui; // 嘴的默认地址
	var global_bi; // 鼻的默认地址
	var global_hair; // 头发的默认地址
	var global_bgcolor; // 背景色的默认地址


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
	// 获取点击更换头像按钮
	var change_photo = getById('change_photo');
	// 获取遮罩
	var mask = getById('mask');
	// 获取捏脸插件
	var pre_content = getById('pre_content');
	// 获取关闭按钮
	var pre_close = getById('pre_close');
	// 获取选择男女窗
	var checkbg = getById('checkbg');
	// 获取男孩女孩按钮
	var g_b_btns = checkbg.getElementsByTagName('a');
	// 关闭选择男女窗的按钮
	var check_close = getById('check_close');
	
	// 点击修改头像按钮
	change_photo.onclick = function(){
		mask.style.display = checkbg.style.display = 'block';

		makeHeight(checkbg, 250); // 计算遮罩和弹窗高度的函数
	
	};

	// 点击关闭按钮 关闭的是捏脸时候的窗口
	pre_close.onclick = function(){
		mask.style.display = pre_content.style.display = 'none';
		clearImgList(); // 点击关闭按钮的时候
	};
	// 点击关闭按钮 关闭的是选择男女时候的窗口
	check_close.onclick = function(){
		mask.style.display = checkbg.style.display = 'none';
		clearImgList(); // 点击关闭按钮的时候
	};

	// 点击签到按钮
	clickon.onclick = function(){
		pillars_auto.style.width = pillars_auto.offsetWidth + 10 + 'px';
		days.innerHTML = parseInt(days.innerHTML) + 1;

		// 如果parseInt(days.innerHTML) % 50 == 0成立，说明能量柱到头了
		if(parseInt(days.innerHTML) % 50 == 0){

		}
	}

	// 点击男孩或女孩
	for(var gb = 1; gb < g_b_btns.length; gb++){
		g_b_btns[gb].onclick = function(){
			// 女生初始化 main(tou, er, mei, yan, zui, bi, hair, bgcolor)
			if(this.id == "./images/preInfoEdit/girl/"){
				global_pic_path = "./images/preInfoEdit/girl/";
				main(26, 1, 8, 11, 1, 1, 1, 0);
				// 图片数量定义
				head_pic_num = 28;
				eyebrows_pic_num = 23;
				ears_pic_num = 5;
				eyes_pic_num = 28;
				nose_pic_num = 28;
				mouth_pic_num = 27;
				hair_pic_num = 21;
				bgcolor_pic_num = 82;

				// 全局记忆变量定义
				global_tou = 26; // 头的默认地址
				global_er = 1; // 耳的默认地址
				global_mei = 8; // 眉的默认地址
				global_yan = 11; // 眼的默认地址
				global_zui = 1; // 嘴的默认地址
				global_bi = 1; // 鼻的默认地址
				global_hair = 1; // 头发的默认地址
				global_bgcolor = 0; // 背景色的默认地址
			}else{ // 男生初始化
				global_pic_path = "./images/preInfoEdit/boy/";
				main(1, 1, 1, 1, 1, 1, 1, 0);
				
				// 图片数量定义
				head_pic_num = 28;
				eyebrows_pic_num = 28;
				ears_pic_num = 6;
				eyes_pic_num = 27;
				nose_pic_num = 27;
				mouth_pic_num = 27;
				hair_pic_num = 24;
				bgcolor_pic_num = 82;

				// 全局记忆变量定义
				global_tou = 1; // 头的默认地址
				global_er = 1; // 耳的默认地址
				global_mei = 1; // 眉的默认地址
				global_yan = 1; // 眼的默认地址
				global_zui = 1; // 嘴的默认地址
				global_bi = 1; // 鼻的默认地址
				global_hair = 1; // 头发的默认地址
				global_bgcolor = 0; // 背景色的默认地址
			}
			checkbg.style.display = 'none';
			pre_content.style.display = 'block';
			createImgList('head', 28);
		};
	}

	// 获取选择学校按钮
	var check_school = getById('check_school');
	// 获取选择学校弹窗
	var vote_school = getById('vote-school');
	// 获取关闭选择学校弹窗
	var vs_pass = getById('vs-pass');

	// 点击选择学校
	check_school.onclick = function(){
		makeHeight(vote_school, 484);
		mask.style.display = vote_school.style.display = 'block';
	};

	// 点击关闭选择学校弹窗的按钮
	vs_pass.onclick = function(){
		mask.style.display = vote_school.style.display = 'none';
	};

	// 获取选择星座按钮
	var check_constellation = getById('check_constellation');
	// 获取选择星座弹窗
	var con_Popup = getById('con-Popup');
	// 获取关闭选择星座弹窗
	var con_pass = getById('con-pass');

	// 点击选择学校
	check_constellation.onclick = function(){
		makeHeight(con_Popup, 380);
		mask.style.display = con_Popup.style.display = 'block';
	};

	// 点击关闭选择学校弹窗的按钮
	con_pass.onclick = function(){
		mask.style.display = con_Popup.style.display = 'none';
	};


	// 获取选择标签按钮
	var check_tag = getById('check_tag');
	// 获取选择标签弹窗
	var tag_Popup = getById('tag-Popup');
	// 获取关闭选择标签弹窗
	var tag_pass = getById('tag-pass');

	// 点击选择标签
	check_tag.onclick = function(){
		makeHeight(tag_Popup, 380);
		mask.style.display = tag_Popup.style.display = 'block';
	};

	// 点击关闭选择标签弹窗的按钮
	tag_pass.onclick = function(){
		mask.style.display = tag_Popup.style.display = 'none';
	};

	// 标签特效
	aR=document.getElementById('tag-list-r').getElementsByTagName('li'),
    aG=document.getElementById('tag-list-g').getElementsByTagName('li'),
    aB=document.getElementById('tag-list-b').getElementsByTagName('li'),
    aY=document.getElementById('tag-list-y').getElementsByTagName('li');
    cArr(aR);
    cArr(aG);
    cArr(aB);
    cArr(aY);
	function cArr(arr){
		for(var i=0;i<arr.length;i++){
			(function(k){
				arr[k].onmousedown=function(){
					aa=this;
					fnDown(aa);
					arr[k].onmouseout=function(){
						aa=this;
						fnUp(aa);
					}
				}
				arr[k].onmouseup=function(){
					aa=this;
					fnUp(aa);
				}
				arr[k].onclick=function(){
					aa=this;
					fnY(aa);
				}
			})(i);
		}
	}
	function fnY(aa){
		var aaImg=aa.getElementsByTagName('img')[0];
		if(aaImg.offsetWidth!=0){
			aaImg.style.width=0;
			aaImg.style.height=0;
			aaImg.style.margin='14px 14px 14px 0';
		}else{
			aaImg.style.width='20px';
			aaImg.style.height='20px';
			aaImg.style.margin='4px 4px 4px 0';
		}
	}
	function fnDown(aa){
		aa.style.width='86px';
		aa.style.height='24px';
		aa.style.lineHeight='24px';
		aa.style.fontSize='12px';
		aa.style.margin='12px 2px';
	}
	function fnUp(aa){
		aa.style.width='90px';
		aa.style.height='28px';
		aa.style.lineHeight='28px';
		aa.style.fontSize='14px';
		aa.style.margin='10px auto';
	}

	// 当页面滚动的时候重新计算高度
	window.onscroll = function(){
		makeHeight(pre_content, 530); // 计算遮罩和弹窗高度的函数
		makeHeight(checkbg, 250); 
		makeHeight(vote_school, 484); // 计算选择学校遮罩和弹窗高度的函数
		makeHeight(con_Popup, 380); // 计算选择星座遮罩和弹窗高度的函数
		makeHeight(tag_Popup, 380); // 计算选择标签遮罩和弹窗高度的函数
	};

	//鼠标移入加油按钮，显示解释说明福利的框
	clickon.onmouseover = function(){
		instr.style.display = 'block';
	}
	//鼠标移出加油按钮，隐藏解释说明福利的框
	clickon.onmouseout = function(){
		instr.style.display = 'none';
	}

	

	// 计算遮罩和弹窗高度的函数
	// obj 是窗口对象
	// height 是窗口高度
	function makeHeight(obj, height){
		var top = document.documentElement.scrollTop || document.body.scrollTop;
		mask.style.top=top+'px';

		var client_height = document.documentElement.clientHeight;
		var h = height;
		var end_top = Math.ceil((client_height - h) / 2) + top;
		obj.style.top = end_top + 'px';
	}
	


	

	// 点击 类别按钮 事件委托
	btn_list.onclick = function(ev){

		var ev = ev || window.event;
		var target = ev.target || ev.srcElement;

		if(target.title){

			// 样式修改

			for(var i = 0; i < btns.length; i++){
				removeClass(btns[i], 'active');
			}
			addClass(target.parentNode, 'active');

			// 清除节点
			clearImgList();

			
			switch(target.title){
				case 'head':
					createImgList('head', head_pic_num);
				break;

				case 'eyebrows':
					createImgList('eyebrows', eyebrows_pic_num);
				break;

				case 'ears':
					createImgList('ears', ears_pic_num);
				break;

				case 'eyes':
					createImgList('eyes', eyes_pic_num);
				break;

				case 'nose':
					createImgList('nose', nose_pic_num);
				break;

				case 'mouth':
					createImgList('mouth', mouth_pic_num);
				break;

				case 'hair':
					createImgList('hair', hair_pic_num);
				break;

				case 'bgcolor':
					createImgList('bgcolor', bgcolor_pic_num);
				break;

				default:
				break
			}

		}

	}




	// 点击 陈列用户当前选择的图标列表中的按钮  事件委托
	pic_list.onclick = function(ev){

		var ev = ev || window.event;
		var target = ev.target || ev.srcElement;

		if(target.title){

			// 样式修改
			var pic_list_arr = pic_list.getElementsByTagName('div');
			for(var i = 0; i < pic_list_arr.length; i++){
				removeClass(pic_list_arr[i], 'active');
			}
			addClass(target.parentNode, 'active');


			var target_arr = target.title.split('_');
			context.clearRect(0, 0, pic_wrap.width, pic_wrap.height);
			switch(target_arr[0]){
				case 'head':
					global_tou = target_arr[1];
					main(target_arr[1], global_er, global_mei, global_yan, global_zui, global_bi, 
						 global_hair, global_bgcolor);
				break;

				case 'eyebrows':
					global_mei = target_arr[1];
					main(global_tou, global_er, target_arr[1], global_yan, global_zui, global_bi, 
						 global_hair, global_bgcolor);
				break;

				case 'ears':
					global_er = target_arr[1];
					main(global_tou, target_arr[1], global_mei, global_yan, global_zui, global_bi, 
						 global_hair, global_bgcolor);
				break;

				case 'eyes':
					global_yan = target_arr[1];
					main(global_tou, global_er, global_mei, target_arr[1], global_zui, global_bi, 
						 global_hair, global_bgcolor);
				break;

				case 'nose':
					global_bi = target_arr[1];
					main(global_tou, global_er, global_mei, global_yan, global_zui, target_arr[1], 
						 global_hair, global_bgcolor);
				break;

				case 'mouth':
					global_zui = target_arr[1];
					main(global_tou, global_er, global_mei, global_yan, target_arr[1], global_bi, 
						 global_hair, global_bgcolor);
				break;

				case 'hair':
					global_hair = target_arr[1];
					main(global_tou, global_er, global_mei, global_yan, global_zui, global_bi, 
						 target_arr[1], global_bgcolor);
				break;

				case 'bgcolor':
					global_bgcolor = target_arr[1];
					main(global_tou, global_er, global_mei, global_yan, global_zui, global_bi, 
						 global_hair, target_arr[1]);	
				break;

				default:
				break
			}

		}
	}



	/**
	 * main 主函数
	 * 思路 层的概念
	 * 先画头 第一个参数，代表头的图片地址 
	 * 再画耳 第二个参数，代表耳的图片地址 (男女有区别)
	 * 再画眉 第三个参数，代表眉的图片地址
	 * 再画眼 第四个参数，代表眼的图片地址
	 * 再画嘴 第五个参数，代表嘴的图片地址
	 * 再画鼻 第六个参数，代表鼻的图片地址
	 * 再画头发 第七个参数，代表头发的图片地址 (男女有区别)
	 *
	 */

	function main(tou, er, mei, yan, zui, bi, hair, bgcolor){
		// 画头
		var tou_img1 = new Image();
		tou_img1.src = global_pic_path + 'head/' + tou + '.png';
		tou_img1.onload = function(){

			context.drawImage(tou_img1, 30, 30, 120, 171);


			// 画耳
			var er_img1 = new Image();
			er_img1.src = global_pic_path + 'ears/' + er + '.png';
			er_img1.onload = function(){

				// 画女生的耳
				if(global_pic_path == './images/preInfoEdit/girl/'){
					context.drawImage(er_img1, 13, 104, 155, 50);
				}else{ // 画男生的耳
					context.drawImage(er_img1, 6, 104, 168, 50);
				}



				// 画眉
				var mei_img1 = new Image();
				mei_img1.src = global_pic_path + 'eyebrows/' + mei + '.png';
				mei_img1.onload = function(){

					context.drawImage(mei_img1, 48, 80, 80, 28.6);


					// 画眼
					var yan_img1 = new Image();
					yan_img1.src = global_pic_path + 'eyes/' + yan + '.png';
					yan_img1.onload = function(){

						context.drawImage(yan_img1, 48, 100, 80, 28.6);


						// 画嘴
						var zui_img1 = new Image();
						zui_img1.src = global_pic_path + 'mouth/' + zui + '.png';
						zui_img1.onload = function(){

							context.drawImage(zui_img1, 60, 152, 60, 30);



							// 画鼻
							var bi_img1 = new Image();
							bi_img1.src = global_pic_path + 'nose/' + bi + '.png';
							bi_img1.onload = function(){

								context.drawImage(bi_img1, 72, 124, 35, 30);


								// 画头发
								var hair_img1 = new Image();
								hair_img1.src = global_pic_path + 'hair/' + hair + '.png';
								hair_img1.onload = function(){

									// 画女生头发
									if(global_pic_path == './images/preInfoEdit/girl/'){
										context.drawImage(hair_img1, 0, 0, 178, 222);
									}else{ // 画男生头发
										context.drawImage(hair_img1, 0, 0, 178, 125);
									}
									
								} // ------画头发

							} // ------画鼻

						} // ------画嘴

					} // ------画眼

				} // ------画眉

			} // ------画耳

		} // ------画头

		// 画背景色
		context.fillStyle = bgcolor_arr[bgcolor];
		context.fillRect(0, 0, pic_wrap.width, pic_wrap.height);
	} // main 函数结束



	/*
	 * 创建用户当前选择图标的方法 pic_list
	 * src_str 要获取的投标集的类型
	 * num 图片数量
	 *
	 */
	function createImgList(src_str, num){

		if(src_str == 'bgcolor'){ // 如果是更换背景颜色

			
			for(var i = 0; i < bgcolor_arr.length; i++){
				var div = document.createElement('div');
				addClass(div, 'pic_img');
				if(i == 0){
					addClass(div, 'active');
				}

				div.style.background = bgcolor_arr[i];
				
				var img = document.createElement('img');
				img.style.width = '52px';
				img.style.height = '51px';
				img.title = src_str + '_' + i;
				div.appendChild(img);
				pic_list.appendChild(div);
			}

		}else{
			var start;
			// 处理可以清除的部件
			if(src_str == 'hair'){
				start = 0;
			}else{
				start = 1;
			}

			for(var i = start; i <= num; i++){
				var div = document.createElement('div');
				addClass(div, 'pic_img');
				if(i == 1){
					addClass(div, 'active');
				}

				var img = document.createElement('img');
				img.src = global_pic_path + src_str + '/all/' + i + '.png';
				img.title = src_str + '_' + i;
				

				div.appendChild(img);
				pic_list.appendChild(div);
			}
		}

		
	}

	/*
	 * 清除 用户当前选择图标的方法 pic_list
	 *
	 */
	function clearImgList(){
		var div = pic_list.getElementsByTagName('div');
		for(var i = div.length - 1; i >= 0; i--){
			pic_list.removeChild(div[i]);
		}
	}


	/*根据id选择元素
	*
	*@id 参数	字符串	元素的id名称
	*return		获取到的元素对象
	*/

	function getById(id){
		return document.getElementById(id);
	}

	/* 给元素添加class
	*
	*@obj	要给添加class的元素对象
	*@sClass	要添加的class名
	*
	*/
	function addClass(obj,sClass){
		if(obj.className==''){
			obj.className=sClass;
		}else{
			var arrClassName=obj.className.split(' ');
			if(inarr(arrClassName,sClass)==-1){
				obj.className+=(' '+sClass);
			}
			
		}
		function inarr(arr,str){
			var tmp=-1;
			for(var i=0;i<arr.length;i++){
				if(arr[i]==str){
					tmp=i;
				}
			}
			return tmp;
		}
	}
	/* 移出元素上的class
	*
	*@obj	要给添加class的元素对象
	*@sClass	要添加的class名
	*
	*/
	function removeClass(obj,sClass){
		if(obj.className!=''){
			var arrClassName=obj.className.split(' ');
			var _index=inarr(arrClassName,sClass);
			if(_index!=-1){
				arrClassName.splice(_index,1);
				obj.className=arrClassName.join(' ');
			}
		}
		function inarr(arr,str){
			var tmp=-1;
			for(var i=0;i<arr.length;i++){
				if(arr[i]==str){
					tmp=i;//如果已经有了要添加的class
				}
			}
			return tmp;
		}
	}




})();

