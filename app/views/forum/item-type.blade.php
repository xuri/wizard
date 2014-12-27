<ul id="bbs_main_clinic" class="bbs_main bbs_main_{{ $categoryCode }}">
	@foreach($items as $post)
	<li class="bbs_main_boy">
		<a href="{{ route('forum.show', $post->id) }}" target="_blank">{{ $post->title }}</a>
		<p>{{ $post->content }}</p>
		<span class="bbs_main_look">{{ ForumComments::where('post_id', $post->id)->count() }}</span>
		<span class="bbs_main_time">{{ date("m-d H:m",strtotime($post->created_at)) }}</span>
	</li>
	@endforeach
</ul>

{{ pagination($items->appends(Input::except('page')), 'layout.paginator') }}

<script>
	// Fancybox

	// Open external links in new window
	var externalLinks = function(){
		var host = location.host;

		$('body').on('click', 'a', function(e){
			var href = this.href,
				link = href.replace(/https?:\/\/([^\/]+)(.*)/, '$1');

			if (link != '' && link != host && !$(this).hasClass('fancybox')){
				window.open(href);
				e.preventDefault();
			}
		});
	};

	// Append caption after pictures
	var appendCaption = function(){
		$('.bbs_main_boy p').each(function(i){
			var _i = i;
			$(this).find('img').each(function(){
				var alt = this.alt;

				// if (alt != ''){
				// 	$(this).after('<span class="caption">'+alt+'</span>');
				// }

				$(this).wrap('<a href="'+this.src+'" title="'+alt+'" class="fancybox" rel="gallery'+_i+'" />');
			});
		});
	};

	externalLinks(); // Delete or comment this line to disable opening external links in new window
	appendCaption(); // Delete or comment this line to disable caption
	$('.fancybox').fancybox({
		arrows : false // Disable fancybox previous and next links showing up
	});
</script>