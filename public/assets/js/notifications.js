$('.new_tab').find('li').click(function(){
	$('.new_tab').find('li').attr('class','');
	$(this).attr('class','active');
	$('.new_main').css('display','none');
	$('.new_main').eq($(this).index()/2).css('display','block');
});