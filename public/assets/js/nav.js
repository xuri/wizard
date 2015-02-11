var oNavMain=document.getElementById('nav_main'),
	oNav=document.getElementById('nav_left').getElementsByTagName('ul')[0],
	nav=document.getElementById('nav_left'),
	navNum=false;
oNavMain.onmouseover=function(){
	if(navNum==true){
		return false;
	}else{
		navS();
	}
}

function navS(){
	if(navNum==false){
		nav.style.left='0px';
		navNum=true;
	}else{
		nav.style.left='-60px';
		navNum=false;
	}
}
// function stopBubble(e){
// 	var e=e||windw.event;
//     if(e&&e.stopPropagation){
//         e.stopPropagation();
//     }else{
//         e.cancelBubble = true;
//     }
// }
oNavMain.onclick=function(e){
	if(navNum==true){
		var arr=oNav.getElementsByTagName('li');
		for(var i=0;i<arr.length;i++){
			arr[i].style.width='60px';
		}
	}
	navS();
	var e=e||event;
	e.cancelBubble = true;
};
oNav.onmouseover=function(){
	var arr=oNav.getElementsByTagName('li');
	for(var i=0;i<arr.length;i++){
		arr[i].style.width='150px';
	}
}
oNav.onclick=function(e){
	var e=e||windw.event;
	e.cancelBubble = true;
}
document.onclick=function(){
	var arr=oNav.getElementsByTagName('li');
	for(var i=0;i<arr.length;i++){
		arr[i].style.width='60px';
	}
	if(navNum==false){

	}else{
		navS();
	}
}