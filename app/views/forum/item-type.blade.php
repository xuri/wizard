<ul id="bbs_main_clinic" class="bbs_main bbs_main_{{ $categoryCode }}">
	@foreach($tops as $top)
	<li class="bbs_main_boy">
		<span class="tops">{{ Lang::get('forum/index.top') }}</span>
		<a href="{{ route('forum.show', $top->id) }}" target="_blank">{{ badWordsFilter(Str::limit($top->title, 35)) }}</a>

		{{-- Get plain text from post content HTML code and replace to content value in array --}}
		<p>{{ badWordsFilter(str_ireplace("\n", '', getplaintextintrofromhtml($top->content, 200))) }}</p>

		<?php
			$comments_count	= ForumComments::where('post_id', $top->id)
								->where('block', false)
								->count();
			$comments_array	= ForumComments::where('post_id', $top->id)
								->where('block', false)
								->select('id')
								->get()
								->toArray();
			$replies_count	= 0;
			foreach ($comments_array as $key => $value) {
				$replies_count	= $replies_count + ForumReply::where('comments_id', $value['id'])->where('block', false)->count();
			}
			$comments_and_replies = $comments_count + $replies_count;
		?>
		<span class="bbs_main_look">{{ badWordsFilter($comments_and_replies) }}</span>
		<span class="bbs_main_time">{{ date("m-d G:i",strtotime($top->created_at)) }}</span>
		<?php
			// Using expression get all picture attachments (Only with pictures stored on this server.)
			preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $top->content, $top_match );
			$top_thumbnails = join(',', array_pop($top_match));

			$top_i = 0;
			foreach($top_match[0] as $top_thumbnail){

				// Recovery origional file path
				$top_thumbnail_file_path	= substr(str_replace('_src="' . route('home') . '/', '', $top_thumbnail), 0, -1);

				// Get file size to determin blank or broken file
				$top_thumbnail_bytes		= File::size($top_thumbnail_file_path);

				// Determin file exists
				if(File::exists($top_thumbnail_file_path) && $top_thumbnail_bytes > 0) {
					// Image exists can be display
					echo '<a ' . str_replace('_src=', 'href=', $top_thumbnail) . ' class="fancybox" rel="gallery5"><img class="post_thumbnails" ' . str_replace('_src', 'src', $top_thumbnail) . ' /></a>';
				}
			$top_i ++;
			if($top_i == 3) break;
			}
		?>
	</li>
	@endforeach

	@foreach($items as $post)
	<li class="bbs_main_boy">
		<a href="{{ route('forum.show', $post->id) }}" target="_blank">{{ badWordsFilter(Str::limit($post->title, 35)) }}</a>

		<?php
			$comments_count	= ForumComments::where('post_id', $post->id)
								->where('block', false)
								->count();
			$comments_array	= ForumComments::where('post_id', $post->id)
								->where('block', false)
								->select('id')
								->get()
								->toArray();
			$replies_count	= 0;
			foreach ($comments_array as $key => $value) {
				$replies_count	= $replies_count + ForumReply::where('comments_id', $value['id'])->count();
			}
			$comments_and_replies = $comments_count + $replies_count;
		?>

		{{-- Get plain text from post content HTML code and replace to content value in array --}}
		<p>{{ badWordsFilter(str_ireplace("\n", '', getplaintextintrofromhtml($post->content, 200))) }}</p>
		<span class="bbs_main_look">{{ badWordsFilter($comments_and_replies) }}</span>
		<span class="bbs_main_time">{{ date("m-d G:i",strtotime($post->created_at)) }}</span>
		<?php
			// Using expression get all picture attachments (Only with pictures stored on this server.)
			preg_match_all( '@_src="(' . route('home') . '/upload/image[^"]+)"@' , $post->content, $match );
			$thumbnails = join(',', array_pop($match));

			$i = 0;
			foreach($match[0] as $thumbnail) {

				// Recovery origional file path
				$thumbnail_file_path = substr(str_replace('_src="' . route('home') . '/', '', $thumbnail), 0, -1);

				// Get file size to determin blank or broken file
				$thumbnail_bytes	 = File::size($thumbnail_file_path);

				// Determin file exists
				if(File::exists($thumbnail_file_path) && $thumbnail_file_path > 0) {
					echo '<a ' . str_replace('_src=', 'href=', $thumbnail) . ' class="fancybox" rel="gallery5"><img class="post_thumbnails" ' . str_replace('_src', 'src', $thumbnail) . ' /></a>';
				}
			$i ++;
			if($i == 3) break;
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