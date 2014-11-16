@include('account.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="content" class="clear">
		<div class="con_title">个人中心</div>
		<div class="con_img">
			<span class="line1"></span>
			<span class="line2"></span>
			{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
		</div>

		<div id="wrap" class="clear">
			<div class="w_left">
				<ul class="w_nav">
					<li><a href="{{ route('account') }}" class="active a1">我的资料</a></li>
					<li><a href="{{ route('account.sent') }}" class="a2">我追的人</a></li>
					<li><a href="#" class="a3">我的来信</a></li>
					<li><a href="#" class="a4">我的关注</a></li>
					<li><a href="#" class="a5">关注我们</a></li>
				</ul>
				<div id="download">
					<div>安卓APP</div>
					{{ HTML::image('assets/images/preInfoEdit/app.png') }}
				</div>
			</div>
			<div class="w_right">
				<div class="clear">
					<div class="img">
						{{ HTML::image('assets/images/preInfoEdit/peo.png') }}
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


				{{-- Profile  --}}
				<div id="data">
					<a href="{{ route('account.complete') }}" class="editor">点击编辑</a>
					<div class="data_top clear">
						<span></span> {{-- Left pink section --}}
						<p>我的资料</p>
					</div>
					<table>
						<tr>
							<td class="data_td1">昵称：</td><td class="data_td2">敏感的阳</td>
						</tr>
						<tr>
							<td class="data_td1">性别：</td><td class="data_td2">
								{{ HTML::image('assets/images/symbol.png') }}
								{{ HTML::image('assets/images/g.jpg') }}
							</td>
						</tr>
						<tr>
							<td class="data_td1">出生年：</td><td class="data_td2">1993</td>
						</tr>
						<tr>
							<td class="data_td1">学校：</td><td class="data_td2">哈工大</td>
						</tr>
						<tr>
							<td class="data_td1">入学年：</td><td class="data_td2">2012</td>
						</tr>
						<tr>
							<td class="data_td1">星座：</td><td class="data_td2 constellation">
								{{ HTML::image('assets/images/preInfoEdit/constellation/baiyang.png', '', array('width' => '30', 'height' => '30')) }}
								<span style="margin-left:50px;">白羊座</span></td>
						</tr>
						<tr>
							<td class="data_td1">性格：</td>
							<td class="data_td2 character">
								<span>冷酷</span>
								<span>冷酷</span>
								<span>冷酷</span>
								<span>冷酷</span>
								<span>冷酷</span>
								<span>冷酷</span>
								<span>冷酷</span>
								<span>冷酷</span>
							</td>
						</tr>
						<tr>
							<td class="data_td1 vertical_c">爱好：</td><td class="data_td2 vertical_c">打篮球，洗澡，飞</td>
						</tr>
						<tr>
							<td class="data_td1">个人简介：</td><td class="data_td2">我是很牛B的人，PHP全省第一，IOS开发全省第一，美工全省
                 第一等。就不多说了，要低调，其实我只是为了凑字而已，高中之后很
  		  为了写作文凑字了。这回字够了</td>
						</tr>
						<tr class="end_tr">
							<td class="data_td1">真爱寄语：</td><td class="data_td2">真爱永恒</td>
						</tr>
						<tr class="love_problem">
							<td class="data_td1">爱情考验：</td><td class="data_td2">你在乎我不是一个有钱的男生么？</td>
						</tr>
					</table>
				</div>

			</div>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

@include('account.footer')
@yield('content')