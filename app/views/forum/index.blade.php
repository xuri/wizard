@include('forum.header')
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

			<div id="bbs_content_main" class="tabs lu_content_main clear">
				<ul class="tabNavigation bbs_tab">
					<a class="" href="#first">
						<li class="lu_left active">
							<span>爱诊所</span>
							{{ HTML::image('assets/images/boys.png') }}
						</li>
					</a>
					<a class="" href="#second">
						<li class="lu_left">
							<span>男人帮</span>
							{{ HTML::image('assets/images/boys.png') }}
						</li>
					</a>
					<a class="" href="#third">
						<li class="lu_left" id="bbs_tab_end">
							<span>女人窝</span>
							{{ HTML::image('assets/images/girls.png') }}
						</li>
					</a>
				</ul>
				<div style="display: none;" id="first">
					<ul id="bbs_main_clinic" class="bbs_main">{{-- 爱诊所 --}}
						<li class="bbs_main_boy">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我dfg</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_girl">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我123123123</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_boy">
							<a href="#">五个时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我sdfsdszd</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
						</li>
						<li class="bbs_main_girl">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我asdasd</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_boy">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我dfg</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_girl">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我123123123</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_boy">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我sdfsdszd</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
						</li>
						<li class="bbs_main_girl">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我asdasd</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
					</ul>

					{{ Form::open(array(
						'class'			=> 'bbs_bottom',
						'autocomplete' 	=> 'off',
						'action'		=> 'ForumController@postNew'
						))
					}}
						<input name="_token" type="hidden" value="{{ csrf_token() }}" />
						<input name="category_id" type="hidden" value="1" />
						<div class="bbs_bottom_new lu_left">
							{{ HTML::image('assets/images/release.png') }}
							<span>发表新帖</span>
						</div>
						<input class="bbs_bottom_title lu_left" type="text" name="title" placeholder="添加题目" value="{{ Input::old('title') }}" required="required">{{ $errors->first('title', '<strong class="error" style="color: #cc0000">:message</strong>') }}
						{{ $errors->first('content', '<strong class="error" style="color: #cc0000">:message</strong>') }}
						<textarea class="bbs_bottom_text" name="content" cols="30" rows="10">{{ Input::old('content') }}</textarea>
						<input class="bbs_bottom_btn" type="submit" value="发表">
					{{ Form::close() }}

				</div>
				<div style="display: none;" id="second">
					<ul id="bbs_main_man" class="bbs_main">{{-- 男人帮 --}}
						<li class="bbs_main_boy">
							<a href="#">五小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我dfg</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_boy">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我123123123</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_boy">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我sdfsdszd</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
						</li>
						<li class="bbs_main_boy">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我asdasd</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_boy">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我dfg</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_boy">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我123123123</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_boy">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我sdfsdszd</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
						</li>
						<li class="bbs_main_boy">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我asdasd</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
					</ul>

					{{ Form::open(array(
						'class'			=> 'bbs_bottom',
						'autocomplete' 	=> 'off',
						'action'		=> 'ForumController@postNew'
						))
					}}
						<input name="_token" type="hidden" value="{{ csrf_token() }}" />
						<input name="category_id" type="hidden" value="2" />
						<div class="bbs_bottom_new lu_left">
							{{ HTML::image('assets/images/release.png') }}
							<span>发表新帖</span>
						</div>
						<input class="bbs_bottom_title lu_left" type="text" name="title" placeholder="添加题目" value="{{ Input::old('title') }}" required="required">{{ $errors->first('title', '<strong class="error" style="color: #cc0000">:message</strong>') }}
						{{ $errors->first('content', '<strong class="error" style="color: #cc0000">:message</strong>') }}
						<textarea class="bbs_bottom_text" name="content" cols="30" rows="10">{{ Input::old('content') }}</textarea>
						<input class="bbs_bottom_btn" type="submit" value="发表">
					{{ Form::close() }}
				</div>
				<div style="display: block;" id="third">

					<ul id="bbs_main_woman" class="bbs_main">{{-- 女人窝 --}}
						<li class="bbs_main_girl">
							<a href="#">五个小时没保存了！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我dfg</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_girl">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我123123123</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_girl">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我sdfsdszd</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
						</li>
						<li class="bbs_main_girl">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我asdasd</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_girl">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我dfg</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_girl">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我123123123</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
						<li class="bbs_main_girl">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我sdfsdszd</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
							{{ HTML::image('assets/images/boys.png') }}
						</li>
						<li class="bbs_main_girl">
							<a href="#">五个小时没保存了！！！</a>
							<p>我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字好的等我asdasd</p>
							<span class="bbs_main_look">58</span>
							<span class="bbs_main_time">18:27</span>
						</li>
					</ul>

					{{ Form::open(array(
						'class'			=> 'bbs_bottom',
						'autocomplete' 	=> 'off',
						'action'		=> 'ForumController@postNew'
						))
					}}
						<input name="_token" type="hidden" value="{{ csrf_token() }}" />
						<input name="category_id" type="hidden" value="3" />
						<div class="bbs_bottom_new lu_left">
							{{ HTML::image('assets/images/release.png') }}
							<span>发表新帖</span>
						</div>
						<input class="bbs_bottom_title lu_left" type="text" name="title" placeholder="添加题目" value="{{ Input::old('title') }}" required="required">{{ $errors->first('title', '<strong class="error" style="color: #cc0000">:message</strong>') }}
						{{ $errors->first('content', '<strong class="error" style="color: #cc0000">:message</strong>') }}
						<textarea class="bbs_bottom_text" name="content" cols="30" rows="10">{{ Input::old('content') }}</textarea>
						<input class="bbs_bottom_btn" type="submit" value="发表">
					{{ Form::close() }}

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

@include('forum.footer')
@yield('content')