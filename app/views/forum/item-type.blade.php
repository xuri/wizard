<ul id="bbs_main_clinic" class="bbs_main bbs_main_{{ $categoryCode }}">
	@foreach($items as $post)
	<li class="bbs_main_boy">
		<a href="{{ route('forum.show', $post->id) }}" target="_blank">{{ Str::limit($post->title, 35) }}</a>

		<p>{{ close_tags(Str::limit($post->content, 200)) }}</p>
		<span class="bbs_main_look">{{ ForumComments::where('post_id', $post->id)->count() }}</span>
		<span class="bbs_main_time">{{ date("m-d H:m",strtotime($post->created_at)) }}</span>
	</li>
	@endforeach
</ul>

{{ pagination($items->appends(Input::except('page')), 'layout.paginator') }}

<script>
	var homeuri								= "{{ route('home') }}";
</script>

{{ Minify::javascript(array('/assets/js/forum.index.item.type.js')) }}