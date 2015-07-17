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

// 每一屏的高度
var perHeight = $(window).height();
var curBlock = 1;
var curTop = 0;
var scrollLabel = true;
// 计算每一屏 的高度
$('.big-box').css({height: perHeight + 'px'});

// 当鼠标滚动的时候监听滚动事件
$(window).on('mousewheel', scrollHandle);

function scrollHandle(event) {
	
	if(scrollLabel){

		if(event.deltaY > 0){
			// 向上滚动
			curBlock -= 1;
		}else if(event.deltaY < 0){
			// 向下滚动
			curBlock += 1;
		}
		if(curBlock > 5){
			curBlock = 5;
		}else if(curBlock < 1){
			curBlock = 1;
		}
		switch(curBlock){
			case 1:
				curTop = 0;
				break;
			case 2:
				curTop = -1 * perHeight;
				break;
			case 3:
				curTop = -2 * perHeight;
				break;
			case 4:
				curTop = -3 * perHeight;
				break;
			case 5:
				curTop = -3 * perHeight - $('.footer').height();
				break;

		}


		scrollLabel = false;
		console.log(event.deltaX, event.deltaY, event.deltaFactor);
	    //$(window).off('mousewheel', scrollHandle);
	    if(event.deltaY > 0){
			// 向上滚动
			//$(window).scrollTop(2000)
			$('#content').animate({ top:curTop },1000,'swing', function(){
				scrollLabel = true;
			});
		}else if(event.deltaY < 0){
			// 向下滚动
			//$(window).scrollTop(2000)
			$('#content').animate({ top:curTop },1000,'swing', function(){
				scrollLabel = true;
			});
		}
		


	}


}

// 获取鼠标滚轮滚动方向（上120、下-120）
/* 注意此函数用的时候要绑定两个鼠标滚动事件
 * Event.addHandler(document, "mousewheel", handle);
 * Event.addHandler(document, "DOMMouseScroll", handle); // 火狐
 */
function getWheelDelta(event){
	if(event.wheelDelta){

		// 用到了客户端检测，为了兼容老版本的Opera
		return (client.engine.opera && client.engine.opera < 9.5 ? -event.wheelDelta : event.wheelDelta);
	}else{
		// 兼容火狐
		return -event.detail * 40;
	}
};

// 给元素绑定事件
function addHandler(element, type, handler){
	if(element.addEventListener){
		element.addEventListener(type, handler, false);
	}else if(element.attachEvent){
		element.attachEvent("on" + type, handler);
	}else{
		element["on" + type] = handler;
	}
};
// 移除事件
function removeHandler(element, type, handler){
	if(element.removeEventListener){
		element.removeEventListener(type, handler, false);
	}else if(element.detachEvent){
		element.detachEvent("on" + type, handler);
	}else{
		element["on" + type] = null;
	}
};
