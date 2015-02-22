<ul id="bbs_main_clinic" class="bbs_main bbs_main_{{ $categoryCode }}">
	@foreach($tops as $top)
	<li class="bbs_main_boy">
		<span class="tops">置 顶</span>
		<a href="{{ route('forum.show', $top->id) }}" target="_blank">{{ Str::limit($top->title, 35) }}</a>

		{{-- Get plain text from post content HTML code and replace to content value in array --}}
		<p>{{ str_ireplace("\n", '', getplaintextintrofromhtml($top->content, 200)); }}</p>

		<?php
			$comments_count	= ForumComments::where('post_id', $top->id)->count();
			$comments_array	= ForumComments::where('post_id', $top->id)->select('id')->get()->toArray();
			$replies_count	= 0;
			foreach ($comments_array as $key => $value) {
				$replies_count	= $replies_count + ForumReply::where('comments_id', $value['id'])->count();
			}
			$comments_and_replies = $comments_count + $replies_count;
		?>
		<span class="bbs_main_look">{{ $comments_and_replies }}</span>
		<span class="bbs_main_time">{{ date("m-d G:i",strtotime($top->created_at)) }}</span>
	</li>
	@endforeach

	@foreach($items as $post)
	<li class="bbs_main_boy">
		<a href="{{ route('forum.show', $post->id) }}" target="_blank">{{ Str::limit($post->title, 35) }}</a>

		<?php
			$comments_count	= ForumComments::where('post_id', $post->id)->count();
			$comments_array	= ForumComments::where('post_id', $post->id)->select('id')->get()->toArray();
			$replies_count	= 0;
			foreach ($comments_array as $key => $value) {
				$replies_count	= $replies_count + ForumReply::where('comments_id', $value['id'])->count();
			}
			$comments_and_replies = $comments_count + $replies_count;
		?>

		{{-- Get plain text from post content HTML code and replace to content value in array --}}
		<p>{{ str_ireplace("\n", '', getplaintextintrofromhtml($post->content, 200)); }}</p>
		<span class="bbs_main_look">{{ $comments_and_replies }}</span>
		<span class="bbs_main_time">{{ date("m-d G:i",strtotime($post->created_at)) }}</span>
		<?php
			// Using expression get all picture attachments (Only with pictures stored on this server.)
			preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $post->content, $match );
			$thumbnails = join(',', array_pop($match));

			$i=0;
			foreach($match[0] as $thumbnail){
				echo '<a ' . str_replace('_src=', 'href=', $thumbnail) . ' class="fancybox" rel="gallery5"><img class="post_thumbnails" ' . str_replace('_src', 'src', $thumbnail) . ' /></a>';

			$i++;
			if($i==3) break;
			}
		?>
	</li>
	@endforeach
</ul>

{{ pagination($items->appends(Input::except('page')), 'layout.paginator') }}

<script>
	var homeuri	= "{{ route('home') }}";
</script>

{{ Minify::javascript(array('/assets/js/forum.index.item.type.js')) }}