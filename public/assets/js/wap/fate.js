// 轮播图
var swiper = new Swiper('.swiper-container', {
    pagination: '.swiper-pagination',
    paginationClickable: true
});

$('.swiper-container').css({width: "288px"});

// 点击翻TA
$('.rotate-btn').on('tap', rotateFn);
function rotateFn(){
	// 检查翻牌机会是否用完
	if( parseInt($('.pai-nums').html()) <= 0 ){
		alert('您的翻牌机会已经用完啦');
	}else{
		// 检查是否已经翻过了
		if( parseInt($('.swiper-slide-active').data('ready')) == 0 ){
			$('.swiper-slide-active .pai1').addClass('pai-yes-zhuan');
			$('.swiper-slide-active .pai2').addClass('pai-no-zhuan');
			$('.swiper-slide-active').data('ready', 1);
			$('.pai-nums').html( parseInt($('.pai-nums').html()) - 1 );
		}
		
	}
	
}