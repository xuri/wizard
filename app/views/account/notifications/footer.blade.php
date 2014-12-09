</body>
{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
{{ HTML::script('assets/js/jingling.js') }}
{{ HTML::script('assets/js/color.js') }}
{{ HTML::script('assets/js/preInfo.js') }}
{{ HTML::script('assets/js/notifications.js') }}
 <script>
$(window).on('hashchange', function() {
	if (window.location.hash) {
		var page = window.location.hash.replace('#', '');
		if (page == Number.NaN || page <= 0) {
			return false;
		} else {
			getPosts(page);
		}
	}
});
$(document).ready(function() {
	$(document).on('click', '.lu_paging a', function(e) {
		getPosts($(this).attr('href').split('page=')[1]);
		e.preventDefault();
	});
});

function getPosts(page) {
	$.ajax({
		url: '?page=' + page,
		dataType: 'json',
	}).done(function(data) {
		$('.friendNotification').html(data);
		location.hash = page;
	}).fail(function() {
		alert('Posts could not be loaded.');
	});
}
</script>

</html>