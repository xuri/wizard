@include('account.complete-header')
@yield('content')

	{{-- Mask --}}
	<div id="mask"></div>
	<div id="checkbg">
		<a id="check_close" href="javascript:;">×</a>
		<a class="once" href="javascript:;" id="../assets/images/preInfoEdit/boy/">
			{{ HTML::image('assets/images/preInfoEdit/bgcolor/boy.png') }}
		</a>
		<a class="once" href="javascript:;" id="../assets/images/preInfoEdit/girl/">
			{{ HTML::image('assets/images/preInfoEdit/bgcolor/girl.png') }}
		</a>
	</div>
	{{-- Avatar --}}
	<div id="pre_content">
		<a id="pre_close" href="javascript:;">×</a>
		<canvas id="pre_pic_wrap" width="180" height="220">您的浏览器不支持该功能</canvas>
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
			<div class="vs-shcoollist clear" id="provinces">
				<a href="javascript:;">黑龙江</a>
				<a href="javascript:;">黑龙江</a>
				<a href="javascript:;">黑龙江</a>
				<a href="javascript:;">黑龙江</a>
				<a href="javascript:;">黑龙江</a>
				<a href="javascript:;">黑龙江</a>
			</div>
			<span class="vs-line-bottom"></span>
			<!--a href="javascript:;">哈尔滨理工大学</a-->
			<div class="vs-school clear" id="school_wrap">

			</div>
		</div>
	</div>
	{{-- end Choose school --}}

	{{-- 选择星座 --}}
	<div class="con-Popup" id="con-Popup">
		<div class="con-Popup-pass" id="con-pass">×</div>
		<div class="con-box clear">
			<div class="con-Popup-school">选择星座</div>
			<ul class="con-Constellation clear">
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
			</ul>
			<ul class="con-Constellation clear">
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
			</ul>
			<ul class="con-Constellation clear">
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
				<li><span>憨厚</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>内敛</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>潮男</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>正直</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>胖的</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>靠谱</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>麦霸</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>静待缘分</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>有点发福</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>特别能睡</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>稳重</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>有责任心</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>学霸</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>开朗</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>诚信</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>阳光</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>执着</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>奋斗中</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>爱挑战</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>冷静</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>张扬</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li><span>幽默</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li><span>乐观</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li><span>低调</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li><span>独立</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li><span>健康</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li><span>高大</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li><span>包容</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li><span>完美主义</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li><span>非诚勿扰</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}
				</li>
				<li><span>宅</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>简单</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>大大咧咧</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>帅</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>孝顺</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>话唠</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>戒烟ing</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>张扬</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>有男人味</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
				<li><span>萌萌的</span>
					{{ HTML::image('assets/images/preInfoEdit/Y.png', '', array('class' => 'Y')) }}</li>
			</ul>
		</div>
		<input type="submit" class="tag-submit" value="完成" id="tag_end"/>
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
					<li><a href="{{ route('account') }}" class="active a1 fa fa-tasks">&nbsp;&nbsp;&nbsp;我的资料</a></li>
					<li><a href="{{ route('account.sent') }}" class="a2 fa fa-heart-o">&nbsp;&nbsp;&nbsp;我追的人</a></li>
					<li><a href="#" class="a3 fa fa-inbox">&nbsp;&nbsp;&nbsp;我的来信</a></li>
					<li><a href="#" class="a4 fa fa-star-o">&nbsp;&nbsp;&nbsp;我的关注</a></li>
					<li><a href="#" class="a5 fa fa-bookmark">&nbsp;&nbsp;&nbsp;关注我们</a></li>
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
						<img src="{{ route('home') }}/portrait/{{ Auth::user()->portrait }}" id="head_pic">
						@else
						{{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('id' => 'head_pic'))}}
						@endif
						<div id="change_photo">修改头像{{ $errors->first('portrait', '<strong class="error" style="color: #cc0000">:message</strong>') }}</div>
					</div>
					<div class="sgnin">
						<div class="sgnin_top">
							<div><span>昵称 : </span>{{ Auth::user()->nickname }}</div>
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
					{{ Form::open(array(
					'id'           => 'edi_form',
					'autocomplete' => 'off',
					'action'       => 'AccountController@postComplete'
					)) }}
						<input id="province_token" name="_token" type="hidden" value="{{ csrf_token() }}" />
						<input name="portrait" value="{{ Input::old('portrait', $profile->portrait) }}" id="portait" type="hidden"/>
						<input name="constellation" value="{{ Input::old('constellation', $profile->constellation) }}" id="constellation" type="hidden"/>
						<input name="tag_str" id="tag_str" value="{{ Input::old('tag_str', $profile->tag_str) }}"  type="hidden"/>
						<input name="school" value="{{ Input::old('school', Auth::user()->school) }}" id="school_str" type="hidden"/>
						<table>
							<tr>
								<td class="data_td1">昵称：{{ $errors->first('nickname', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td><td class="data_td2">
									<input type="text" value="{{ Input::old('nickname', Auth::user()->nickname) }}" name="nickname" placeholder="请输入你的昵称"/>
								</td>
							</tr>
							<tr>
								<td class="data_td1">性别：{{ $errors->first('sex', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td>
								<td class="data_td2">
									<select name="sex" id="sex_select">
										<option value="">请选择</option>
										<option value="M">男</option>
										<option value="F">女</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="data_td1">出生年：{{ $errors->first('born_year', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td>
								<td class="data_td2">
									<select name="born_year" id="born_select">
										<option value="1990">1990</option>
										<option value="1991">1991</option>
										<option value="1992">1992</option>
										<option value="1993">1993</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="data_td1">学校：{{ $errors->first('school', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td>
								<td class="data_td2">
									<span type="text" id="check_school">请选择学校</span>
								</td>
							</tr>
							<tr>
								<td class="data_td1">入学年：{{ $errors->first('grade', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td>
								<td class="data_td2">
									<select name="grade" id="grade_select">
										<option value="2011">2011</option>
										<option value="2012">2012</option>
										<option value="2013">2013</option>
										<option value="2014">2014</option>
									</select>
								</td>
							</tr>
							<tr>
								<td class="data_td1">星座：{{ $errors->first('constellation', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td><td class="data_td2 constellation">
								{{ HTML::image('assets/images/preInfoEdit/constellation/default.png', '', array('width' => '30', 'height' => '30', 'class' => 'constellation_img', 'id' => 'con_img')) }}
								<span style="margin-left:50px;" id="check_constellation">请选择星座</span></td>
							</tr>
							<tr>
								<td class="data_td1 vertical_top">标签：{{ $errors->first('tag_str', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td>
									<!--span data-num="1-冷酷">冷酷<em>×</em></span-->
								<td class="data_td2 character" id="tag_td">
									<span class="end" id="check_tag"><b>+</b>  标签 </span>
								</td>
							</tr>
							<tr>
								<td class="data_td1">爱好：{{ $errors->first('hobbies', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td><td class="data_td2">
									<input class="lang" name="hobbies" type="text" placeholder="把你的爱好告诉大家吧" value="{{ Input::old('hobbies', $profile->hobbies) }}" />
								</td>
							</tr>
							<tr>
								<td class="data_td1 vertical_top">个人简介：{{ $errors->first('self_intro', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td><td class="data_td2 vertical_top">
									<textarea rows="4" name="self_intro" placeholder="这是推销你自己的好机会">{{ Input::old('self_intro', $profile->self_intro) }}</textarea>
								</td>
							</tr>
							<tr class="end_tr">
								<td class="data_td1">真爱寄语：{{ $errors->first('bio', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td><td class="data_td2">
									<input class="lang" name="bio" type="text" placeholder="输入你对真爱的诠释" value="{{ Input::old('bio', Auth::user()->bio) }}">
								</td>
							</tr>
							<tr class="love_problem">
								<td class="data_td1 vertical_top">爱情考验：{{ $errors->first('question', '<strong class="error" style="color: #cc0000">:message</strong>') }}</td><td class="data_td2 vertical_top">
									<input class="lang" type="text" name="question" placeholder="提出你的问题，去等待TA的回答吧，或许TA的答案能让你明白，你要找的就是TA" value="{{ Input::old('question', $profile->question) }}">
								</td>
							</tr>
						</table>

					<div class="btn_box">
						<input type="submit" value="保存"/>
					</div>
					{{ Form::close() }}
				</div>

			</div>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

@include('account.complete-footer')
@yield('content')