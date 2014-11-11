/*1获取元素的行间样式
*@ obj	元素对象
*@ attr	要获取的元素属性
*@ return 所获取的行间样式的值
*/

function getStyle(obj,attr){
	if(obj.currentStyle){
		return obj.currentStyle[attr];
	}else{
		return getComputedStyle(obj,false)[attr];
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

/*2根据class选择元素
*@ obj		父级元素
*@ sClass	要获取的元素的class名字
*@ return	元素组（array）
*/
function getByClass(obj,tagName,sClass){
	var aEle=obj.getElementsByTagName(tagName);
	var i=0;
	var aResult=[];
	for(i=0;i<aEle.length;i++){
		var aClassName=aEle[i].className.split(' ');
		for(j=0;j<aClassName.length;j++){
			if(aClassName[j]==sClass){
				aResult.push(aEle[i]);
				break;
			}
		}
		
	}
	return aResult;
}
/*3给元素添加class
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
/*4移出元素上的class
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
/*5检测浏览器类型和版本
*@ str		用来存储返回的数据
*@ return	浏览器类型
*
*/
function check_browser(){
	var str='';
	if(window.navigator.userAgent.search(/firefox/i)!=-1){
		str='ff';
	}else if(window.navigator.userAgent.search(/chrome/i)!=-1){
		str='chrome';
	}else if(window.navigator.userAgent.search(/msie 9/i)!=-1){
		str='IE9';
	}else if(window.navigator.userAgent.search(/msie 8/i)!=-1){
		str='IE8';
	}else if(window.navigator.userAgent.search(/msie 7/i)!=-1){
		str='IE7';
	}else if(window.navigator.userAgent.search(/msie 6/i)!=-1){
		str='IE6';
	}else{
		str='';
	}
	return str;
}
/*6运动框架
*@ obj	要运动的对象
*@ json	要运动的对象的属性和值的json数据
*@ iSpeed	运动速度（当type值为2时(即缓冲运动时)传入的iSpeed参数无效）
*@ type	运动类型（可选）值为1时：匀速运动	值为2时：缓冲运动
*@ fn	运动结束后的回调函数(可选)
*/
//type为运动类型1:匀速运动	2:缓冲运动(当选择缓冲运动的时候iSpeed课初始化为0)
function startMove(obj,ojson,iSpeed,type,fn){
	clearInterval(obj.timer);
	var icur=0;
	obj.timer=setInterval(function(){
		var bbool=true;
		for(var attr in ojson){
			var iTarget=Math.ceil(ojson[attr]);
			if(attr=='opacity'){
				icur=Math.round(css(obj,'opacity')*100);
			}else{
				icur=parseInt(css(obj,attr));
			}
			if(type==2){//缓冲运动
				iSpeed=(iTarget-icur)/8;
				iSpeed=iSpeed>0?Math.ceil(iSpeed) : Math.floor(iSpeed);
			}
			if(icur!=iTarget){
				bbool=false;
				if(attr=='opacity'){
					obj.style.opacity=(icur+iSpeed)/100;
					obj.style.filter="alpha(opacity="+(icur+iSpeed)+")";
				}else{
					obj.style[attr]=icur+iSpeed+'px';
				}
			}
		}
		if(bbool){
			clearInterval(obj.timer);
			fn && fn.call(obj);
		}
		
	},30);
	function css(obj,attr){
		if(obj.currentStyle){
			return obj.currentStyle[attr];
		}else{
			return getComputedStyle(obj,false)[attr];
		}
	}
}
/*
时间版运动框架
obj 要运动的对象
json 要运动的属性，例{width:200,height:200}
times 运动的时间
type 运动的形式（tWeen）
fn 回调函数
*/
function timeMove(obj,json,times,type,fn){

		
	//获取当前值
	var iCur={};
	for(var attr in json){
		iCur[attr]=0;
		if(attr=='opacity'){
			iCur[attr]=Math.round(getStyle(obj,attr)*100);
		}else{
			iCur[attr]=parseInt(getStyle(obj,attr));
		}
	}
	//
	clearInterval(obj.timer);
	var startTime=now();
	obj.timer=setInterval(function(){

		var changeTime=now();
		var t=times-Math.max(0,(startTime+times-changeTime));//t的值是0-2000
		for(var attr in json){
			var value=Tween[type](t,iCur[attr],json[attr]-iCur[attr],times);
			if(attr=='opacity'){
				obj.style.opacity=value/100;
				obj.style.filter='alpha(opacity='+value+')';
			}else{
				obj.style[attr]=value+'px';
			}
			
		}
		if(t==times){
			clearInterval(obj.timer);
			if(fn){
				fn.call(obj);
			}
		}
		
	},13);

	function getStyle(obj,attr){
		if(obj.currentStyle){
			return obj.currentStyle[attr];
		}else{
			return getComputedStyle(obj,false)[attr];
		}
	}

	function now(){
		return new Date().getTime();
	}

	var Tween = {
		linear: function (t, b, c, d){  //匀速
			return c*t/d + b;
		},
		easeIn: function(t, b, c, d){  //加速曲线
			return c*(t/=d)*t + b;
		},
		easeOut: function(t, b, c, d){  //减速曲线
			return -c *(t/=d)*(t-2) + b;
		},
		easeBoth: function(t, b, c, d){  //加速减速曲线
			if ((t/=d/2) < 1) {
				return c/2*t*t + b;
			}
			return -c/2 * ((--t)*(t-2) - 1) + b;
		},
		easeInStrong: function(t, b, c, d){  //加加速曲线
			return c*(t/=d)*t*t*t + b;
		},
		easeOutStrong: function(t, b, c, d){  //减减速曲线
			return -c * ((t=t/d-1)*t*t*t - 1) + b;
		},
		easeBothStrong: function(t, b, c, d){  //加加速减减速曲线
			if ((t/=d/2) < 1) {
				return c/2*t*t*t*t + b;
			}
			return -c/2 * ((t-=2)*t*t*t - 2) + b;
		},
		elasticIn: function(t, b, c, d, a, p){  //正弦衰减曲线（弹动渐入）
			if (t === 0) { 
				return b; 
			}
			if ( (t /= d) == 1 ) {
				return b+c; 
			}
			if (!p) {
				p=d*0.3; 
			}
			if (!a || a < Math.abs(c)) {
				a = c; 
				var s = p/4;
			} else {
				var s = p/(2*Math.PI) * Math.asin (c/a);
			}
			return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
		},
		elasticOut: function(t, b, c, d, a, p){    //正弦增强曲线（弹动渐出）
			if (t === 0) {
				return b;
			}
			if ( (t /= d) == 1 ) {
				return b+c;
			}
			if (!p) {
				p=d*0.3;
			}
			if (!a || a < Math.abs(c)) {
				a = c;
				var s = p / 4;
			} else {
				var s = p/(2*Math.PI) * Math.asin (c/a);
			}
			return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
		},    
		elasticBoth: function(t, b, c, d, a, p){
			if (t === 0) {
				return b;
			}
			if ( (t /= d/2) == 2 ) {
				return b+c;
			}
			if (!p) {
				p = d*(0.3*1.5);
			}
			if ( !a || a < Math.abs(c) ) {
				a = c; 
				var s = p/4;
			}
			else {
				var s = p/(2*Math.PI) * Math.asin (c/a);
			}
			if (t < 1) {
				return - 0.5*(a*Math.pow(2,10*(t-=1)) * 
						Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
			}
			return a*Math.pow(2,-10*(t-=1)) * 
					Math.sin( (t*d-s)*(2*Math.PI)/p )*0.5 + c + b;
		},
		backIn: function(t, b, c, d, s){     //回退加速（回退渐入）
			if (typeof s == 'undefined') {
			   s = 1.70158;
			}
			return c*(t/=d)*t*((s+1)*t - s) + b;
		},
		backOut: function(t, b, c, d, s){
			if (typeof s == 'undefined') {
				s = 3.70158;  //回缩的距离
			}
			return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
		}, 
		backBoth: function(t, b, c, d, s){
			if (typeof s == 'undefined') {
				s = 1.70158; 
			}
			if ((t /= d/2 ) < 1) {
				return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
			}
			return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
		},
		bounceIn: function(t, b, c, d){    //弹球减振（弹球渐出）
			return c - Tween['bounceOut'](d-t, 0, c, d) + b;
		},       
		bounceOut: function(t, b, c, d){
			if ((t/=d) < (1/2.75)) {
				return c*(7.5625*t*t) + b;
			} else if (t < (2/2.75)) {
				return c*(7.5625*(t-=(1.5/2.75))*t + 0.75) + b;
			} else if (t < (2.5/2.75)) {
				return c*(7.5625*(t-=(2.25/2.75))*t + 0.9375) + b;
			}
			return c*(7.5625*(t-=(2.625/2.75))*t + 0.984375) + b;
		},      
		bounceBoth: function(t, b, c, d){
			if (t < d/2) {
				return Tween['bounceIn'](t*2, 0, c, d) * 0.5 + b;
			}
			return Tween['bounceOut'](t*2-d, 0, c, d) * 0.5 + c*0.5 + b;
		}
	}

}
//弹性运动
function t_startMove(obj,attr,iTarget,fn){
	var iSpeed=0;
	var height=0;
	clearInterval(obj.timer);
	obj.timer=setInterval(function(){
		iSpeed+=(iTarget-height)/5;
		iSpeed*=0.7;
		if(Math.abs(iSpeed)<1 && Math.abs(iTarget-height)<1){
			clearInterval(obj.timer);
			obj.style[attr]=iTarget+'px';
			if(fn){
				fn();
			}
		}else{
			height+=iSpeed;
			obj.style[attr]=height+'px';
		}
	},30);
}

//7获取元素到页面的绝对位置
/*
*	@obj   参数		元素对象
*	@return   pos----json	(包含元素在页面中的绝对位置left/top)
*
*/
function getAbPos(obj){
	var pos={left:0,top:0}
	while(obj){
		pos.left+=obj.offsetLeft;
		pos.top+=obj.offsetTop;
		obj=obj.offsetParent;
	}
	return pos;
}
//8简陋版的ajax封装函数
/*
*@method	请求方式（get/post）
*@url		请求地址
*@data		参数
*@tfn		回调函数----非必须参数
*/
function ajax(method,url,data,tfn){
	var xhr=null;
	//做兼容版的创建ajax对象
	//方法一
	if(window.XMLHttpRequest){
		xhr = new XMLHttpRequest();
	}else{
		xhr = new ActiveXObject('Microsoft.XMLHTTP');
	}
	//方法二
	/*
	try{
		xhr = new XMLHttpRequest();
	}catch(e){
		xhr = new ActiveXObject('Microsoft.XMLHTTP');
	}
	*/
	if(method=='get' && data){
		url+='?'+data+'&'+new Date().getTime();
	}
	xhr.open(method,url,true);
	if(method=='get'){
		xhr.send();
	}else{
		xhr.setRequestHeader('content-type','application/x-www-form-urlencoded');
		xhr.send(data);
	}
	
	xhr.onreadystatechange=function(){
		if(xhr.readyState==4){
			if(xhr.status==200){
				tfn && tfn(xhr.responseText);//存在才执行
			}else{
				alert("出错了："+xhr.status);
			}
			
		}
	}
}
/*9获得给定范围内的随机数
*@lowernum		最小值
*@uppernum		最大值
*@return		介于最小值和最大值之间的一个随机值
*
*/
function getRandom(lowernum,uppernum){
	var allnum=uppernum-lowernum+1;
	return Math.floor(Math.random()*allnum+lowernum);
}
/*10根据url参数的名字获取参数的值
*http://localhost/html/test/a.php?q=9&m=sdf
*@return	对象。对象的属性就是q和m
*/
function getUrlargus(){
	var queryStr=location.search.length>0?location.search.substring(1):"";//如果url有参数部分，就获取
	//定义一个对象来保存参数名和值
	var args={};
	var items=queryStr.length>0?queryStr.split("&"):[];//把queryStr按&分解成数组
	
	var item=null;
	var name=null;
	var value=null;
	var len=items.length;
	for(var i=0;i<len;i++){
		item=items[i].split("=");
		name=decodeURIComponent(item[0]);
		value=decodeURIComponent(item[1]);
		args[name]=value;
	}
	
	return args;

}
/*11检测浏览器是否安装了某款插件
*@kname	参数	插件的名称
*
*/
//非IE检测插件
function checkNIEPlugins(kname){
	var len=navigator.plugins.length;
	for(var i=0;i<len;i++){
		if(navigator.plugins[i].name.toLowerCase().indexOf(kname)>-1){
			return true;
		}
	}
	return false;
}
//IE检测插件
function checkIEPlugins(kname){
	try{
		new ActiveXObject(kname);
		return true;
	}catch(e){
		return false;
	}
}
/*12给元素绑定事件，这种绑定事件的方法可以给一个对象绑定多个互补影响的事件
*@obj	要绑定事件的对象
*@evname	绑定事件的名称
*@fn	事件函数
*@bool	是否捕获
*/
function bind(obj,evname,fn,bool){
	if(obj.addEventListener){
		//事件捕获，true监控进去的事件，false监控出去的事件（就是冒泡）
		obj.addEventListener(evname,fn,bool);//标准浏览器下事件绑定//阻止默认事件用ev.preventDefault();
	}else{
		obj.attachEvent('on'+evname,function(){fn.call(obj);});//IE下事件绑定//阻止默认事件还用return false
	}
}
/*13给元素取消绑定事件，这种绑定事件的方法可以给一个对象绑定多个互补影响的事件
*@obj	要取消绑定事件的对象
*@evname	取消绑定事件的名称
*@fn	事件函数
*@bool	是否捕获
*/
function removebind(obj,evname,fn,bool){
	if(obj.removeEventListener){
		//事件捕获，true监控进去的事件，false监控出去的事件（就是冒泡）
		obj.removeEventListener(evname,fn,bool);//标准浏览器下事件绑定
	}else{
		obj.detachEvent('on'+evname,function(){fn.call(obj);});//IE下事件绑定
	}
}
/*14设置cookie
*@key	cookie的键
*@value	cookie的值
*@time	过期时间（设置为负数的时候为删除cookie）
*/
function setCookie(key,value,time){
	var oDate=new Date();
	oDate.setDate(oDate.getDate()+time);
	document.cookie=key+"="+encodeURI(value)+";expires="+oDate.toGMTString();
}
/*15获取cookie
*@key	cookie的键
*
*
*/
function getCookie(key){
	var arr1=document.cookie.split('; ');
	for(var i=0;i<arr1.length;i++){
		var arr2=arr1[i].split('=');
		if(arr2[0]==key){
			return decodeURI(arr2[1]);
		}
	}
	return null;
}
/*功能: 1)去除字符串前后所有空格
*       2)去除字符串中所有空格(包括中间空格,需要设置第2个参数为:g)
*/
function Trim(str,is_global){
	var result; 
	result = str.replace(/(^\s+)|(\s+$)/g,"");
	if(is_global.toLowerCase()=="g")
	result = result.replace(/\s/g,"");
	return result;
}