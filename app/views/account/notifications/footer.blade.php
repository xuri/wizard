{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
{{ HTML::script('assets/js/jingling.js') }}
{{ HTML::script('assets/js/color.js') }}
{{ HTML::script('assets/js/preInfo.js') }}
{{ HTML::script('assets/js/notifications.js') }}
<script>
	// Define variable use in forum.js
	var csrfToken 						= '{{ csrf_token() }}';
	var firstAjaxURL 					= "{{ route('account.notifications.type', 'first') }}";
	var secondAjaxURL 					= "{{ route('account.notifications.type', 'second') }}";
	var thirdAjaxURL 					= "{{ route('account.notifications.type', 'third') }}";

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
</script>
</body>
</html>