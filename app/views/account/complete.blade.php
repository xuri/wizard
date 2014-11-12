@include('account.complete-header')
@yield('content')

	{{-- Mask --}}
	<div id="mask"></div>
	<div id="checkbg">
		<a id="check_close" href="javascript:;">×</a>
		<a class="once" href="javascript:;" id="./images/preInfoEdit/boy/">
			{{ HTML::image('assets/images/preInfoEdit/bgcolor/boy.png') }}
		</a>
		<a class="once" href="javascript:;" id="./images/preInfoEdit/girl/">
			{{ HTML::image('assets/images/preInfoEdit/bgcolor/girl.png') }}
		</a>
	</div>
	{{-- Avatar --}}
	<div id="pre_content">
		<a id="pre_close" href="javascript:;">×</a>
		<canvas id="pre_pic_wrap" width="180" height="220">你的狗逼浏览器不行</canvas>
		<div id="pre_pic_list">
		</div>
		<div id="pre_btn_list">
			<div class="btn_img" title="head">
				{{ HTML::image('assets/images/preInfoEdit/boy/head/btn.png', '', array('title' => 'head')) }}
			</div>
			<div class="btn_img" title="eyebrows">
				{{ HTML::image('assets/images/preInfoEdit/boy/eyebrows/btn.png', '', array('title' => 'eyebrows')) }}
			</div>
			<div class="btn_img" title="ears">
				{{ HTML::image('assets/images/preInfoEdit/boy/ears/btn.png', '', array('title' => 'ears')) }}
			</div>
			<div class="btn_img" title="eyes">
				{{ HTML::image('assets/images/preInfoEdit/boy/eyes/btn.png', '', array('title' => 'eyes')) }}
			</div>
			<div class="btn_img" title="nose">
				{{ HTML::image('assets/images/preInfoEdit/boy/nose/btn.png', '', array('title' => 'nose')) }}
			</div>
			<div class="btn_img" title="mouth">
				{{ HTML::image('assets/images/preInfoEdit/boy/mouth/btn.png', '', array('title' => 'mouth')) }}
			</div>
			<div class="btn_img" title="hair">
				{{ HTML::image('assets/images/preInfoEdit/boy/hair/btn.png', '', array('title' => 'hair')) }}
			</div>
			<div class="btn_img" title="bgcolor">
				{{ HTML::image('assets/images/preInfoEdit/bgcolor/btn.png', '', array('title' => 'bgcolor')) }}
			</div>
		</div>
		<a href="javascript:;" id="save_pic">保存</a>
	</div>
	{{-- end Avatar --}}
	{{-- Choose school --}}
	<div class="vs-Popup" id="vote-school">
		<div class="vs-Popup-pass" id="vs-pass">×</div>
		<div class="vs-box clear">
			<div class="vs-Popup-school">选择学校</div>
			<div class="vs-search">搜索：<input type="text"/></div>
			<div class="vs-shcoollist clear">
				<a href="#">齐齐哈尔</a>
				<a href="#">齐齐哈尔</a>
				<a href="#">齐齐哈尔</a>
				<a href="#">齐齐哈尔</a>
				<a href="#">齐齐哈尔</a>
				<a href="#">齐齐哈尔</a>
			</div>
			<span class="vs-line-bottom"></span>
			<div class="vs-school clear">
				<a href="#">哈尔滨理工大学</a>
				<a href="#">哈尔滨理工大学</a>
				<a href="#">哈尔滨理工大学</a>
				<a href="#">哈尔滨理工大学</a>
				<a href="#">哈尔滨理工大学</a>
				<a href="#">哈尔滨理工大学</a>
			</div>
		</div>
	</div>
	{{-- end Choose school --}}

	{{-- 选择星座 --}}
	<div class="con-Popup" id="con-Popup">
		<div class="con-Popup-pass" id="con-pass">×</div>
		<div class="con-box clear">
			<div class="con-Popup-school">选择星座</div>
			<ul class="con-Constellation">
				<li>
					{{ HTML::image('assets/images/preInfoEdit/constellation/baiyang.png') }}
					<p class="con-name">白羊座</p>
					<p class="con-date">3.21~4.19</p>
				</li>
				<li>
					{{ HTML::image('assets/images/preInfoEdit/constellation/jinniu.png') }}
					<p class="con-name">金牛座</p>
					<p class="con-date">4.20~5.20</p>
				</li>
				<li>
					{{ HTML::image('assets/images/preInfoEdit/constellation/shuangzi.png') }}
					<p class="con-name">双子座</p>
					<p class="con-date">5.21~6.21</p>
				</li>
				<li>
					{{ HTML::image('assets/images/preInfoEdit/constellation/juxie.png') }}
					<p class="con-name">巨蟹座</p>
					<p class="con-date">6.22~7.22</p>
				</li>
				<li>
					{{ HTML::image('assets/images/preInfoEdit/constellation/shizi.png') }}
					<p class="con-name">狮子座</p>
					<p class="con-date">7.23~8.22</p>
				</li>
				<li>
					{{ HTML::image('assets/images/preInfoEdit/constellation/chunv.png') }}
					<p class="con-name">处女座</p>
					<p class="con-date">8.23~9.22</p>
				</li>
				<li>
					{{ HTML::image('assets/images/preInfoEdit/constellation/tiancheng.png') }}
					<p class="con-name">天秤座</p>
					<p class="con-date">9.23~10.23</p>
				</li>
				<li>
					{{ HTML::image('assets/images/preInfoEdit/constellation/tianxie.png') }}
					<p class="con-name">天蝎座</p>
					<p class="con-date">10.24~11.22</p>
				</li>
				<li>
					{{ HTML::image('assets/images/preInfoEdit/constellation/sheshou.png') }}
					<p class="con-name">射手座</p>
					<p class="con-date">11.23~12.21</p>
				</li>
				<li>
					{{ HTML::image('assets/images/preInfoEdit/constellation/mojie.png') }}
					<p class="con-name">摩羯座</p>
					<p class="con-date">12.22~1.19</p>
				</li>
				<li>
					{{ HTML::image('assets/images/preInfoEdit/constellation/shuipin.png') }}
					<p class="con-name">水瓶座</p>
					<p class="con-date">1.20~2.18</p>
				</li>
				<li>
					{{ HTML::image('assets/images/preInfoEdit/constellation/shuangyu.png') }}
					<p class="con-name">双鱼座</p>
					<p class="con-date">2.19~3.20</p>
				</li>
			</ul>
		</div>
	</div>
	{{-- 选择星座结束 --}}

	{{-- 选择标签弹窗 --}}
	<div class="tag-Popup" id="tag-Popup">
		<div class="con-Popup-pass" id="tag-pass">×</div>
		<div class="tag-box clear">
			<div class="tag-Popup-school">选择标签</div>
			<ul class="tag-list" id="tag-list-r">
				<li class="red">吃东西
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li class="red">吃东西
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li class="red">吃东西
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li class="red">吃东西
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
			</ul>
			<ul class="tag-list" id="tag-list-b">
				<li class="blue">冷酷
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li class="blue">冷酷
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li class="blue">冷酷
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li class="blue">冷酷
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
			</ul>
			<ul class="tag-list" id="tag-list-g">
				<li class="green">T暖男
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li class="green">T暖男
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li class="green">T暖男
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li class="green">T暖男
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
			</ul>
			<ul class="tag-list" id="tag-list-y">
				<li class="yellow">女神控
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li class="yellow">女神控
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li class="yellow">女神控
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li class="yellow">女神控
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
			</ul>
		</div>
		<input type="submit" class="tag-submit" value="完成"/>
	</div>
	{{-- 选择标签弹窗结束 --}}

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
					<li><a href="#" class="active a1">我的资料</a></li>
					<li><a href="#" class="a2">我追的人</a></li>
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
						<div id="change_photo">修改头像</div>
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



				{{-- 资料部分 --}}
				<div id="data">
					<a href="{{ route('account') }}" class="editor">取消</a>
					<div class="data_top clear">
						<span></span>{{-- Left pink section --}}
						<p>我的资料</p>
					</div>
					<form action="#" method="post" id="edi_form">
						<table>
							<tr>
								<td class="data_td1">昵称：</td><td class="data_td2">
									<input type="text" value="" placeholder="请输入你的昵称"/>
								</td>
							</tr>
							<tr>
								<td class="data_td1">性别：</td>
								<td class="data_td2">
									<select name='sex'>
										<option value="0">男</option>
										<option value="1">女</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="data_td1">出生年：</td>
								<td class="data_td2">
									<select name='year'>
										<option value="1990">1990</option>
										<option value="1991">1991</option>
										<option value="1992">1992</option>
										<option value="1993">1993</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="data_td1">学校：</td>
								<td class="data_td2">
									<span type="text" id="check_school">请选择学校</span>
								</td>
							</tr>
							<tr>
								<td class="data_td1">入学年：</td>
								<td class="data_td2">
									<select name='inyear'>
										<option value="2011">2011</option>
										<option value="2012">2012</option>
										<option value="2013">2013</option>
										<option value="2014">2014</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="data_td1">星座：</td><td class="data_td2 constellation">
								{{ HTML::image('assets/images/preInfoEdit/constellation/baiyang.png', '', array('width' => '30', 'height' => '30', 'class' => 'constellation_img')) }}
								<span style="margin-left:50px;" id="check_constellation">白羊座</span></td>
							</tr>
							<tr>
								<td class="data_td1 vertical_top">性格：</td>
								<td class="data_td2 character">
									<span>冷酷<em>×</em></span>
									<span>冷酷<em>×</em></span>
									<span>冷酷<em>×</em></span>
									<span>冷酷<em>×</em></span>
									<span>冷酷<em>×</em></span>
									<span>冷酷<em>×</em></span>
									<span>冷酷<em>×</em></span>
									<span>冷酷<em>×</em></span>
									<span class="end" id="check_tag"><b>+</b>  标签 </span>
								</td>
							</tr>
							<tr>
								<td class="data_td1">爱好：</td><td class="data_td2">
									<input class="lang" type="text" placeholder="把你的爱好告诉大家吧" />
								</td>
							</tr>
							<tr>
								<td class="data_td1 vertical_top">个人简介：</td><td class="data_td2 vertical_top">
									<textarea rows="4" placeholder="这是推销你自己的好机会"></textarea>
								</td>
							</tr>
							<tr class="end_tr">
								<td class="data_td1">真爱寄语：</td><td class="data_td2">
									<input class="lang" type="text" placeholder="输入你对真爱的诠释">
								</td>
							</tr>
							<tr class="love_problem">
								<td class="data_td1 vertical_top">爱情考验：</td><td class="data_td2 vertical_top">
									<input class="lang" type="text" placeholder="提出你的问题，去等待TA的回答吧，或许TA的答案能让你明白，你要找的就是TA">
								</td>
							</tr>
						</table>
					</form>
					<div class="btn_box">
						<input type="button" value="保存"/>
					</div>
				</div>

			</div>
		</div>
	</div>
	<div class="footer">
		<p>Copyright © 2013 - 2014 All rights reserved. 哈尔滨精灵科技有限公司</p>
	</div>

@include('account.complete-footer')
@yield('content')