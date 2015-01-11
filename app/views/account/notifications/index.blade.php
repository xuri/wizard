@include('account.notifications.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="content" class="clear">
		<div class="con_title">我的来信</div>
		<div class="con_img">
			<span class="line1"></span>
			<span class="line2"></span>
			{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
		</div>

		<div id="wrap" class="clear">
			<div class="w_left">
				<ul class="w_nav">
					<li><a href="{{ route('account') }}" class="a1 fa fa-tasks">&nbsp;&nbsp;&nbsp;我的资料</a></li>
					<li><a href="{{ route('members.index') }}" class="a1 fa fa-users">&nbsp;&nbsp;&nbsp;缘来在这</a></li>
					<li><a href="{{ route('account.sent') }}" class="a2 fa fa-heart-o">&nbsp;&nbsp;&nbsp;我追的人</a></li>
					<li><a href="{{ route('account.inbox') }}" class="a2 fa fa-star">&nbsp;&nbsp;&nbsp;追我的人</a></li>
					<li><a href="{{ route('account.notifications') }}" class="active a3 fa fa-inbox">&nbsp;&nbsp;&nbsp;我的来信</a></li>
					<li><a href="{{ route('forum.index') }}" class="a3 fa fa-user">&nbsp;&nbsp;&nbsp;单身公寓</a></li>
					<li><a href="{{ route('account.posts') }}" class="a3 fa fa-flag-o">&nbsp;&nbsp;&nbsp;我的帖子</a></li>
					<li><a href="{{ route('support.index') }}" class="a5 fa fa-life-ring">&nbsp;&nbsp;&nbsp;联系客服</a></li>
					<li><a href="{{ route('home') }}" class="a5 fa fa-bookmark">&nbsp;&nbsp;&nbsp;关于我们</a></li>
				</ul>
				<div id="download">
					<div>移动客户端下载</div>
					{{ HTML::image('assets/images/preInfoEdit/app.png') }}
				</div>
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
						<div class="sgnin_top">
							<div><span>昵称 : </span>{{ Auth::user()->nickname }}</div>
							<div><span>精灵豆 : </span><em>{{ Auth::user()->points }}</em><strong>　(每天为爱情正能量加油可以获取精灵豆哦)</strong></div>
						</div>
						@include('account.points')
						@yield('content')
					</div>
				</div>

				<!-- 资料部分 -->
				<div id="data">
					<div class="data_top clear">
						<span></span> <!--左侧粉块-->
						<p>我的消息</p>
					</div>
					<ul class="new_tab clear">
						<li class="active">好友消息<span>{{ $friendNotificationsCount }}</span></li>
						<span></span>
						<li>论坛消息<span>{{ $forumNotificationsCount }}</span></li>
						<span></span>
						<li>系统消息<span>{{ $systemNotificationsCount }}</span></li>
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