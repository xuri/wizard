<div id="load-ajax">
	<ul id="new_main_forum" class="new_main">
		<div id="if_error"></div>
		<div id="courtship-mine">
			<ul class="clear" id="post-ajax">
				@foreach($posts as $post)
				<?php
					$comments = ForumComments::where('post_id', $post->id)->count();
				?>
				<li class="clear">
					<a href="{{ route('forum.show', $post->id) }}" title="这条帖子有{{ $comments }}条回复。" alt="这条帖子有{{ $comments }}条回复。">
						<span>{{ $comments }}</span>
					</a>
					<a href="{{ route('forum.show', $post->id) }}" title="查看我的帖子：{{ $post->title }}" alt="查看我的帖子：{{ $post->title }}">
						<p>
							{{ $post->title }}
						</p>
					</a>
					<a class="date">{{ date('Y年m月d日 H:m',strtotime($post->created_at)) }}</a>
					<a href="javascript:void(0);" class="delete-message" data-post-id="{{ $post->id }}"><i class="fa fa-trash-o"></i></a>
				</li>
				@endforeach
			</ul>
		</div>
		{{ pagination($posts->appends(Input::except('page')), 'layout.paginator') }}
	</ul>
</div>