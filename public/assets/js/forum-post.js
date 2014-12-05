// By luxurioust use jQuery

// Leave comment for this post href jump

$(".smooth").click(function(){
    var href = $(this).attr("href");
    var pos = $(href).offset().top;
    $("html,body").animate({scrollTop: pos}, 800);
    return false;
});

// For reply comment button and textarea function

$('.reply_comment_form').click(function(e){
    e.stopPropagation();
});

$('.reply_comment').click(function(e){

    e.preventDefault();
    e.stopPropagation();
    // hide all span
    var $this = $(this).parent().parent().parent().find('.reply_comment_form');
    $(".reply_comment_form").not($this).hide();

    // here is what I want to do
    $this.fadeToggle(300);

});

$(document).click(function() {
    $('.reply_comment_form').fadeOut(400);
});

// For inner reply comment button and textarea function

$('.reply_inner_form').click(function(e){
    e.stopPropagation();
});

$('.reply_inner').click(function(e){

    e.preventDefault();
    e.stopPropagation();
    // hide all span
    var $this = $(this).parent().find('.reply_inner_form');
    $(".reply_inner_form").not($this).hide();

    // here is what I want to do
    $this.fadeToggle(300);

});

$(document).click(function() {
    $('.reply_inner_form').fadeOut(400);
});

// // By Qiongxue
// var aO=document.getElementById('g-list');
// 	var aA=[];
// 	var aL=rList();
// 	getA();
// 	function getA(){
// 		var num=aO.getElementsByTagName('a');
// 		for(var b=0;b<num.length;b++){
// 			if(num[b].className=='replay-a'){
// 				aA.push(num[b]);
// 			}
// 		}
// 	}
// 	function rList(){
// 		var arr=new Array();
// 		var num=aO.getElementsByTagName('div');
// 		for(var i=0;i<num.length;i++){
// 			if(num[i].className=='o-others'){
// 				arr.push(num[i]);
// 			}
// 		}
// 		return arr;
// 	}

// 	for(var i=0; i<aA.length;i++){
// 		(function(k){
// 			aA[k].onclick=function(){
// 				Reply(k);
// 			}
// 		})(i);
// 	}

// 	var gRe=document.getElementById('g-replay');
// 	gRe.onmousedown=function(){
// 		this.style.boxShadow='0 0 0 #fff';
// 	}
// 	gRe.onmouseup=function(){
// 		this.style.boxShadow='1px 1px 0 #c2c2c2';
// 	}
// 	function Reply(k){
// 		var textarea=document.createElement('textarea'),
// 			input=document.createElement('input');
// 		var div=aA[k].parentNode;
// 		var span=div.getElementsByTagName('span')[1];
// 		var c=div.getElementsByTagName('textarea')[0];
// 		if(c){
// 			Reset(k,div);
// 			return;
// 		}
// 		input.className='submit';
// 		input.type='submit';
// 		input.value='发表';
// 		input.onclick=function(){
// 			var oThis=this;
// 			fSubmit(oThis,div);
// 		}
// 		textarea.className='textarea';
// 		if(div.getElementsByTagName('h3')[0].outerText){
// 			textarea.innerHTML='@'+div.getElementsByTagName('h3')[0].outerText;
// 		}else{
// 			textarea.innerHTML='@'+div.getElementsByTagName('h3')[0].textContent;
// 		}

// 		div.insertBefore(textarea,span);
// 		div.insertBefore(input,span);
// 	}
// 	function Reset(k,div){
// 		if(div.getElementsByTagName('textarea')[0]){
// 			div.removeChild(document.getElementsByTagName('textarea')[0]);
// 			div.removeChild(document.getElementsByTagName('input')[0]);
// 		}
// 	}
// 	function fSubmit(oThis,oDiv){
// 		var div=document.createElement('div');
// 		var imgBox=document.createElement('span');
// 		imgBox.className='imgSpan';
// 		var imgHead=document.createElement('img');
// 		imgHead.src='images/headImg.jpg';
// 		imgBox.appendChild(imgHead);
// 		var sexImg=document.createElement('img');
// 		sexImg.className='o-sexImg';
// 		sexImg.src='images/symbol.png';
// 		var gH3=document.createElement('h3');
// 		gH3.className='g-h3';
// 		gH3.innerHTML='Test'+':';						//这个也是记得修改
// 		var rValue=document.createElement('p');
// 		rValue.className='r-value';
// 		var value=oThis.parentNode.getElementsByTagName('textarea')[0].value;
// 		rValue.innerHTML=value;  							//这个别忘了填写^_^；
// 		var replayA=document.createElement('a');
// 		replayA.className='replay-a';
// 		replayA.innerHTML='回复';
// 		var date=document.createElement('p');
// 		date.className='date';
// 		date.innerHTML='2014-11-22 10:45';
// 		var spanLine=document.createElement('span');
// 		spanLine.className='span-line';
// 		div.appendChild(imgBox);
// 		div.appendChild(sexImg);
// 		div.appendChild(gH3);
// 		div.appendChild(rValue);
// 		div.appendChild(replayA);
// 		div.appendChild(date);
// 		div.appendChild(spanLine);
// 		oDiv.appendChild(div);
// 		getA();
// 		for(var i=0; i<aA.length;i++){
// 			(function(k){
// 				aA[k].onclick=function(){
// 					Reply(k);
// 				}
// 				Reset(k,oDiv);
// 			})(i);
// 		}
// 	}