@include('members.show-header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="content">
		<div class="con_title">个人中心</div>
		<div class="con_img">
			<span class="line1"></span>
			<span class="line2"></span>
			{{ HTML::image('assets/images/hert.png') }}

		</div>

		<div id="wrap" class="clear">
			<div class="w_left">
				<ul class="w_nav">
					<li><a href="#" class="active a1">我的资料</a></li>
					<li><a href="#" class="a2">我追的人</a></li>
					<li><a href="#" class="a3">我的来信</a></li>
					<li><a href="#" class="a4">我的关注</a></li>
					<li><a href="#" class="a5">关注我们</a></li>
				</ul>
				<div id="download">
					<div>安卓APP</div>
					{{ HTML::image('assets/images/app.png') }}

				</div>
			</div>
			<div class="w_right">
				<div class="clear">
					<div class="img">
						{{ HTML::image('assets/images/peo.png') }}

					</div>
					<div class="sgnin">
						<div class="sgnin_top">
							<div><span>昵称 : </span>敏感的阳</div>
							<div><span>精灵豆 : </span><em>30</em><strong>　(每天为爱情正能量加油可以获取精灵豆哦)</strong></div>
						</div>
						<div class="sgnin_con">
							<div class="comeon">
								<span class="comeon_title">为爱情正能量加油</span>
								<a id="clickon" href="javascript:;">加油</a>
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
										{{ HTML::image('assets/images/hert.png') }}

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

				<div id="focuson_me" class="clear">
					<div class="focuson_title">关注我的 : </div>
					<div class="focuson_list">
						{{ HTML::image('assets/images/fome.png') }}

						{{ HTML::image('assets/images/fome.png') }}

						{{ HTML::image('assets/images/fome.png') }}

						{{ HTML::image('assets/images/fome.png') }}

						{{ HTML::image('assets/images/fome.png') }}

						{{ HTML::image('assets/images/fome.png') }}

						{{ HTML::image('assets/images/fome.png') }}

						{{ HTML::image('assets/images/fome.png') }}

						<a href="#">...查看更多</a>
					</div>
				</div>


			</div>
		</div>
	</div>
</body>
	{{ HTML::script('assets/js/jingling.js') }}

	{{ HTML::script('assets/js/members-main.js') }}

</html>