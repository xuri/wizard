function getStyle(obj,attr){if(obj.currentStyle){return obj.currentStyle[attr];}else{return getComputedStyle(obj,false)[attr];}}
function getById(id){return document.getElementById(id);}
function getByClass(obj,tagName,sClass){var aEle=obj.getElementsByTagName(tagName);var i=0;var aResult=[];for(i=0;i<aEle.length;i++){var aClassName=aEle[i].className.split(' ');for(j=0;j<aClassName.length;j++){if(aClassName[j]==sClass){aResult.push(aEle[i]);break;}}}
return aResult;}
function addClass(obj,sClass){if(obj.className==''){obj.className=sClass;}else{var arrClassName=obj.className.split(' ');if(inarr(arrClassName,sClass)==-1){obj.className+=(' '+sClass);}}
function inarr(arr,str){var tmp=-1;for(var i=0;i<arr.length;i++){if(arr[i]==str){tmp=i;}}
return tmp;}}
function removeClass(obj,sClass){if(obj.className!=''){var arrClassName=obj.className.split(' ');var _index=inarr(arrClassName,sClass);if(_index!=-1){arrClassName.splice(_index,1);obj.className=arrClassName.join(' ');}}
function inarr(arr,str){var tmp=-1;for(var i=0;i<arr.length;i++){if(arr[i]==str){tmp=i;}}
return tmp;}}
function check_browser(){var str='';if(window.navigator.userAgent.search(/firefox/i)!=-1){str='ff';}else if(window.navigator.userAgent.search(/chrome/i)!=-1){str='chrome';}else if(window.navigator.userAgent.search(/msie 9/i)!=-1){str='IE9';}else if(window.navigator.userAgent.search(/msie 8/i)!=-1){str='IE8';}else if(window.navigator.userAgent.search(/msie 7/i)!=-1){str='IE7';}else if(window.navigator.userAgent.search(/msie 6/i)!=-1){str='IE6';}else{str='';}
return str;}
function startMove(obj,ojson,iSpeed,type,fn){clearInterval(obj.timer);var icur=0;obj.timer=setInterval(function(){var bbool=true;for(var attr in ojson){var iTarget=Math.ceil(ojson[attr]);if(attr=='opacity'){icur=Math.round(css(obj,'opacity')*100);}else{icur=parseInt(css(obj,attr));}
if(type==2){iSpeed=(iTarget-icur)/8;iSpeed=iSpeed>0?Math.ceil(iSpeed):Math.floor(iSpeed);}
if(icur!=iTarget){bbool=false;if(attr=='opacity'){obj.style.opacity=(icur+iSpeed)/100;obj.style.filter="alpha(opacity="+(icur+iSpeed)+")";}else{obj.style[attr]=icur+iSpeed+'px';}}}
if(bbool){clearInterval(obj.timer);fn&&fn.call(obj);}},30);function css(obj,attr){if(obj.currentStyle){return obj.currentStyle[attr];}else{return getComputedStyle(obj,false)[attr];}}}
function timeMove(obj,json,times,type,fn){var iCur={};for(var attr in json){iCur[attr]=0;if(attr=='opacity'){iCur[attr]=Math.round(getStyle(obj,attr)*100);}else{iCur[attr]=parseInt(getStyle(obj,attr));}}
clearInterval(obj.timer);var startTime=now();obj.timer=setInterval(function(){var changeTime=now();var t=times-Math.max(0,(startTime+times-changeTime));for(var attr in json){var value=Tween[type](t,iCur[attr],json[attr]-iCur[attr],times);if(attr=='opacity'){obj.style.opacity=value/100;obj.style.filter='alpha(opacity='+value+')';}else{obj.style[attr]=value+'px';}}
if(t==times){clearInterval(obj.timer);if(fn){fn.call(obj);}}},13);function getStyle(obj,attr){if(obj.currentStyle){return obj.currentStyle[attr];}else{return getComputedStyle(obj,false)[attr];}}
function now(){return new Date().getTime();}
var Tween={linear:function(t,b,c,d){return c*t/d+b;},easeIn:function(t,b,c,d){return c*(t/=d)*t+b;},easeOut:function(t,b,c,d){return-c*(t/=d)*(t-2)+b;},easeBoth:function(t,b,c,d){if((t/=d/2)<1){return c/2*t*t+b;}
return-c/2*((--t)*(t-2)-1)+b;},easeInStrong:function(t,b,c,d){return c*(t/=d)*t*t*t+b;},easeOutStrong:function(t,b,c,d){return-c*((t=t/d-1)*t*t*t-1)+b;},easeBothStrong:function(t,b,c,d){if((t/=d/2)<1){return c/2*t*t*t*t+b;}
return-c/2*((t-=2)*t*t*t-2)+b;},elasticIn:function(t,b,c,d,a,p){if(t===0){return b;}
if((t /=d)==1){return b+c;}
if(!p){p=d*0.3;}
if(!a||a<Math.abs(c)){a=c;var s=p/4;}else{var s=p/(2*Math.PI)*Math.asin(c/a);}
return-(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b;},elasticOut:function(t,b,c,d,a,p){if(t===0){return b;}
if((t /=d)==1){return b+c;}
if(!p){p=d*0.3;}
if(!a||a<Math.abs(c)){a=c;var s=p / 4;}else{var s=p/(2*Math.PI)*Math.asin(c/a);}
return a*Math.pow(2,-10*t)*Math.sin((t*d-s)*(2*Math.PI)/p)+c+b;},elasticBoth:function(t,b,c,d,a,p){if(t===0){return b;}
if((t /=d/2)==2){return b+c;}
if(!p){p=d*(0.3*1.5);}
if(!a||a<Math.abs(c)){a=c;var s=p/4;}
else{var s=p/(2*Math.PI)*Math.asin(c/a);}
if(t<1){return-0.5*(a*Math.pow(2,10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p))+b;}
return a*Math.pow(2,-10*(t-=1))*Math.sin((t*d-s)*(2*Math.PI)/p)*0.5+c+b;},backIn:function(t,b,c,d,s){if(typeof s=='undefined'){s=1.70158;}
return c*(t/=d)*t*((s+1)*t-s)+b;},backOut:function(t,b,c,d,s){if(typeof s=='undefined'){s=3.70158;}
return c*((t=t/d-1)*t*((s+1)*t+s)+1)+b;},backBoth:function(t,b,c,d,s){if(typeof s=='undefined'){s=1.70158;}
if((t /=d/2)<1){return c/2*(t*t*(((s*=(1.525))+1)*t-s))+b;}
return c/2*((t-=2)*t*(((s*=(1.525))+1)*t+s)+2)+b;},bounceIn:function(t,b,c,d){return c-Tween['bounceOut'](d-t,0,c,d)+b;},bounceOut:function(t,b,c,d){if((t/=d)<(1/2.75)){return c*(7.5625*t*t)+b;}else if(t<(2/2.75)){return c*(7.5625*(t-=(1.5/2.75))*t+0.75)+b;}else if(t<(2.5/2.75)){return c*(7.5625*(t-=(2.25/2.75))*t+0.9375)+b;}
return c*(7.5625*(t-=(2.625/2.75))*t+0.984375)+b;},bounceBoth:function(t,b,c,d){if(t<d/2){return Tween['bounceIn'](t*2,0,c,d)*0.5+b;}
return Tween['bounceOut'](t*2-d,0,c,d)*0.5+c*0.5+b;}}}
function t_startMove(obj,attr,iTarget,fn){var iSpeed=0;var height=0;clearInterval(obj.timer);obj.timer=setInterval(function(){iSpeed+=(iTarget-height)/5;iSpeed*=0.7;if(Math.abs(iSpeed)<1&&Math.abs(iTarget-height)<1){clearInterval(obj.timer);obj.style[attr]=iTarget+'px';if(fn){fn();}}else{height+=iSpeed;obj.style[attr]=height+'px';}},30);}
function getAbPos(obj){var pos={left:0,top:0}
while(obj){pos.left+=obj.offsetLeft;pos.top+=obj.offsetTop;obj=obj.offsetParent;}
return pos;}
function ajax(method,url,data,tfn){var xhr=null;if(window.XMLHttpRequest){xhr=new XMLHttpRequest();}else{xhr=new ActiveXObject('Microsoft.XMLHTTP');}
if(method=='get'&&data){url+='?'+data+'&'+new Date().getTime();}
xhr.open(method,url,true);if(method=='get'){xhr.send();}else{xhr.setRequestHeader('content-type','application/x-www-form-urlencoded');xhr.send(data);}
xhr.onreadystatechange=function(){if(xhr.readyState==4){if(xhr.status==200){tfn&&tfn(xhr.responseText);}else{alert("出错了："+xhr.status);}}}}
function getRandom(lowernum,uppernum){var allnum=uppernum-lowernum+1;return Math.floor(Math.random()*allnum+lowernum);}
function getUrlargus(){var queryStr=location.search.length>0?location.search.substring(1):"";var args={};var items=queryStr.length>0?queryStr.split("&"):[];var item=null;var name=null;var value=null;var len=items.length;for(var i=0;i<len;i++){item=items[i].split("=");name=decodeURIComponent(item[0]);value=decodeURIComponent(item[1]);args[name]=value;}
return args;}
function checkNIEPlugins(kname){var len=navigator.plugins.length;for(var i=0;i<len;i++){if(navigator.plugins[i].name.toLowerCase().indexOf(kname)>-1){return true;}}
return false;}
function checkIEPlugins(kname){try{new ActiveXObject(kname);return true;}catch(e){return false;}}
function bind(obj,evname,fn,bool){if(obj.addEventListener){obj.addEventListener(evname,fn,bool);}else{obj.attachEvent('on'+evname,function(){fn.call(obj);});}}
function removebind(obj,evname,fn,bool){if(obj.removeEventListener){obj.removeEventListener(evname,fn,bool);}else{obj.detachEvent('on'+evname,function(){fn.call(obj);});}}
function setCookie(key,value,time){var oDate=new Date();oDate.setDate(oDate.getDate()+time);document.cookie=key+"="+encodeURI(value)+";expires="+oDate.toGMTString();}
function getCookie(key){var arr1=document.cookie.split('; ');for(var i=0;i<arr1.length;i++){var arr2=arr1[i].split('=');if(arr2[0]==key){return decodeURI(arr2[1]);}}
return null;}
function Trim(str,is_global){var result;result=str.replace(/(^\s+)|(\s+$)/g,"");if(is_global.toLowerCase()=="g")
result=result.replace(/\s/g,"");return result;}var colorConfig=['#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0'];window.onload=function(){var clickon=getById('clickon');var pillars_auto=getById('pillars_auto');var pillars_fixed=getById('pillars_fixed');var days=pillars_auto.getElementsByTagName('span')[0];var instr=getById('instr');clickon.onclick=function(){$.post(post_renew_url,{'_token':token},function(jdata){if(jdata['success']){pillars_auto.style.width=pillars_auto.offsetWidth+10+'px';days.innerHTML=parseInt(days.innerHTML)+1;}});if(parseInt(days.innerHTML)%50==0){}}
clickon.onmouseover=function(){instr.style.display='block';}
clickon.onmouseout=function(){instr.style.display='none';}}