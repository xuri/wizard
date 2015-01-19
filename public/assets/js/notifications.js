$('.new_tab').find('li').click(function(){
	$('.new_tab').find('li').attr('class','');
	$(this).attr('class','active');
	$('.new_main').css('display','none');
	$('.new_main').eq($(this).index()/2).css('display','block');
});

// jQuery Ajax Multi Pagination

// 1.
function getPaginationSelectedPage(url) {
	var chunks = url.split('?');
	var baseUrl = chunks[0];
	var querystr = chunks[1].split('&');
	var pg = 1;
	for (i in querystr) {
		var qs = querystr[i].split('=');
		if (qs[0] == 'page') {
			pg = qs[1];
			break;
		}
	}
	return pg;
}

// 2.
$('#new_main_mine').on('click', '.lu_paging a', function(e) {
	e.preventDefault();
	var pg = getPaginationSelectedPage($(this).attr('href'));

	$.ajax({
		url: firstAjaxURL,
		data: { page: pg },
		success: function(data) {
			$('#first_inner').html(data);
		}
	});
});

$('#new_main_forum').on('click', '.lu_paging a', function(e) {
	e.preventDefault();
	var pg = getPaginationSelectedPage($(this).attr('href'));

	$.ajax({
		url: secondAjaxURL,
		data: { page: pg },
		success: function(data) {
			$('#second_inner').html(data);
		}
	});
});

$('#new_main_system').on('click', '.lu_paging a', function(e) {
	e.preventDefault();
	var pg = getPaginationSelectedPage($(this).attr('href'));

	$.ajax({
		url: thirdAjaxURL,
		data: { page: pg },
		success: function(data) {
			$('#third_inner').html(data);
		}
	});
});

$(function(){
	// 3.
	$('#first_inner').load(firstAjaxURL + '?page=1');
	$('#second_inner').load(secondAjaxURL + '?page=1');
	$('#third_inner').load(thirdAjaxURL + '?page=1');
});