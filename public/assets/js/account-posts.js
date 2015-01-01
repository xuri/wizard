// Ajax pagination

$(function() {
	$('#load-ajax').on('click', '.lu_paging a', function (e) {
		getPosts($(this).attr('href').split('page=')[1]);
		e.preventDefault();
	});
});

function getPosts(page) {
	$.ajax({
		url : '?page=' + page,
		dataType: 'json',
	}).done(function (data) {
		$('#load-ajax').html(data);
	}).fail(function () {
		alert('Posts could not be loaded.');
	});
}