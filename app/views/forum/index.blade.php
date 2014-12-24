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
					<a class="lu_left active" href="#first">

							<span>爱诊所</span>
							{{ HTML::image('assets/images/boys.png') }}

					</a>
					<a class="lu_left" href="#second">

							<span>男人帮</span>
							{{ HTML::image('assets/images/boys.png') }}

					</a>
					<a class="lu_left" id="bbs_tab_end" href="#third">

							<span>女人窝</span>
							{{ HTML::image('assets/images/girls.png') }}

					</a>
				</ul>
				<div style="display: none;" id="first"></div>
				<div style="display: none;" id="second"></div>
				<div style="display: none;" id="third"></div>
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

	@include('layout.copyright')
	@yield('content')

@include('forum.footer')
@yield('content')