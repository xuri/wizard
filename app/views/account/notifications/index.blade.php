@include('account.notifications.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="content" class="clear">
		<div class="con_title">{{ Lang::get('navigation.inbox') }}</div>
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
					<li><a href="{{ route('account.notifications') }}" class="active a3 fa fa-inbox">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.inbox') }}</a></li>
					<li><a href="{{ route('account.posts') }}" class="a3 fa fa-flag-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.my_posts') }}</a></li>
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
							@if(File::exists('portrait/'.Auth::user()->portrait) && File::size('portrait/' . Auth::user()->portrait) > 0)
								{{ HTML::image('portrait/'.Auth::user()->portrait) }}
							@else
								{{ HTML::image('assets/images/preInfoEdit/peo.png') }}
							@endif
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
						<span></span> {{-- Left Border --}}
						<p>{{ Lang::get('navigation.inbox') }}</p>
					</div>
					<ul class="new_tab clear">
						<li class="active">
							{{ Lang::get('account/notifications.friend_notifications') }}
							@if($friendNotificationsCount != 0)
								<span>{{ $friendNotificationsCount }}</span>
							@endif
						</li>
						<span></span>
						<li>
							{{ Lang::get('account/notifications.forum_notifications') }}
							@if($forumNotificationsCount != 0)
								<span>{{ $forumNotificationsCount }}</span>
							@endif
						</li>
						<span></span>
						<li>
							{{ Lang::get('account/notifications.system_notifications') }}
							@if($systemNotificationsCount != 0)
								<span>{{ $systemNotificationsCount }}</span>
							@endif
						</li>
					</ul>

					<ul id="new_main_mine" class="new_main">
						{{-- Friend request notifications --}}
						<div id="first_inner"></div>

					</ul>

					<ul id="new_main_forum" class="new_main">
						{{-- Forum notofications --}}
						<div id="second_inner"></div>
					</ul>

					<ul id="new_main_system" class="new_main">
						{{-- System notifications --}}
						<div id="third_inner"></div>
					</ul>

				</div>

			</div>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

@include('account.notifications.footer')
@yield('content')