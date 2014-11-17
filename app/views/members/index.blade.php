@include('members.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="lu_content">
		<div class="lu_con_title">缘来在这</div>
		<div class="lu_con_img">
			<span class="lu_line1"></span>
			<span class="lu_line2"></span>
			{{ HTML::image('assets/images/hert.png') }}

		</div>
		<div class="lu_content_box clear">
			<div class="lu_content_main clear">
				<div class="lu_content_main_tab">
					<a href="#" class="lu_school lu_public">选择学校</a>
					<select class="lu_sex lu_public">
						<option>性别</option>
						<option>男</option>
						<option>女</option>
					</select>
					<a href="#" class="lu_search lu_public">搜索</a>
					<a href="#" class="lu_release lu_public">我也发个</a>
				</div>
				<div class="lu_resumes clear">
					<div class="lu_resumes_user clear">
						{{ HTML::image('assets/images/user_1.png', '', array('class' => 'lu_img')) }}


						<div class="lu_userMessage">
							{{ HTML::image('assets/images/symbol.png', '', array('class' => 'lu_left')) }}

							<p class="lu_te lu_userMessage_name lu_left">夏米斯丁艾合麦提·阿布都米吉提</p>
							<p class="lu_te lu_userMessage_p lu_userMessage_school lu_left">黑龙江工程学院</p>
							<p class="lu_userMessage_p lu_left">2012届</p>
							<p class="lu_userMessage_p lu_left">身高：170</p>
							<a class="lu_userMessage_detail lu_left" href="#">详细资料</a>
							<p class="lu_userMessage_readme lu_left">我想找到值得信赖的人！为爱加油吧。继续凑字，争取凑两行继续凑字凑字凑字美女如此多酱紫酱紫酱紫。</p>
							<ul class="lu_userMessage_character">
								<li style="background:#e64150;">帅哥控</li>
								<li style="background:#5cd5d5;">美女控</li>
								<li style="background:#8acd47;">御姐控</li>
								<li style="background:#ffcc00;">文艺青年</li>
								<li style="background:#a036a0;">技术宅</li>
							</ul>
							{{ HTML::image('assets/images/arrow.png', '', array('class' => 'lu_userMessage_arrow')) }}

						</div>
					</div>
				</div>
				<div class="lu_paging">
					<span>上一页</span>
					<a class="lu_active">1</a>
					<a>2</a>
					<a>3</a>
					<a>4</a>
					<span>下一页</span>
				</div>
			</div>
			<div class="lu_content_right">
				{{ HTML::image('assets/images/user_1.png', '', array('class' => 'lu_img')) }}

				<div class="lu_up">
					<p class="lu_te lu_name lu_left">
						{{ HTML::image('assets/images/symbol.png') }}

						夏米斯丁艾合麦提·阿布都米吉提</p>
					<p class="lu_bin lu_left">精灵豆：<a>60</a></p>
				</div>
			</div>
		</div>
	</div>
</body>
</html>