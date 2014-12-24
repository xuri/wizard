</body>
{{ HTML::script('assets/fancybox-2.1.5/jquery.fancybox.pack.js') }}
{{ HTML::script('assets/js/forum.js') }}
<script>

	// jQuery Ajax Multi Pagination

	$(function() {
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
		$('#first').on('click', '.lu_paging a', function(e) {
			e.preventDefault();
			var pg = getPaginationSelectedPage($(this).attr('href'));

			$.ajax({
				url: '{{ route("forum.type", "first") }}',
				data: { page: pg },
				success: function(data) {
					$('#first').html(data);
				}
			});
		});

		$('#second').on('click', '.lu_paging a', function(e) {
			e.preventDefault();
			var pg = getPaginationSelectedPage($(this).attr('href'));

			$.ajax({
				url: '{{ route("forum.type", "second") }}',
				data: { page: pg },
				success: function(data) {
					$('#second').html(data);
				}
			});
		});

		$('#third').on('click', '.lu_paging a', function(e) {
			e.preventDefault();
			var pg = getPaginationSelectedPage($(this).attr('href'));

			$.ajax({
				url: '{{ route("forum.type", "third") }}',
				data: { page: pg },
				success: function(data) {
					$('#third').html(data);
				}
			});
		});

		// 3.
		$('#first').load('{{ route("forum.type", "first") }}?page=1');
		$('#second').load('{{ route("forum.type", "second") }}?page=1');
		$('#third').load('{{ route("forum.type", "third") }}?page=1');
	});

</script>
</html>