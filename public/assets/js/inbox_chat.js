$(document).on('open', '.remodal', function() {
		// console.log('open');
	});

	$(document).on('opened', '.remodal', function() {
		// console.log('opened');
	});

	$(document).on('close', '.remodal', function() {
		// console.log('close');
	});

	$(document).on('closed', '.remodal', function() {
		// console.log('closed');
	});

	$(document).on('confirm', '.remodal', function() {
		// console.log('confirm');
	});

	$(document).on('cancel', '.remodal', function() {
		// console.log('cancel');
	});

	// You can open or close it like this:
	// $(function () {
	//     var inst = $.remodal.lookup[$('[data-remodal-id=modal]').data('remodal')];
	//     inst.open();
	//     inst.close();
	// });

	//  Or init in this way:
	var inst = $('[data-remodal-id=modal2]').remodal();
	//  inst.open();
	//
	//
	//
	// Easemob Section
	//
	//
	// var curUserId = '{{ Auth::user()->id }}';
	var curChatUserId = '';

	// var curUserPass = '{{ Auth::user()->password }}';
	var conn = null;
	var curRoomId = null;
	var msgCardDivId = "conversation";
	var talkToDivId = "talkTo";
	var talkInputId = "talkInputId";
	var fileInputId = "fileInput";
	var bothRoster = [];
	var toRoster = [];
	var unknowContact = {};
	var maxWidth = 200;
	var listRoom = [];
	var listRoomId = [];
	var curContentTypeFlag = null;
	var groupFlagMark = "group&-";
	var groupQuering = false;
	var textSending = false;

	var startChat = false; // 标志是否连接成功

	location.hash = '#'; // 每次刷新隐藏模态框

	window.URL = window.URL || window.webkitURL || window.mozURL
			|| window.msURL;

	//定义消息编辑文本域的快捷键，enter和ctrl+enter为发送，alt+enter为换行
	//控制提交频率
	$(function() {
		$("#talkInputId").keydown(function(event) {
			var msgInput = document.getElementById(talkInputId);
				if (event.altKey && event.keyCode == 13) {
					if(startChat){
						e = $(this).html();
						$(this).html(e + '\n');
					}else{
						alert("尚未连接成功，等会吧 = = ");
						msgInput.value = '';
					}
				} else if (event.ctrlKey && event.keyCode == 13) {

					if(startChat){
						//e = $(this).val();
						//$(this).val(e + '<br>');
						event.returnValue = false;
						event.preventDefault();
						sendText();

						return false;
					}else{
						alert("尚未连接成功，等会吧 = = ");
						msgInput.value = '';
					}
				} else if (event.keyCode == 13) {
					if(startChat){
						event.returnValue = false;
						event.preventDefault();
						sendText();
						return false;
					}else{
						alert("尚未连接成功，等会吧 = = ");
						msgInput.value = '';
					}
				}


		});
	});


	//easemobwebim-sdk注册回调函数列表
	$(document).ready(function() {
		conn = new Easemob.im.Connection();
		//初始化连接
		conn.init({
			//当连接成功时的回调方法
			onOpened : function() {
				handleOpen(conn);
			},
			//当连接关闭时的回调方法
			onClosed : function() {
				handleClosed();
			},
			//收到文本消息时的回调方法
			onTextMessage : function(message) {
				//alert('shou');
				handleTextMessage(message);
			},
			//收到表情消息时的回调方法
			onEmotionMessage : function(message) {
				handleEmotion(message);
			},
			//收到图片消息时的回调方法
			onPictureMessage : function(message) {
				handlePictureMessage(message);
			},
			//收到音频消息的回调方法
			onAudioMessage : function(message) {
				handleAudioMessage(message);
			},
			onLocationMessage : function(message) {
				handleLocationMessage(message);
			},
			//收到联系人订阅请求的回调方法
			onPresence : function(message) {
				handlePresence(message);
			},
			//收到联系人信息的回调方法
			onRoster : function(message) {
				handleRoster(message);
			},
			//异常时的回调方法
			onError : function(message) {
				handleError(message);
			}
		});

		//发送文件的模态窗口
		$('#fileModal').on('hidden.bs.modal', function(e) {
			var ele = document.getElementById(fileInputId);
			ele.value = "";
			if (!window.addEventListener) {
				ele.outerHTML = ele.outerHTML;
			}
			document.getElementById("fileSend").disabled = false;
			document.getElementById("cancelfileSend").disabled = false;
		});

		$('#addFridentModal').on('hidden.bs.modal', function(e) {
			var ele = document.getElementById("addfridentId");
			ele.value = "";
			if (!window.addEventListener) {
				ele.outerHTML = ele.outerHTML;
			}
			document.getElementById("addFridend").disabled = false;
			document.getElementById("cancelAddFridend").disabled = false;
		});

		$('#delFridentModal').on('hidden.bs.modal', function(e) {
			var ele = document.getElementById("delfridentId");
			ele.value = "";
			if (!window.addEventListener) {
				ele.outerHTML = ele.outerHTML;
			}
			document.getElementById("delFridend").disabled = false;
			document.getElementById("canceldelFridend").disabled = false;
		});

		$('#confirm-block-div-modal').on('hidden.bs.modal', function(e) {

		});

		$('#option-room-div-modall').on('hidden.bs.modal', function(e) {

		});

		$('#notice-block-div').on('hidden.bs.modal', function(e) {

		});

		var user = curUserId;

		var pass = curUserPass;

		var myAppK = 'jinglingkj#pinai'//开发者APPKey
		conn.open({
			user : user,
			pwd : pass,
			appKey : myAppK
		});

		 $(function() {
			$(window).bind('beforeunload', function() {
				if (conn) {
					conn.close();

				}

				//return "看看有没有未读消息在选择是否离开吧";
			});
		 });
	});

	//处理连接时函数,主要是登录成功后对页面元素做处理
	var handleOpen = function(conn) {
		//从连接中获取到当前的登录人注册帐号名
		curUserId = conn.context.userId;
		//获取当前登录人的联系人列表
		conn.getRoster({
			success : function(roster) {
				// 页面处理
				// hiddenWaitLoginedUI();
				//showChatUI();

				//alert('已连接');
				startChat = true;
				createContactlistUL();
				var curroster;
				for ( var i in roster) {
					var ros = roster[i];
					//both为双方互为好友，要显示的联系人,from我是对方的单向好友
					if (ros.subscription == 'both'
							|| ros.subscription == 'from') {
						bothRoster.push(ros);
					} else if (ros.subscription == 'to') {
						//to表明了联系人是我的单向好友
						toRoster.push(ros);
					}
				}
				if (bothRoster.length > 0) {
					curroster = bothRoster[0];
					buildContactDiv("contractlist", bothRoster);//联系人列表页面处理
					//if (curroster)
					//	setCurrentContact(curroster.name);//页面处理将第一个联系人作为当前聊天div
				}
				conn.setPresence();
				//获取当前登录人的群组列表
				conn.listRooms({
					success : function(rooms) {
						if (rooms) {
							var listRoom = rooms;
							buildListRoomDiv("contractlist", rooms);//群组列表页面处理
						}
					},
					error : function(e) {

					}
				});
			}
		});

	};

	//连接中断时的处理，主要是对页面进行处理
	var handleClosed = function() {
		curUserId = null;
		curChatUserId = null;
		curRoomId = null;
		bothRoster = [];
		toRoster = [];
		listRoom = [];
		listRoomId = [];
		hiddenChatUI();
		clearContactUI("contactlistUL", "contactgrouplistUL",
				"momogrouplistUL", msgCardDivId);

		showLoginUI();
		groupQuering = false;
		textSending = false;
	};
	//easemobwebim-sdk中收到联系人订阅请求的处理方法，具体的type值所对应的值请参考xmpp协议规范
	var handlePresence = function(e) {
		//（发送者希望订阅接收者的出席信息），即别人申请加你为好友
		if (e.type == 'subscribe') {
			if (e.status) {
				if (e.status.indexOf('resp:true') > -1) {
					agreeAddFriend(e.from);
					return;
				}
			}
			var subscribeMessage = e.from + "请求加你为好友。\n验证消息：" + e.status;
			showNewNotice(subscribeMessage);
			$('#confirm-block-footer-confirmButton').click(function() {
				//同意好友请求
				agreeAddFriend(e.from);//e.from用户名
				//反向添加对方好友
				conn.subscribe({
					to : e.from,
					message : "[resp:true]"
				});
				$('#confirm-block-div-modal').modal('hide');
			});
			$('#confirm-block-footer-cancelButton').click(function() {
				rejectAddFriend(e.from);//拒绝加为好友
				$('#confirm-block-div-modal').modal('hide');
			});
			return;
		}
		//(发送者允许接收者接收他们的出席信息)，即别人同意你加他为好友
		if (e.type == 'subscribed') {
			toRoster.push({
				name : e.from,
				jid : e.fromJid,
				subscription : "to"
			});
			return;
		}
		//（发送者取消订阅另一个实体的出席信息）,即删除现有好友
		if (e.type == 'unsubscribe') {
			//单向删除自己的好友信息，具体使用时请结合具体业务进行处理
			delFriend(e.from);
			return;
		}
		//（订阅者的请求被拒绝或以前的订阅被取消），即对方单向的删除了好友
		if (e.type == 'unsubscribed') {
			delFriend(e.from);
			return;
		}
	};
	//easemobwebim-sdk中处理出席状态操作
	var handleRoster = function(rosterMsg) {
		for ( var i = 0; i < rosterMsg.length; i++) {
			var contact = rosterMsg[i];
			if (contact.ask && contact.ask == 'subscribe') {
				continue;
			}
			if (contact.subscription == 'to') {
				toRoster.push({
					name : contact.name,
					jid : contact.jid,
					subscription : "to"
				});
			}
			//app端删除好友后web端要同时判断状态from做删除对方的操作
			if (contact.subscription == 'from') {
				toRoster.push({
					name : contact.name,
					jid : contact.jid,
					subscription : "from"
				});
			}
			if (contact.subscription == 'both') {
				var isexist = contains(bothRoster, contact);
				if (!isexist) {
					var newcontact = document.getElementById("contactlistUL");
					var lielem = document.createElement("li");
					lielem.setAttribute("id", contact.name);
					lielem.setAttribute("class", "offline");
					lielem.setAttribute("className", "offline");
					lielem.onclick = function() {
						chooseContactDivClick(this);
					};
					var imgelem = document.createElement("img");
					imgelem.setAttribute("src", "img/head/contact_normal.png");
					lielem.appendChild(imgelem);

					var spanelem = document.createElement("span");
					spanelem.innerHTML = contact.name;
					lielem.appendChild(spanelem);

					newcontact.appendChild(lielem);
					bothRoster.push(contact);
				}
			}
			if (contact.subscription == 'remove') {
				var isexist = contains(bothRoster, contact);
				if (isexist) {
					removeFriendDomElement(contact.name);
				}
			}
		}
	};
	//异常情况下的处理方法
	// var handleError = function(e) {
	// 	if (curUserId == null) {
	// 		//hiddenWaitLoginedUI();
	// 		// 连接失败 重新连接
	// 		startChat = false;
	// 		// conn.open({
	// 		// 	user : user,
	// 		// 	pwd : pass,
	// 		// 	appKey : myAppK
	// 		// });
	// 		//showLoginUI();
	// 	} else {
	// 		var msg = e.msg;
	// 		if (e.type == EASEMOB_IM_CONNCTION_SERVER_CLOSE_ERROR) {
	// 			if (msg == "") {
	// 				startChat = false;
	// 				alert("服务器器断开连接,可能是因为在别处登录");
	// 			} else {
	// 				alert("服务器器断开连接，刷新页面试试重连吧");
	// 				startChat = false;
	// 				// conn.open({
	// 				// 	user : user,
	// 				// 	pwd : pass,
	// 				// 	appKey : myAppK
	// 				// });
	// 			}
	// 		} else {
	// 			alert(msg);
	// 		}
	// 	}
	// };
	//异常情况下的处理方法
	var handleError = function(e) {
		if (curUserId == null) {
			//hiddenWaitLoginedUI();
			// 连接失败 重新连接
			conn.open({
				user : user,
				pwd : pass,
				appKey : myAppK
			});
			//showLoginUI();
		} else {
			var msg = e.msg;
			if (e.type == EASEMOB_IM_CONNCTION_SERVER_CLOSE_ERROR) {
				if (msg == "") {
					alert("服务器器断开连接,可能是因为在别处登录");
				} else {
					chat_start = false;
					//alert("服务器器断开连接，刷新页面试试重连吧");
					if(client.browser.safari){
						location.reload();
					}


				}
			} else {
				alert(msg);
			}
		}
	};
	//判断要操作的联系人和当前联系人列表的关系
	var contains = function(roster, contact) {
		var i = roster.length;
		while (i--) {
			if (roster[i].name === contact.name) {
				return true;
			}
		}
		return false;
	};

	Array.prototype.indexOf = function(val) {
		for ( var i = 0; i < this.length; i++) {
			if (this[i].name == val.name)
				return i;
		}
		return -1;
	};
	Array.prototype.remove = function(val) {
		var index = this.indexOf(val);
		if (index > -1) {
			this.splice(index, 1);
		}
	};

	//登录系统时的操作方法
	var login = function() {
		var user = $("#username").val();
		var pass = $("#password").val();
		if (user == '' || pass == '') {
			alert("请输入用户名和密码");
			return;
		}
		//hiddenLoginUI();
		//showWaitLoginedUI();
		//根据用户名密码登录系统
		conn.open({
			user : user,
			pwd : pass,
			//连接时提供appkey
			appKey : 'jinglingkj#pinai'
		});
		return false;
	};
	var logout = function() {
		conn.close();
	};

	//设置当前显示的聊天窗口div，如果有联系人则默认选中联系人中的第一个联系人，如没有联系人则当前div为null-nouser
	var setCurrentContact = function(defaultUserId, nickname) {
		showContactChatDiv(defaultUserId,nickname);
		if (curChatUserId != null) {
			hiddenContactChatDiv(curChatUserId);
		} else {
			$('#null-nouser').css({
				"display" : "none"
			});
		}
		curChatUserId = defaultUserId;
	};

	var createContactlistUL = function() {
		var uielem = document.createElement("ul");
		$(uielem).attr({
			"id" : "contactlistUL",
			"class" : "chat03_content_ul"
		});
		var contactlist = document.getElementById("contractlist");
		contactlist.appendChild(uielem);
	};

	//构造联系人列表
	var buildContactDiv = function(contactlistDivId, roster) {
		var uielem = document.getElementById("nav_message").getElementsByClassName('nav_message_list')[0];
		var cache = {};
		for (i = 0; i < roster.length; i++) {
			if (!(roster[i].subscription == 'both' || roster[i].subscription == 'from')) {
				continue;
			}
			var jid = roster[i].jid;
			//alert(jid);
			var userName = jid.substring(jid.indexOf("_") + 1).split("@")[0];
			if (userName in cache) {
				continue;
			}
			cache[userName] = true;
			var lielem = document.createElement("li");
							//lielem.innerHTML = userName;
							lielem.style.display = 'none';
							lielem.style.cursor = 'pointer';
							lielem.style.marginTop = '6px';

							lielem.onmouseover = function(){
								this.style.backgroundColor = '#ccc';
							}
							lielem.onmouseout = function(){
								this.style.backgroundColor = '#fff';
							}

			$(lielem).attr({
				'id' : userName,
				//'class' : 'offline',
				//'className' : 'offline',
				'chat' : 'chat',
				'displayName' : userName
			});
			lielem.onclick = function() {
				chooseContactDivClick(this);
				this.style.display = 'none';

				var nav_msg_list = nav_message.getElementsByClassName('nav_message_list')[0]; // 消息列表ul
				nav_msg_list.style.display = 'none';

				location.hash = '#modal';
			};


			// 下面这段代码是创建小头像
			var courtship = document.getElementById('courtship'); // 包含我追的人用户列表的div
			var preLi = courtship.getElementsByClassName('preLi'); // 通过上面那个div找到下面的li
			//var chat_start = document.getElementById('chat_start');
			for(var j = 0; j < preLi.length; j++){
				(function (){
					//alert(j);
					var preLi_chat_start = getByClass(preLi[j],'a','chat_start'); // 获取每个li下面的点击聊天的按钮
					if(preLi_chat_start[0].getAttribute('data-id') == userName){
						// 获取头像
						var headPicSrc = preLi_chat_start[0].parentNode.parentNode.parentNode.getElementsByClassName('_headPic')[0].src;
						//alert(headPicSrc);
						var newPic = document.createElement('img');
						newPic.src = headPicSrc;
						newPic.style.width = '40px';
						newPic.style.height = '40px';
						newPic.style.position = 'absolute';
						newPic.style.top = '5px';
						newPic.style.left = '5px';
						//alert();
						lielem.appendChild(newPic);

						// 创建昵称
						var t_p = document.createElement('p');
						t_p.innerHTML = "昵称: " + preLi_chat_start[0].getAttribute('data-nickname');
						t_p.style.float = 'left';
						t_p.setAttribute('data-nickn', preLi_chat_start[0].getAttribute('data-nickname'));
						t_p.style.color = '#ab657d';
						t_p.style.marginLeft = '50px';
						lielem.appendChild(t_p);


					}
				})(j);

			}


			//var imgelem = document.createElement("img");
			//imgelem.setAttribute("src", "");
			//lielem.appendChild(imgelem);

			//var spanelem = document.createElement("span");
			//spanelem.innerHTML = userName;
			//lielem.appendChild(spanelem);

			uielem.appendChild(lielem);
		}
		//var contactlist = document.getElementById(contactlistDivId);
		//var children = contactlist.children;
		//if (children.length > 0) {
		//	contactlist.removeChild(children[0]);
		//}
		//contactlist.appendChild(uielem);
	};

	//构造群组列表
	buildListRoomDiv = function(contactlistDivId, rooms) {
		var uielem = document.getElementById("contactlistUL");
		var cache = {};
		for (i = 0; i < rooms.length; i++) {
			var roomsName = rooms[i].name;
			var roomId = rooms[i].roomId;
			listRoomId.push(roomId);
			if (roomId in cache) {
				continue;
			}
			cache[roomId] = true;
			var lielem = document.createElement("li");
			$(lielem).attr({
				'id' : groupFlagMark + roomId,
				'class' : 'offline',
				'className' : 'offline',
				'type' : 'groupchat',
				'displayName' : roomsName,
				'roomId' : roomId,
				'joined' : 'false'
			});
			$(lielem).click(function() {
				chooseContactDivClick(this);
			});
			$(lielem).append('<img src="img/head/group_normal.png"/>');
			$(lielem).append('<span>' + roomsName + '<span>');

			uielem.appendChild(lielem);
		}
		var contactlist = document.getElementById(contactlistDivId);
		var children = contactlist.children;
		if (children.length > 0) {
			contactlist.removeChild(children[0]);
		}
		contactlist.appendChild(uielem);
	};

	//选择联系人的处理
	var getContactLi = function(chatUserId) {
		return document.getElementById(chatUserId);
	};

	//构造当前聊天记录的窗口div
	var getContactChatDiv = function(chatUserId) {
		return document.getElementById(curUserId + "-" + chatUserId);
	};

	//如果当前没有某一个联系人的聊天窗口div就新建一个
	var createContactChatDiv = function(chatUserId) {
		var msgContentDivId = curUserId + "-" + chatUserId;
		var newContent = document.createElement("div");
		newContent.setAttribute("id", msgContentDivId);
		newContent.setAttribute("class", "chat01_content");
		newContent.setAttribute("className", "chat01_content");
		newContent.setAttribute("style", "display:none;");
		return newContent;
	};

	//显示当前选中联系人的聊天窗口div，并将该联系人在联系人列表中背景色置为蓝色
	var showContactChatDiv = function(chatUserId,nickname) {
		var contentDiv = getContactChatDiv(chatUserId);
		if (contentDiv == null) {
			contentDiv = createContactChatDiv(chatUserId);
			document.getElementById(msgCardDivId).appendChild(contentDiv);
		}
		contentDiv.style.display = "block";
		var contactLi = document.getElementById(chatUserId);
		if (contactLi == null) {
			return;
		}
		//contactLi.style.backgroundColor = "blue";
		var dispalyTitle = null;//聊天窗口显示当前对话人名称
		if (chatUserId.indexOf(groupFlagMark) >= 0) {
			dispalyTitle = "群组" + $(contactLi).attr('displayname') + "聊天中";
			curRoomId = $(contactLi).attr('roomid');
			$("#roomMemberImg").css('display', 'block');
		} else {
			dispalyTitle = "与 " + nickname + " 聊天中";
			$("#roomMemberImg").css('display', 'none');
		}

		document.getElementById('cont_title').innerHTML = dispalyTitle;
	};
	//对上一个联系人的聊天窗口div做隐藏处理，并将联系人列表中选择的联系人背景色置空
	var hiddenContactChatDiv = function(chatUserId) {
		var contactLi = document.getElementById(chatUserId);
		if (contactLi) {
			contactLi.style.backgroundColor = "";
		}
		var contentDiv = getContactChatDiv(chatUserId);
		if (contentDiv) {
			contentDiv.style.display = "block";

		}

	};
	//切换联系人聊天窗口div
	var chooseContactDivClick = function(li) {
		var chatUserId = li.id;
		if ($(li).attr("type") == 'groupchat'
				&& ('true' != $(li).attr("joined"))) {
			conn.join({
				roomId : $(li).attr("roomId")
			});
			$(li).attr("joined", "true");
		}
		var nickname = $(li).find('p').data('nickn');
		if (chatUserId != curChatUserId) {
			if (curChatUserId == null) {
				showContactChatDiv(chatUserId,nickname);
			} else {
				showContactChatDiv(chatUserId,nickname);
				hiddenContactChatDiv(curChatUserId);
			}
			curChatUserId = chatUserId;
		}
		//对默认的null-nouser div进行处理,走的这里说明联系人列表肯定不为空所以对默认的聊天div进行处理
		$('#null-nouser').css({
			"display" : "none"
		});
	};

	var clearContactUI = function(contactInfoDiv, contactChatDiv) {
		document.getElementById(talkToDivId).children[0].innerHTML = "";
		var contactlist = document.getElementById(contactInfoDiv);
		var children = contactlist.children;
		if (children.length > 0) {
			contactlist.removeChild(children[0]);
		}
		var chatRootDiv = document.getElementById(contactChatDiv);
		var children = chatRootDiv.children;
		for ( var i = children.length - 1; i > 1; i--) {
			chatRootDiv.removeChild(children[i]);
		}
		$('#null-nouser').css({
			"display" : "block"
		});
	};
	var emotionFlag = false;
	var showEmotionDialog = function() {
		if (emotionFlag) {
			$('#wl_faces_box').css({
				"display" : "block"
			});
			return;
		}
		;
		emotionFlag = true;
		// Easemob.im.Helper.EmotionPicData设置表情的json数组
		var sjson = Easemob.im.Helper.EmotionPicData;
		for ( var key in sjson) {
			var emotionImgContent = document.createElement("img");
			emotionImgContent.setAttribute("id", key);
			emotionImgContent.setAttribute("src", sjson[key]);
			emotionImgContent.setAttribute("style", "cursor:pointer;");
			emotionImgContent.onclick = function() {
				selectEmotionImg(this);
			};
			var emotionLi = document.createElement("li");
			emotionLi.appendChild(emotionImgContent);
			document.getElementById("emotionUL").appendChild(emotionLi);
		}
		$('#wl_faces_box').css({
			"display" : "block"
		});
	};
	//表情选择div的关闭方法
	var turnoffFaces_box = function() {
		$("#wl_faces_box").fadeOut("slow");
	};
	var selectEmotionImg = function(selImg) {
		var txt = document.getElementById(talkInputId);
		txt.value = txt.value + selImg.id;

		txt.focus();
	};
	var showSendPic = function() {
		var fileModal = getById('fileModal');
		var sendfiletype = getById('sendfiletype');
		sendfiletype.value = 'pic';
		timeMove(fileModal,{left: 0},800,'easeBoth');
	};
	var hiddenSendPic = function(){
		var fileModal = getById('fileModal');
		timeMove(fileModal,{left: -370},800,'easeBoth');
	};
	var showSendAudio = function() {
		var fileModal = getById('fileModal');
		var sendfiletype = getById('sendfiletype');
		sendfiletype.value = 'audio';
		timeMove(fileModal,{left: 0},800,'easeBoth');
	};

	var sendText = function() {
		if (textSending) {
			return;
		}
		textSending = true;

		var msgInput = document.getElementById(talkInputId);
		var msg = msgInput.value;

		if (msg == null || msg.length == 0) {
			return;
		}
		//alert(curChatUserId);
		var to = curChatUserId;
		if (to == null) {
			return;
		}
		var options = {
			to : to,
			msg : msg,
			type : "chat"
		};
		// 群组消息和个人消息的判断分支
		if (curChatUserId.indexOf(groupFlagMark) >= 0) {
			options.type = 'groupchat';
			options.to = curRoomId;
		}
		//easemobwebim-sdk发送文本消息的方法 to为发送给谁，meg为文本消息对象
		conn.sendTextMessage(options);

		//当前登录人发送的信息在聊天窗口中原样显示
		var msgtext = msg.replace(/\n/g, '<br>');
		appendMsg(curUserId, to, msgtext);
		turnoffFaces_box();
		msgInput.value = "";
		msgInput.focus();

		setTimeout(function() {
			textSending = false;
		}, 1000);
	};

	var pictype = {
		"jpg" : true,
		"gif" : true,
		"png" : true,
		"bmp" : true
	};
	var sendFile = function() {
		var type = $("#sendfiletype").val();
		if (type == 'pic') {
			sendPic();
		} else {
			sendAudio();
		}
	};
	//发送图片消息时调用方法
	var sendPic = function() {
		var to = curChatUserId;
		if (to == null) {
			return;
		}
		// Easemob.im.Helper.getFileUrl为easemobwebim-sdk获取发送文件对象的方法，fileInputId为 input 标签的id值
		var fileObj = Easemob.im.Helper.getFileUrl(fileInputId);
		if (fileObj.url == null || fileObj.url == '') {
			$('#send-file-warning')
					.html("<font color='#FF0000'>请选择发送图片</font>");
			return;
		}
		var filetype = fileObj.filetype;
		var filename = fileObj.filename;
		if (filetype in pictype) {
			document.getElementById("fileSend").disabled = true;
			document.getElementById("cancelfileSend").disabled = true;
			var opt = {
				type : 'chat',
				fileInputId : fileInputId,
				to : to,
				onFileUploadError : function(error) {
					hiddenSendPic();
					var messageContent = error.msg + ",发送图片文件失败:" + filename;
					appendMsg(curUserId, to, messageContent);
					document.getElementById("fileSend").disabled = false;
					document.getElementById("cancelfileSend").disabled = false;
				},
				onFileUploadComplete : function(data) {
					hiddenSendPic();
					var file = document.getElementById(fileInputId);
					if (file && file.files) {
						var objUrl = getObjectURL(file.files[0]);
						if (objUrl) {
							var img = document.createElement("img");
							img.src = objUrl;
							img.width = maxWidth;
						}
					}
					appendMsg(curUserId, to, {
						data : [ {
							type : 'pic',
							filename : filename,
							data : img
						} ]
					});
					document.getElementById("fileSend").disabled = false;
					document.getElementById("cancelfileSend").disabled = false;
				}
			};

			if (curChatUserId.indexOf(groupFlagMark) >= 0) {
				opt.type = 'groupchat';
				opt.to = curRoomId;
			}
			conn.sendPicture(opt);
			return;
		}
		$('#send-file-warning').html(
				"<font color='#FF0000'>不支持此图片类型" + filetype + "</font>");
	};
	var audtype = {
		"mp3" : true,
		"wma" : true,
		"wav" : true,
		"amr" : true,
		"avi" : true
	};
	//发送音频消息时调用的方法
	var sendAudio = function() {
		var to = curChatUserId;
		if (to == null) {
			return;
		}
		//利用easemobwebim-sdk提供的方法来构造一个file对象
		var fileObj = Easemob.im.Helper.getFileUrl(fileInputId);
		if (fileObj.url == null || fileObj.url == '') {
			$('#send-file-warning')
					.html("<font color='#FF0000'>请选择发送音频</font>");
			return;
		}
		var filetype = fileObj.filetype;
		var filename = fileObj.filename;
		if (filetype in audtype) {
			document.getElementById("fileSend").disabled = true;
			document.getElementById("cancelfileSend").disabled = true;
			var opt = {
				type : "chat",
				fileInputId : fileInputId,
				to : to,//发给谁
				onFileUploadError : function(error) {
					hiddenSendPic();
					var messageContent = error.msg + ",发送音频失败:" + filename;
					appendMsg(curUserId, to, messageContent);
					document.getElementById("fileSend").disabled = false;
					document.getElementById("cancelfileSend").disabled = false;
				},
				onFileUploadComplete : function(data) {
					var messageContent = "发送音频" + filename;
					hiddenSendPic();
					appendMsg(curUserId, to, messageContent);
					document.getElementById("fileSend").disabled = false;
					document.getElementById("cancelfileSend").disabled = false;
				}
			};
			//构造完opt对象后调用easemobwebim-sdk中发送音频的方法
			if (curChatUserId.indexOf(groupFlagMark) >= 0) {
				opt.type = 'groupchat';
				opt.to = curRoomId;
			}
			conn.sendAudio(opt);
			return;
		}
		$('#send-file-warning').html(
				"<font color='#FF0000'>不支持此音频类型" + filetype + "</font>");
	};
	//easemobwebim-sdk收到文本消息的回调方法的实现
	var handleTextMessage = function(message) {
		var from = message.from;//消息的发送者
		var mestype = message.type;//消息发送的类型是群组消息还是个人消息
		var messageContent = message.data;//文本消息体
		//TODO  根据消息体的to值去定位那个群组的聊天记录
		var room = message.to;
		if (mestype == 'groupchat') {
			appendMsg(message.from, message.to, messageContent, mestype);
		} else {
			appendMsg(from, from, messageContent);
		}
	};
	//easemobwebim-sdk收到表情消息的回调方法的实现，message为表情符号和文本的消息对象，文本和表情符号sdk中做了
	//统一的处理，不需要用户自己区别字符是文本还是表情符号。
	var handleEmotion = function(message) {
		var from = message.from;
		var room = message.to;
		var mestype = message.type;//消息发送的类型是群组消息还是个人消息
		if (mestype == 'groupchat') {
			appendMsg(message.from, message.to, message, mestype);
		} else {
			appendMsg(from, from, message);
		}

	};
	//easemobwebim-sdk收到图片消息的回调方法的实现
	var handlePictureMessage = function(message) {
		var filename = message.filename;//文件名称，带文件扩展名
		var from = message.from;//文件的发送者
		var mestype = message.type;//消息发送的类型是群组消息还是个人消息
		var contactDivId = from;
		if (mestype == 'groupchat') {
			contactDivId = groupFlagMark + message.to;
		}
		var options = message;
		// 图片消息下载成功后的处理逻辑
		options.onFileDownloadComplete = function(response, xhr) {
			var objectURL = window.URL.createObjectURL(response);
			img = document.createElement("img");
			img.onload = function(e) {
				img.onload = null;
				window.URL.revokeObjectURL(img.src);
			};
			img.onerror = function() {
				img.onerror = null;
				if (typeof FileReader == 'undefined') {
					img.alter = "当前浏览器不支持blob方式";
					return;
				}
				img.onerror = function() {
					img.alter = "当前浏览器不支持blob方式";
				};
				var reader = new FileReader();
				reader.onload = function(event) {
					img.src = this.result;
				};
				reader.readAsDataURL(response);
			}
			img.src = objectURL;
			var pic_real_width = options.width;

			if (pic_real_width == 0) {
				$("<img/>").attr("src", objectURL).load(function() {
					pic_real_width = this.width;
					if (pic_real_width > maxWidth) {
						img.width = maxWidth;
					} else {
						img.width = pic_real_width;
					}
					appendMsg(from, contactDivId, {
						data : [ {
							type : 'pic',
							filename : filename,
							data : img
						} ]
					});

				});
			} else {
				if (pic_real_width > maxWidth) {
					img.width = maxWidth;
				} else {
					img.width = pic_real_width;
				}
				appendMsg(from, contactDivId, {
					data : [ {
						type : 'pic',
						filename : filename,
						data : img
					} ]
				});
			}
		};
		options.onFileDownloadError = function(e) {
			appendMsg(from, contactDivId, e.msg + ",下载图片" + filename + "失败");
		};
		//easemobwebim-sdk包装的下载文件对象的统一处理方法。
		Easemob.im.Helper.download(options);
	};
	//easemobwebim-sdk收到音频消息回调方法的实现
	var handleAudioMessage = function(message) {
		var filename = message.filename;
		var filetype = message.filetype;
		var from = message.from;

		var mestype = message.type;//消息发送的类型是群组消息还是个人消息
		var contactDivId = from;
		if (mestype == 'groupchat') {
			contactDivId = groupFlagMark + message.to;
		}
		var options = message;
		options.onFileDownloadComplete = function(response, xhr) {
			//amr 不处理播放，提供下载
			var index = filename.lastIndexOf("\.");
			if (index > 0) {
				var fileType = filename.substring(index, filename.length);
				if (".amr" == fileType.toLowerCase()) {
					var spans = "不支持的音频格式:" + filename;
					var reader = new FileReader();
					reader.onload = function(event) {
						if (navigator.userAgent.indexOf("Trident") == -1) {
							spans = spans
									+ ",请<a download='"+filename+"' href='"+event.target.result+"'>&nbsp;&nbsp;下载&nbsp;&nbsp;</a>播放";
						}
						appendMsg(from, contactDivId, spans);
					}
					reader.readAsDataURL(response);
					return;
				}
			}
			var objectURL = window.URL.createObjectURL(response);
			var audio = document.createElement("audio");
			if (("src" in audio) && ("controls" in audio)) {
				audio.onload = function() {
					audio.onload = null;
					window.URL.revokeObjectURL(audio.src);
				};
				audio.onerror = function() {
					audio.onerror = null;
					appendMsg(from, contactDivId, "当前浏览器不支持播放此音频:" + filename);
				};
				audio.controls = "controls";
				audio.src = objectURL;
				appendMsg(from, contactDivId, {
					data : [ {
						type : 'audio',
						filename : filename,
						data : audio
					} ]
				});
				audio.play();
				return;
			}
		};
		options.onFileDownloadError = function(e) {
			appendMsg(from, contactDivId, e.msg + ",下载音频" + filename + "失败");
		};
		Easemob.im.Helper.download(options);
	};

	var handleLocationMessage = function(message) {
		var from = message.from;
		var addr = message.addr;
		var ele = appendMsg(from, from, addr);
		return ele;
	};

	//显示聊天记录的统一处理方法
	var appendMsg = function(who, contact, message, chattype) {
		//alert(2);
		//var contactUL = document.getElementById("contactlistUL");
		var contactUL = document.getElementById("nav_message").getElementsByClassName('nav_message_list')[0];
		if (contactUL.children.length == 0) {
			return null;
		}
		var contactDivId = contact;
		if (chattype) {
			contactDivId = groupFlagMark + contact;
		}
		var contactLi = getContactLi(contactDivId);
		if (contactLi == null) {
			if (unknowContact[contact]) {
				return;
			}
			//alert("陌生人" + who + "的消息,忽略");
			unknowContact[contact] = true;
			return null;
		}

		// 消息体 {isemotion:true;body:[{type:txt,msg:ssss}{type:emotion,msg:imgdata}]}
		var localMsg = null;
		if (typeof message == 'string') {
			localMsg = Easemob.im.Helper.parseTextMessage(message);
			localMsg = localMsg.body;
		} else {
			localMsg = message.data;
		}
		var date = new Date();
		var time = date.toLocaleTimeString();
		// var headstr = [ "<p1>" + who + "   <span></span>" + "   </p1>",
		// 		"<p2>" + time + "<b></b><br/></p2>" ];
		var headstr = [ '<div class="reflect"></div>' ];
		var header = $(headstr.join(''))

		var lineDiv = document.createElement("div");
		for ( var i = 0; i < header.length; i++) {
			var ele = header[i];
			lineDiv.appendChild(ele);
		}
		var messageContent = localMsg;
		for ( var i = 0; i < messageContent.length; i++) {
			var msg = messageContent[i];
			var type = msg.type;
			var data = msg.data;
			if (type == "emotion") {
				var eletext = "<p><img src='" + data + "'/></p>";
				var ele = $(eletext);
				for ( var j = 0; j < ele.length; j++) {
					lineDiv.appendChild(ele[j]);
				}
			} else if (type == "pic") {
				var filename = msg.filename;
				var fileele = $("<p>" + filename + "</p><br>");
				for ( var j = 0; j < fileele.length; j++) {
					lineDiv.appendChild(fileele[j]);
				}
				lineDiv.appendChild(data);
			} else if (type == 'audio') {
				var filename = msg.filename;
				var fileele = $("<p>" + filename + "</p><br>");
				for ( var j = 0; j < fileele.length; j++) {
					lineDiv.appendChild(fileele[j]);
				}
				lineDiv.appendChild(data);
			} else {
				var eletext = "<p>" + data + "</p>";
				var ele = $(eletext);
				ele[0].setAttribute("class", "chat-content-p3");
				ele[0].setAttribute("className", "chat-content-p3");
				if (curUserId == who) {
					// ele[0].style.backgroundColor = "#EBEBEB";
				}
				for ( var j = 0; j < ele.length; j++) {
					lineDiv.appendChild(ele[j]);
				}
			}
		}
		//if (curChatUserId.indexOf(contact) < 0) {
			//alert(contact);
			//alert(location.hash);
		// 只有下面这种情况下才进行消息提示
		if(!(who != contact || (who == contact && curChatUserId==contact && location.hash=="#modal"))){
			//contactLi.style.backgroundColor = "green";
			contactLi.style.display = 'block';
		}
		var msgContentDiv = getContactChatDiv(contactDivId);
		if (curUserId == who) {
			lineDiv.className = "text sent";
		} else {
			lineDiv.className = "text receive";
		}
		var create = false;
		if (msgContentDiv == null) {
			msgContentDiv = createContactChatDiv(contactDivId);
			create = true;
		}
		msgContentDiv.appendChild(lineDiv);
		if (create) {
			document.getElementById(msgCardDivId).appendChild(msgContentDiv);
		}

		//msgContentDiv.scrollTop = msgContentDiv.scrollHeight;
		document.getElementById('conversation').scrollTop = document.getElementById('conversation').scrollHeight;
		return lineDiv;

	};

	var showAddFriend = function() {
		$('#addFridentModal').modal('toggle');
		$('#addfridentId').val('好友账号');//输入好友账号
		$('#add-frident-warning').html("");
	};

	//添加输入框鼠标焦点进入时清空输入框中的内容
	var clearInputValue = function(inputId) {
		$('#' + inputId).val('');
	};

	var showDelFriend = function() {
		$('#delFridentModal').modal('toggle');
		$('#delfridentId').val('好友账号');//输入好友账号
		$('#del-frident-warning').html("");
	};

	//消息通知操作时条用的方法
	var showNewNotice = function(message) {
		$('#confirm-block-div-modal').modal('toggle');
		$('#confirm-block-footer-body').html(message);
	};

	var showWarning = function(message) {
		$('#notice-block-div').modal('toggle');
		$('#notice-block-body').html(message);
	};

	//主动添加好友操作的实现方法
	var startAddFriend = function() {
		var user = $('#addfridentId').val();
		if (user == '') {
			$('#add-frident-warning').html(
					"<font color='#FF0000'> 请输入好友名称</font>");
			return;
		}
		if (bothRoster)
			for ( var i = 0; i < bothRoster.length; i++) {
				if (bothRoster[i].name == user) {
					$('#add-frident-warning').html(
							"<font color='#FF0000'> 已是您的好友</font>");
					return;
				}
			}
		//发送添加好友请求
		var date = new Date().toLocaleTimeString();
		conn.subscribe({
			to : user,
			message : "加个好友呗-" + date
		});
		$('#addFridentModal').modal('hide');
		return;
	};

	//回调方法执行时同意添加好友操作的实现方法
	var agreeAddFriend = function(user) {
		conn.subscribed({
			to : user,
			message : "[resp:true]"
		});
	};
	//拒绝添加好友的方法处理
	var rejectAddFriend = function(user) {
		var date = new Date().toLocaleTimeString();
		conn.unsubscribed({
			to : user,
			message : date
		});
	};

	//直接调用删除操作时的调用方法
	var directDelFriend = function() {
		var user = $('#delfridentId').val();
		if (validateFriend(user, bothRoster)) {
			conn.removeRoster({
				to : user,
				success : function() {
					conn.unsubscribed({
						to : user
					});
					//删除操作成功时隐藏掉dialog
					$('#delFridentModal').modal('hide');
				},
				error : function() {
					$('#del-frident-warning').html(
							"<font color='#FF0000'>删除联系人失败!</font>");
				}
			});
		} else {
			$('#del-frident-warning').html(
					"<font color='#FF0000'>该用户不是你的好友!</font>");
		}
	};
	//判断要删除的好友是否在当前好友列表中
	var validateFriend = function(optionuser, bothRoster) {
		for ( var deluser in bothRoster) {
			if (optionuser == bothRoster[deluser].name) {
				return true;
			}
		}
		return false;
	};

	//回调方法执行时删除好友操作的方法处理
	var delFriend = function(user) {
		conn.removeRoster({
			to : user,
			groups : [ 'default' ],
			success : function() {
				conn.unsubscribed({
					to : user
				});
			}
		});
	};
	var removeFriendDomElement = function(userToDel, local) {
		var contactToDel;
		if (bothRoster.length > 0) {
			for ( var i = 0; i < bothRoster.length; i++) {
				if (bothRoster[i].name == userToDel) {
					contactToDel = bothRoster[i];
					break;
				}
			}
		}
		if (contactToDel) {
			bothRoster.remove(contactToDel);
		}
		// 隐藏删除好友窗口
		if (local) {
			$('#delFridentModal').modal('hide');
		}
		//删除通讯录
		$('#' + userToDel).remove();
		//删除聊天
		var chatDivId = curUserId + "-" + userToDel;
		var chatDiv = $('#' + chatDivId);
		if (chatDiv) {
			chatDiv.remove();
		}
		if (curChatUserId != userToDel) {
			return;
		} else {
			var displayName = '';
			//将第一个联系人作为当前聊天div
			if (bothRoster.length > 0) {
				curChatUserId = bothRoster[0].name;
				$('#' + curChatUserId).css({
					"background-color" : "blue"
				});
				var currentDiv = getContactChatDiv(curChatUserId)
						|| createContactChatDiv(curChatUserId);
				document.getElementById(msgCardDivId).appendChild(currentDiv);
				$(currentDiv).css({
					"display" : "block"
				});
				displayName = '与' + curChatUserId + '聊天中';
			} else {
				$('#null-nouser').css({
					"display" : "block"
				});
				displayName = '';
			}
			$('#talkTo').html('<a href="#">' + displayName + '</a>');
		}
	};

	//清除聊天记录
	var clearCurrentChat = function clearCurrentChat() {
		var currentDiv = getContactChatDiv(curChatUserId)
				|| createContactChatDiv(curChatUserId);
		currentDiv.innerHTML = "";
	};

	//显示成员列表
	var showRoomMember = function showRoomMember() {
		if (groupQuering) {
			return;
		}
		groupQuering = true;
		queryOccupants(curRoomId);
	};

	//根据roomId查询room成员列表
	var queryOccupants = function queryOccupants(roomId) {
		var occupants = [];
		conn.queryRoomInfo({
			roomId : roomId,
			success : function(occs) {
				if (occs) {
					for ( var i = 0; i < occs.length; i++) {
						occupants.push(occs[i]);
					}
				}
				conn.queryRoomMember({
					roomId : roomId,
					success : function(members) {
						if (members) {
							for ( var i = 0; i < members.length; i++) {
								occupants.push(members[i]);
							}
						}
						showRoomMemberList(occupants);
						groupQuering = false;
					},
					error : function() {
						groupQuering = false;
					}
				});
			},
			error : function() {
				groupQuering = false;
			}
		});
	};

	var showRoomMemberList = function showRoomMemberList(occupants) {
		var list = $('#room-member-list')[0];
		var childs = list.childNodes;
		for ( var i = childs.length - 1; i >= 0; i--) {
			list.removeChild(childs.item(i));
		}
		for (i = 0; i < occupants.length; i++) {
			var jid = occupants[i].jid;
			var userName = jid.substring(jid.indexOf("_") + 1).split("@")[0];
			var txt = $("<p></p>").text(userName);
			$('#room-member-list').append(txt);
		}
		$('#option-room-div-modal').modal('toggle');
	};

	var getObjectURL = function getObjectURL(file) {
		var url = null;
		if (window.createObjectURL != undefined) { // basic
			url = window.createObjectURL(file);
		} else if (window.URL != undefined) { // mozilla(firefox)
			url = window.URL.createObjectURL(file);
		} else if (window.webkitURL != undefined) { // webkit or chrome
			url = window.webkitURL.createObjectURL(file);
		}
		return url;
	};


	// 点击开始聊天按钮，如果连接成功才显示聊天窗口
	$('.chat_start').on('click', startModal);

	function startModal(){
		this.onclick = null;
		this.innerHTML = '连接中 ... ';
		//document.title = startChat;

		var _this = this;

		var timer = setInterval(function(){
			if(startChat){
				// 把之前的窗口隐藏
				if(curChatUserId){
					hiddenContactChatDiv(curChatUserId);
				}


				curChatUserId = _this.getAttribute('data-id');
				var nickname = _this.getAttribute('data-nickname');
				setCurrentContact(curChatUserId, nickname);

				location.hash = '#modal';
				_this.onclick = startModal;
				_this.innerHTML = '开始聊天';
				clearInterval(timer);

				$('.chat01_content').hide();
				var show_id = curUserId + "-" + $(_this).data('id');
				//alert(show_id);
				$('#' + show_id).show();

				// 把消息提醒也关掉
				var uielem = document.getElementById("nav_message").getElementsByClassName('nav_message_list')[0];
				var li_nav_msg_arr = uielem.getElementsByTagName('li');
				for(var i = 0; i < li_nav_msg_arr.length; i++){
					if(li_nav_msg_arr[i].id == curChatUserId){
						li_nav_msg_arr[i].style.display = 'none';
					}
				}
			}
		}, 30);

	}


	// 检测消息
	var c_uielem = null;
	var c_title = '';
	setInterval(function(){
		c_uielem = document.getElementById("nav_message").getElementsByClassName('nav_message_list')[0];
		c_title = document.getElementById("nav_message").getElementsByClassName('nav_message_title')[0];
		for(var i = 0; i < c_uielem.children.length; i++){
			//(function(i){
				// document.title = (c_uielem.children[i].style.display == 'block');
				if(c_uielem.children[i].style.display == 'block'){
					c_title.id = 'y';
					c_title.innerHTML = '【 有消息啦！】';
					//addClass(c_title,'fg'); // 添加文字发光样式

					break;
				}else{
					c_title.innerHTML = '';
					c_title.id = 'n';
					//removeClass(c_title,'fg'); // 移出文字发光样式

				}
			//})(i);

		}
	},1000);
	var start_title = document.title;
	var btnonoff = true;
	setInterval(function(){
		if(c_title.id == 'y'){
			if(btnonoff){
				document.title = '有消息啦！';
				c_title.innerHTML = '【 有消息啦！】';
				btnonoff = false;
			}else{
				document.title = start_title;
				c_title.innerHTML = '';
				btnonoff = true;
			}
		}else{
			document.title = start_title;
		}
	}, 800);



	var client = function(){

		// 呈现引擎
		var engine = {
			ie: 0,
			gecko: 0,
			webkit: 0,
			khtml: 0,
			opera: 0,

			// 完整的版本号
			ver: null
		};

		// 浏览器
		var browser = {

			// 主要浏览器
			ie: 0,
			firefox: 0,
			safari: 0,
			konq: 0,
			opera: 0,
			chrome: 0,

			// 具体的版本号
			ver: null
		}

		// 平台、设备和操作系统
		var system = {
			win: false,
			mac: false,
			x11: false,

			// 移动设备
			iphone: false,
			ipod: false,
			ipad:false,
			ios: false,
			android: false,
			nokiaN: false,
			winMobile: false,

			// 游戏系统
			wii: false,
			ps: false
		}

		// 检测呈现引擎和浏览器
		var ua = navigator.userAgent;
		if(window.opera){
			engine.ver = browser.ver = window.pera.version();
			engine.opera = browser.opera = parseFloat(engine.ver);
		}else if(/AppleWebKit\/(\S+)/.test(ua)){
			engine.ver = RegExp["$1"];
			engine.webkit = parseFloat(engine.ver);

			// 确定是chrome还是Safari
			if(/Chrome\/(\S+)/.test(ua)){
				browser.ver = RegExp["$1"];
				browser.chrome = parseFloat(browser.ver);
			}else if(/Version\/(\S+)/.test(ua)){
				browser.ver = RegExp["$1"];
				browser.safari = parseFloat(browser.ver);
			}else{
				// 近似的确定版本号
				var safariVersion = 1;
				if(engine.webkit < 100){
					safariVersion = 1;
				}else if(engine.webkit < 312){
					safariVersion = 1.2;
				}else if(engine.webkit < 412){
					safariVersion = 1.3;
				}else{
					safariVersion = 2;
				}

				browser.safari = browser.ver = safariVersion;
			}
		}else if(/KHTML\/(\S+)/.test(ua) || /Konqueror\/([^;]+)/.test(ua)){
			engine.ver = browser.ver = RegExp['$1'];
			engine.khtml = browser.konq = parseFloat(engine.ver);
		}else if(/rv:([^\)]+)\) Gecko\/\d{8}/.test(ua)){
			engine.ver = RegExp["$1"];
			engine.gecko = parseFloat(engine.ver);

			// 确定是不是Firefox
			if(/Firefox\/(\S+)/.test(ua)){
				engine.ver = RegExp["$1"];
				engine.firefox = parseFloat(engine.ver);
			}
		}else if(/MSIE ([^;]+)/.test(ua)){
			engine.ver = browser.ver = RegExp['$1'];
			engine.ie = browser.ie = parseFloat(engine.ver);
		}


		// 检测浏览器
		browser.ie = engine.ie;
		browser.opera = engine.opera;


		// 检测平台
		var p = navigator.platform;
		system.win = p.indexOf("Win") == 0;
		system.mac = p.indexOf("Min") == 0;
		system.x11 = (p == "X11") || (p.indexOf("Linux") == 0);

		// 检测Windows操作系统
		if(system.win){
			if(/Win(?:dows )?([^do]{2})\s?(\d+\.\d+)?/.test(ua)){
				if(RegExp["$1"] == "NT"){
					switch(RegExp["$2"]){
						case "5.0":
							system.win = "2000";
							break;
						case "5.1":
							system.win = "XP";
							break;
						case "6.0":
							system.win = "Vista";
							break;
						case "6.1":
							system.win = "7";
							break;
						default:
							system.win = "NT";
							break;

					}
				}else if(RegExp["$1"] == "9x"){
					system.win = "ME";
				}else{
					system.win = RegExp["$1"];
				}
			}
		}

		// 移动设备
		system.iphone = ua.indexOf("iPhone") > -1;
		system.ipod = ua.indexOf("iPod") > -1;
		system.ipad = ua.indexOf("iPad") > -1;
		system.nokiaN = ua.indexOf("NokiaN") > -1;

		// windows mobile
		if(system.win == "CE"){
			system.winMobile = system.win;
		}else if(system.win == "Ph"){
			if(/Windows Phone OS (\d+.\d+)/.test(ua)){
				system.win = "Phone";
				system.winMobile = parseFloat(RegExp["$1"]);
			}
		}

		// 检测iOS版本
		if(system.mac && ua.indexOf("Mobile") > -1){
			if(/CPU (?:iPhone )?OS (\d+_\d+)/.test(ua)){
				system.ios = parseFloat(RegExp.$1.replace("_", "."));
			}else{
				system.ios = 2; // 不能真正检测出来，所以只能猜测
			}
		}

		// 检测Android版本
		if(/Android (\d+\.\d+)/.test(ua)){
			system.android = parseFloat(RegExp.$1);
		}

		// 游戏系统
		system.wii = ua.indexOf("Wii") > -1;
		system.ps = /playstation/i.test(ua);

		// 返回这些对象
		return {
			engine: engine,
			browser: browser,
			system: system
		}
	}();