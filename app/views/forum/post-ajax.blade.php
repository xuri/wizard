<div id="post-ajax">
	<div class="clear guest" style="width;100%; border:1px solid #ededed; border-radius:5px;" id="g-list">

		@foreach($comments as $comment)
		<?php
			$user = User::where('id', $comment->user_id)->first(); // Retrieve comment user profile
		?>
		<div class="message-re clear">
			<div class="re-headImg-box">
				<div class="re-headImg">
					<a href="{{ route('members.show', $user->id) }}">
						@if($user->portrait)
						{{ HTML::image('portrait/'.$user->portrait) }}
						@else
						{{ HTML::image('assets/images/preInfoEdit/peo.png') }}
						@endif
					</a>
				</div>
				<a href="{{ route('members.show', $user->id) }}">
					@if($user->sex == 'M')
					{{ HTML::image('assets/images/symbol.png', '', array('class' => 'lu_left sexImg')) }}
					@else
					{{ HTML::image('assets/images/g.jpg', '', array('class' => 'lu_left sexImg')) }}
					@endif
				</a>
				<a href="{{ route('members.show', $user->id) }}" class="m-h3">{{ $user->nickname }}</a>
			</div>
			<p class="g-reply">{{ $comment->content }}</p>

			<ul class="reply">
				<li><a href="{{ route('support.index') }}" class="a-color-grey">举报</a></li>
				<li>{{ $floor ++ }}楼</li>
				<li>{{ date("Y-m-d H:m",strtotime($comment->created_at)) }}</li>
				<li><a href="javascript:void(0);" class="a-color-pink reply_comment">回复</a></li>
			</ul>
			<section class="form_box_first">
				{{ Form::open(array(
					'autocomplete'	=> 'off',
					'class'			=> 'reply_comment_form',
					))
				}}
				<textarea class="reply_comment_textarea" id="reply_id_{{ $comment->id }}" name="reply_content">{{ Input::old('content', '回复 '.$user->nickname.':') }}</textarea>
				{{ Form::button('发表', array('class' => 'reply_comment_submit', 'data-nickname' => $user->nickname, 'data-comment-id' => $comment->id, 'data-reply-id' => $user->id)) }}
				{{ Form::close() }}
			</section>
			<div class="message-other">
				<div class="o-others">
					<?php
						$replies = ForumReply::where('comments_id', $comment->id)->get();
					?>
					@foreach($replies as $reply)
					<?php
						$reply_user = User::where('id', $reply->user_id)->first();
					?>
					<div>
						<span class="imgSpan">
							<a href="{{ route('members.show', $reply_user->id) }}">
								@if($reply_user->portrait)
								{{ HTML::image('portrait/'.$reply_user->portrait) }}
								@else
								{{ HTML::image('assets/images/preInfoEdit/peo.png') }}
								@endif
							</a>
						</span>
						@if($reply_user->sex == 'M')
						{{ HTML::image('assets/images/symbol.png', '', array('class' => 'o-sexImg')) }}
						@else
						{{ HTML::image('assets/images/g.jpg', '', array('class' => 'o-sexImg')) }}
						@endif
						<a href="{{ route('members.show', $reply_user->id) }}" target="_blank" class="g-h3">{{ $reply_user->nickname }}:</a>
						<p class="r-value">{{ $reply->content }}</p>
						<a class="replay-a reply_inner">回复</a>
						<p class="date">{{ date("Y-m-d H:m",strtotime($reply->created_at)) }}</p>

						<section class="form_box_second">
							{{ Form::open(array(
								'autocomplete'	=> 'off',
								'class'			=> 'reply_inner_form'
								))
							}}
							<textarea class="textarea" name="reply_content" id="reply_id_{{ $reply->id }}">{{ Input::old('content', '回复 '.$reply_user->nickname.':') }}</textarea>
							{{ Form::button('回复', array('class' => 'submit', 'data-nickname' => $reply_user->nickname, 'data-comment-id' => $comment->id, 'data-reply-id' => $reply->id)) }}
							{{ Form::close() }}
						</section>
						<span class="span-line"></span>
					</div>
					@endforeach
				</div>
			</div>
		</div>
		@endforeach
	</div>

	{{ pagination($comments->appends(Input::except('page')), 'layout.paginator') }}
</div>
{{ HTML::script('assets/js/forum-post.js') }}