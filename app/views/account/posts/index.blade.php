@include('account.posts.header')
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
					<li><a href="{{ route('account.notifications') }}" class="a3 fa fa-inbox">&nbsp;&nbsp;&nbsp;我的来信</a></li>
					<li><a href="{{ route('forum.index') }}" class="a3 fa fa-user">&nbsp;&nbsp;&nbsp;单身公寓</a></li>
					<li><a href="{{ route('account.posts') }}" class="a3 fa fa-flag-o active">&nbsp;&nbsp;&nbsp;我的帖子</a></li>
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
						<div class="sgnin_con">
							<div class="comeon">
								<span class="comeon_title">为爱情正能量加油</span>
								<a id="clickon" href="javascript:void(0)">加油</a>
								<div id="instr">
									<div>当你加油累积<span>10</span>天后，会得到代表(活跃用户标志)的<em>橙色昵称</em></div>
									<div>当你加油累积<span>30</span>天后，会得到代表粉丝级用户标志的<span>头像加冠</span></div>
									<div>当你加油累积<span>50</span>天后，会得到价值<span>120</span>元的公仔一个</div>
									<div>如果你加油累积到<span>50天以后</span>呢？只要你相信真爱，就会惊喜不断，让我们一起为真爱加油助威吧</div>
									<div><strong>注意：如果断签一天会扣除2天的能量值</strong></div>
								</div>
							</div>
							<div class="pillars">
								<div id="pillars_fixed">
									<div id="pillars_auto" style=" width: 0px;">
										{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
										<div>已加油<span>0</span>天</div>
									</div>
									<span class="num num1">0</span>
									<span class="num num2">25</span>
									<span class="num num3">50</span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- 资料部分 -->
				<div id="data">
					<div class="data_top clear">
						<span></span> <!--左侧粉块-->
						<p>我在论坛发过的帖子</p>
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
				</div>
			</div>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

@include('account.posts.footer')
@yield('content')