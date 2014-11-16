

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


	// 点击男孩或女孩
	for(var gb = 1; gb < g_b_btns.length; gb++){
		g_b_btns[gb].onclick = function(){
			// 女生初始化 main(tou, er, mei, yan, zui, bi, hair, bgcolor)
			if(this.id == "../assets/images/preInfoEdit/girl/"){
				global_pic_path = "../assets/images/preInfoEdit/girl/";
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
				global_pic_path = "../assets/images/preInfoEdit/boy/";
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

	// 点击保存头像按钮
	var save_pic = getById('save_pic');
	// 获取保存头像数据的隐藏表单
	var portait = getById('portait');

	save_pic.onclick = function(){
		var dataURL = pic_wrap.toDataURL("image/png");
		portait.value = dataURL;

		mask.style.display = pre_content.style.display = 'none';
		clearImgList(); // 点击关闭按钮的时候
		//alert(portait.value);
		//
		var head_pic = getById('head_pic');
		head_pic.src = dataURL;
	}
	//alert(portait.value);


	// 获取选择学校按钮
	var check_school = getById('check_school');
	// 获取选择学校弹窗
	var vote_school = getById('vote-school');
	// 获取包含省得容器
	var provinces = getById('provinces');
	// 获取省
	var provinces_arr = provinces.getElementsByTagName('a');
	// 获取包含大学的容器
	var school_wrap = getById('school_wrap');
	// 获取存储学校的隐藏表单
	var school_str = getById('school_str');


	// 点击省份(事件委托)
	provinces.onclick = function(ev){
		var ev = ev || window.event;
		var target = ev.target || ev.srcElement;

		// 获取请求学校数据时的token值
		var to_ken= getById('province_token').value;
		// ajax求情学校数据
		$.post('http://localhost/~luxurioust/wizard/public/account/postuniversity',{
			'_token' : to_ken,
			'province' : target.innerHTML
		},function(jdata){
			for(var j = school_wrap.children.length - 1; j >= 0; j--){
				school_wrap.removeChild(school_wrap.children[j]);
			}
			for(var i = 0; i < jdata.length; i++){
				var a = document.createElement('a');
				a.innerHTML = jdata[i];

				a.onclick = function(){
					check_school.innerHTML = school_str.value = this.innerHTML;

					// 关闭窗口
					mask.style.display = vote_school.style.display = 'none';
				};

				school_wrap.appendChild(a);
			}
		});
	};
	//alert(provinces_arr.length);

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
	// 获取星座弹窗下面的li，即每个星座
	var cons_arr = con_Popup.getElementsByTagName('li');
	// 获取存储选择星座信息的隐藏表单
	var constellation = getById('constellation');
	// 获取关闭选择星座弹窗
	var con_pass = getById('con-pass');

	// 点击对应星座，写入hidden表单
	for(var i = 0; i < cons_arr.length; i++){
		(function(i){
			cons_arr[i].onclick = function(){
				constellation.value = i + 1;

				// 选择完后要关闭啊对不对
				mask.style.display = con_Popup.style.display = 'none';

				getById('con_img').src = this.children[0].src;
				check_constellation.innerHTML = this.children[1].innerHTML;
			};
		})(i);
	}

	// 点击选择星座
	check_constellation.onclick = function(){
		makeHeight(con_Popup, 380);
		mask.style.display = con_Popup.style.display = 'block';
	};

	// 点击关闭选择星座弹窗的按钮
	con_pass.onclick = function(){
		mask.style.display = con_Popup.style.display = 'none';
	};


	// 获取选择标签按钮
	var check_tag = getById('check_tag');
	// 获取选择标签弹窗
	var tag_Popup = getById('tag-Popup');
	// 获取标签（li）
	var tag_arr = tag_Popup.getElementsByTagName('li');

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
									if(global_pic_path == '../assets/images/preInfoEdit/girl/'){
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

	var aLi=document.getElementById('tag-list-r').getElementsByTagName('li');
for(var i=0;i<aLi.length;i++){
	if(i%4==0){
		aLi[i].className='red';
		aLi[i].style.marginLeft='0px';
	}else if(i%4==1){
		aLi[i].className='yellow';
	}else if(i%4==2){
		aLi[i].className='green';
	}else if(i%4==3){
		aLi[i].className='blue';
	}
}
   cArr(aLi);
   // 获取存储标签信息的隐藏表单
   var tag_str = getById('tag_str');
   // 用来存目前选择的标签的数组
   var tagArr = [];
   var admin_tagArr = [];
   // 点击完成按钮
   var tag_end = getById('tag_end');
   // 获取包含标签的td
   var tag_td = getById('tag_td');

   // 初始化的时候，读取隐藏表单的值，看看有没有选择,并付给数组tagArr  和  admin_tagArr
   admin_tagArr = tag_str.value.split(',');
   for(var u = 0; u < admin_tagArr.length; u++){
   		for(var w = 0; w < aLi.length; w++){

   			if(admin_tagArr[u] == w+ 1){
   				//alert(aLi[w].children[0].innerHTML);
   				fnY(aLi[w], w+1, aLi[w].children[0].innerHTML);
   				var s = (w+1) + '-' + aLi[w].children[0].innerHTML;
   				for(var j = tag_td.children.length - 2;j >= 0; j--){
		   			tag_td.removeChild(tag_td.children[j]);
		   		}
		   		for(var i = 0; i < tagArr.length; i++){
		   			// 动态创建标签
		   			var span = document.createElement('span');

		   			var ii = document.createElement('i');
		   			ii.innerHTML = tagArr[i].split('-')[1];

		   			var em = document.createElement('em');
		   			em.innerHTML = '×';
		   			em.onclick = function(){
		   				for(var m = 0; m < aLi.length; m++){
		   					var sr = this.parentNode.getAttribute('data-num');

		   					if(sr == aLi[m].getAttribute('data-num')){

		   						fnY(aLi[m], sr.split('-')[0], sr.split('-')[1]);
		   						tag_td.removeChild(this.parentNode);

		   						tag_str.value = admin_tagArr.toString(); // 给隐藏表单填值
		   					}


			   			}
		   			}

		   			span.appendChild(ii);
		   			span.appendChild(em);
		   			span.setAttribute("data-num", tagArr[i].toString());
		   			tag_td.insertBefore(span, tag_td.children[0]);

		   		}
   			}

		}
   }


   tag_end.onclick = function(){
   		tag_str.value = admin_tagArr.toString(); // 给隐藏表单填值

   		for(var j = tag_td.children.length - 2;j >= 0; j--){
   			tag_td.removeChild(tag_td.children[j]);
   		}
   		for(var i = 0; i < tagArr.length; i++){
   			// 动态创建标签
   			var span = document.createElement('span');

   			var ii = document.createElement('i');
   			ii.innerHTML = tagArr[i].split('-')[1];

   			var em = document.createElement('em');
   			em.innerHTML = '×';
   			em.onclick = function(){
   				for(var m = 0; m < aLi.length; m++){
   					var sr = this.parentNode.getAttribute('data-num');

   					if(sr == aLi[m].getAttribute('data-num')){

   						fnY(aLi[m], sr.split('-')[0], sr.split('-')[1],1);
   						tag_td.removeChild(this.parentNode);

   						tag_str.value = admin_tagArr.toString(); // 给隐藏表单填值
   					}


	   			}
   			}

   			span.appendChild(ii);
   			span.appendChild(em);
   			span.setAttribute("data-num", tagArr[i].toString());
   			tag_td.insertBefore(span, tag_td.children[0]);

   			// 获取选择标签弹窗
			var tag_Popup = getById('tag-Popup');
   			// 关闭弹窗
   			mask.style.display = tag_Popup.style.display = 'none';
   		}

   		//alert(tagArr + '--' + admin_tagArr);

   };



	function cArr(arr){
		for(var i=0;i<arr.length;i++){
			arr[i].index = i + 1;
			(function(k){
				arr[k].onmousedown=function(){
					aa=this;
					arr[k].onmouseout=function(){
						aa=this;
					}
				}
				arr[k].onmouseup=function(){
					aa=this;
				}
				arr[k].onclick=function(){
					aa=this;
					fnY(aa, this.index, this.children[0].innerHTML);
				}
			})(i);
		}
	}
	function fnY(aa, num, str, b){
		//alert(aa + '--' + num + '--' + str);
		var aaImg=aa.getElementsByTagName('img')[0];
		//alert(aa.getAttribute("data-num"));
		var sss = num + '-' + str;
		if(aa.getAttribute("data-num")){
			aaImg.style.width=0;
			aaImg.style.height=0;
			aaImg.style.margin='14px 14px 14px 0';
			aa.setAttribute("data-num", '');
			arrRemove(admin_tagArr, num);
			arrRemove(tagArr, sss);


		}else{
			aaImg.style.width='20px';
			aaImg.style.height='20px';
			aaImg.style.margin='4px 4px 4px 0';


			aa.setAttribute("data-num", sss);

			tagArr.push(sss);
			if(b){
				admin_tagArr.push(num);
			}

		}
	}

	// 删除数组中制定元素的方法
	function arrRemove(arr, val){
		//alert(arr);
		//alert('1'+arr + val);
		for (var i = 0; i < arr.length; i++) {
	        if (arr[i] == val){
	        	var index = arr.getArrayIndex(val);
	        	//alert('2'+index);
	        	arr.splice(index, 1);
	        }
	    }
	}
	Array.prototype.getArrayIndex = function ( value ) {
		var index = -1;
		for (var i = 0; i < this.length; i++) {
			if (this[i] == value) {
				index = i;
				break;
			}
		}
		return index;
	}


