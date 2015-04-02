@include('account.posts.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="content" class="clear">
		<div class="con_title">{{ Lang::get('navigation.my_posts') }}</div>
		<div class="con_img">
			<span class="line1"></span>
			<span class="line2"></span>
			{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
		</div>

		<div id="wrap" class="clear">
			<div class="w_left">
				<ul class="w_nav"><li><a href="{{ route('account') }}" class="a1 fa fa-tasks">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.profile') }}</a></li>
					<li><a href="{{ route('account.sent') }}" class="a2 fa fa-heart-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.friends') }}</a></li>
					<li><a href="{{ route('account.inbox') }}" class="a2 fa fa-star">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.followers') }}</a></li>
					<li><a href="{{ route('account.notifications') }}" class="a3 fa fa-inbox">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.inbox') }}</a></li>
					<li><a href="{{ route('account.posts') }}" class="active a3 fa fa-flag-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.my_posts') }}</a></li>
					<li><a href="{{ route('support.index') }}" class="a5 fa fa-life-ring">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.support') }}</a></li>
					<li><a href="{{ route('home') }}/article/about.html" class="a5 fa fa-bookmark-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.about') }}</a></li>
				</ul>
				@include('account.qrcode')
				@yield('content')
			</div>
			<div class="w_right">
				<div class="clear">
					<div class="img">
						@if(Auth::user()->portrait)
						{{ HTML::image('portrait/'.Auth::user()->portrait) }}
						@else
						{{ HTML::image('assets/images/preInfoEdit/peo.png') }}
						@endif
					</div>
					<div class="sgnin">
						@if ($message = Session::get('success'))
						<div class="sgnin_top" style="margin:0 0 10px 0">
							<div>
								<span>
									<a href="javascript:;" style="color: #297fb8;">&times;</a>
									{{ $message }}
								</span>
							</div>
						</div>
						@endif

						@include('account.points')
						@yield('content')
					</div>
				</div>

				{{-- Data Section --}}
				<div id="data">
					<div class="data_top clear">
						<span></span> {{-- Left border --}}
						<p>{{ Lang::get('navigation.my_posts') }}</p>
					</div>
					<div id="load-ajax">
						<ul id="new_main_forum" class="new_main">
							<div id="if_error"></div>
							<div id="courtship-mine">
								<ul class="clear">
									@foreach($posts as $post)
									<?php
										$comments = ForumComments::where('post_id', $post->id)->count();
									?>
									<li class="clear">
										<a href="{{ route('forum.show', $post->id) }}" class="comments-count" title="这条帖子有{{ $comments }}条回复。" alt="这条帖子有{{ $comments }}条回复。">
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
				</div>
			</div>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

@include('account.posts.footer')
@yield('content')