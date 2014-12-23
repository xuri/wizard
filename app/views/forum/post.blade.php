@include('forum.post-header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="lu_content">
		<div class="lu_con_title">单身公寓</div>
		<div class="lu_con_img">
			<span class="lu_line1"></span>
			<span class="lu_line2"></span>
			{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
		</div>
		<div class="lu_content_box clear">

			@if ($message = Session::get('success'))
				<div class="callout-warning">{{ $message }}</div>
			@endif

			{{ $errors->first('reply_content', '<div class="callout-warning">:message</div>') }}

			<div class="lu_content_main clear">
				<div class="message-re message-border clear">
					<div class="re-headImg-box">
						<div class="re-headImg">
							{{ HTML::image('portrait/'.$author->portrait) }}
						</div>
						@if($author->sex == 'M')
						{{ HTML::image('assets/images/symbol.png', '', array('class' => 'lu_left sexImg')) }}
						@else
						{{ HTML::image('assets/images/g.jpg', '', array('class' => 'lu_left sexImg')) }}
						@endif
						<a href="{{ route('members.show', $author->id) }}" class="m-h3">{{ $author->nickname }}</a>
					</div>
					<h3 class="re-title">{{ $data->title }}</h3>
					<p class="m-reply">{{ $data->content }}</p>

					<ul class="reply">
						<li><a href="#" class="a-color-grey">举报</a></li>
						<li>1楼</li>
						<li>{{ date("Y-m-d H:m",strtotime($data->created_at)) }}</li>
						<li><a href="#create_comment" class="a-color-pink smooth">回复</a></li>
					</ul>

				</div>
				<div class="clear guest" style="width;100%; border:1px solid #ededed; border-radius:5px;" id="g-list">

					@foreach($comments as $comment)
					<?php
						$user = User::where('id', $comment->user_id)->first(); // Retrieve comment user profile
					?>
					<div class="message-re clear">
						<div class="re-headImg-box">
							<div class="re-headImg">
								{{ HTML::image('portrait/'.$user->portrait) }}
							</div>
							@if($user->sex == 'M')
							{{ HTML::image('assets/images/symbol.png', '', array('class' => 'lu_left sexImg')) }}
							@else
							{{ HTML::image('assets/images/g.jpg', '', array('class' => 'lu_left sexImg')) }}
							@endif
							<a href="{{ route('members.show', $comment->user_id) }}" class="m-h3">{{ $user->nickname }}</a>
						</div>
						<p class="g-reply">{{ $comment->content }}</p>

						<ul class="reply">
							<li><a href="#" class="a-color-grey">举报</a></li>
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
							<input type="hidden" name="type" value="reply">
							<input type="hidden" name="comments_id" value="{{ $comment->id }}">
							<input type="hidden" name="reply_id" value="{{ $user->id }}">
							<textarea class="reply_comment_textarea" name="reply_content">{{ Input::old('content', '回复 '.$user->nickname.':') }}</textarea>
							<input type="submit" value="发表" class="reply_comment_submit" />
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
										{{ HTML::image('portrait/'.$reply_user->portrait) }}
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
										<input type="hidden" name="type" value="reply">
										<input type="hidden" name="comments_id" value="{{ $comment->id }}">
										<input type="hidden" name="reply_id" value="{{ $reply_user->id }}">
										<textarea class="textarea" name="reply_content">{{ Input::old('content', '回复 '.$reply_user->nickname.':') }}</textarea>
										<input value="发表" class="submit" type="submit">
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
				<div class="lu_paging">
					<span>上一页</span>
					<a class="lu_active">1</a>
					<a>2</a>
					<a>3</a>
					<a>4</a>
					<span>下一页</span>
				</div>

				<div class="g-box clear" id="create_comment">
					<a class="color">发表评论</a>
					{{ $errors->first('content', '<div class="callout-warning">:message</div>') }}
					<div class="g-r-box clear" class="clear">
						{{ Form::open(array(
							'autocomplete' 	=> 'off'
							))
						}}
							<input type="hidden" name="type" value="comments">
							{{ Umeditor::css() }}
							{{ Umeditor::content(Input::old('content'), ['id'=>'create_comment_editor', 'class'=>'g-r-value', 'name' => 'content', 'height' => '220']) }}
							{{ Umeditor::js() }}
							{{ Form::submit('发表', array('class' => 'g-replay', 'id' => 'g-replay')) }}
						{{ Form::close() }}
					</div>
				</div>
			</div>

			<div class="lu_content_right">
				{{ HTML::image('assets/images/user_1.png', '', array('class' => 'lu_img')) }}
				<div class="lu_up">
					<p class="lu_te lu_name lu_left">{{ HTML::image('assets/images/symbol.png') }}夏米斯丁艾合麦提·阿布都米吉提</p>
					<p class="lu_bin lu_left">精灵豆：<a>60</a></p>
				</div>
			</div>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

@include('forum.post-footer')
@yield('content')