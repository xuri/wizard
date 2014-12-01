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
					<li><a href="{{ route('forum') }}" class="a3 fa fa-user">&nbsp;&nbsp;&nbsp;单身公寓</a></li>
					<li><a href="#" class="a5 fa fa-bookmark-o">&nbsp;&nbsp;&nbsp;关注我们</a></li>
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
						<p>我的消息</p>
					</div>
					<ul class="new_tab clear">
						<li class="active">我的聊天<span>99</span></li>
						<span></span>
						<li>论坛消息<span>5</span></li>
						<span></span>
						<li>系统消息<span>23</span></li>
					</ul>

					<ul id="new_main_mine" class="new_main"><!-- 我的聊天 -->
						<li>
							<a href="#">{{ HTML::image('assets/images/user_1.png', '', array('class' => 'new_main_head')) }}</a>
							{{ HTML::image('assets/images/symbol.png', '', array('class' => 'new_main_sex')) }}
							<a href="#" class="new_main_name">数据加载中阿斯达是打算打算的</a>
							<h3 class="new_main_school">黑龙江工程学院</h6>
							<span class="new_main_time">11-11 06:30</span>
							<p>干什么呢?</p>
							<span class="new_main_state unread">查看</span>
						</li>
						<li>
							<a href="#">{{ HTML::image('assets/images/user_1.png', '', array('class' => 'new_main_head')) }}</a>
							{{ HTML::image('assets/images/symbol.png', '', array('class' => 'new_main_sex')) }}
							<a href="#" class="new_main_name">数据加载中阿斯达是打算打算的</a>
							<h3 class="new_main_school">黑龙江工程学院</h6>
							<span class="new_main_time">11-11 06:30</span>
							<p>玩呢啊.</p>
							<span class="new_main_state">已读</span>
						</li>
						<li>
							<a href="#">{{ HTML::image('assets/images/user_1.png', '', array('class' => 'new_main_head')) }}</a>
							{{ HTML::image('assets/images/symbol.png', '', array('class' => 'new_main_sex')) }}
							<a href="#" class="new_main_name">数据加载中阿斯达是打算打算的</a>
							<h3 class="new_main_school">黑龙江工程学院</h6>
							<span class="new_main_time">11-11 06:30</span>
							<p>玩什么呢?</p>
							<span class="new_main_state unread">查看</span>
						</li>
						<li>
							<a href="#">{{ HTML::image('assets/images/user_1.png', '', array('class' => 'new_main_head')) }}</a>
							{{ HTML::image('assets/images/symbol.png', '', array('class' => 'new_main_sex')) }}
							<a href="#" class="new_main_name">数据加载中阿斯达是打算打算的</a>
							<h3 class="new_main_school">黑龙江工程学院</h6>
							<span class="new_main_time">11-11 06:30</span>
							<p>LOL呢啊！！！</p>
							<span class="new_main_state">已读</span>
						</li>
					</ul>

					<ul id="new_main_forum" class="new_main"><!-- 论坛消息 -->
						<div id="courtship-mine">
							<ul class="clear">
								<li class="clear"><span>39</span><p>这个里面是内容绝对的内容sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss</p><a class="date">2012-12-01 10:45</a><i class="fa fa-trash-o"></i></li>
								<li class="clear"><span>39</span><p>这个里面是内容绝对的内容</p><a class="date">2012-12-01 10:45</a><i class="fa fa-trash-o"></i></li>
								<li class="clear"><span>39</span><p>这个里面是内容绝对的内容</p><a class="date">2012-12-01 10:45</a><i class="fa fa-trash-o"></i></li>
								<li class="clear"><span>39</span><p>这个里面是内容绝对的内容</p><a class="date">2012-12-01 10:45</a><i class="fa fa-trash-o"></i></li>
								<li class="clear"><span>39</span><p>这个里面是内容绝对的内容</p><a class="date">2012-12-01 10:45</a><i class="fa fa-trash-o"></i></li>
							</ul>
						</div>
					</ul>

					<ul id="new_main_system" class="new_main"><!-- 系统消息 -->
						<li>
							{{ HTML::image('assets/images/logo3.jpg', '', array('class' => 'new_main_head')) }}
							{{ HTML::image('assets/images/login_bg.png', '', array('class' => 'new_main_sex')) }}
							<span class="new_main_name">管理员</span>
							<h6 class="new_main_school">聘爱网总部</h6>
							<span class="new_main_time">11-11 06:30</span>
							<p>您的消息未通过审核</p>
							<span class="new_main_state unread">查看</span>
						</li>
						<li>
							{{ HTML::image('assets/images/logo3.jpg', '', array('class' => 'new_main_head')) }}
							{{ HTML::image('assets/images/login_bg.png', '', array('class' => 'new_main_sex')) }}
							<span class="new_main_name">管理员</span>
							<h6 class="new_main_school">聘爱网总部</h6>
							<span class="new_main_time">11-11 06:30</span>
							<p>您的消息未通过审核</p>
							<span class="new_main_state">已读</span>
						</li>
						<li>
							{{ HTML::image('assets/images/logo3.jpg', '', array('class' => 'new_main_head')) }}
							{{ HTML::image('assets/images/login_bg.png', '', array('class' => 'new_main_sex')) }}
							<span class="new_main_name">管理员</span>
							<h6 class="new_main_school">聘爱网总部</h6>
							<span class="new_main_time">11-11 06:30</span>
							<p>您的消息未通过审核</p>
							<span class="new_main_state unread">查看</span>
						</li>
						<li>
							{{ HTML::image('assets/images/logo3.jpg', '', array('class' => 'new_main_head')) }}
							{{ HTML::image('assets/images/login_bg.png', '', array('class' => 'new_main_sex')) }}
							<span class="new_main_name">管理员</span>
							<h6 class="new_main_school">聘爱网总部</h6>
							<span class="new_main_time">11-11 06:30</span>
							<p>您的消息未通过审核</p>
							<span class="new_main_state">已读</span>
						</li>
					</ul>

				</div>

			</div>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

@include('account.notifications.footer')
@yield('content')