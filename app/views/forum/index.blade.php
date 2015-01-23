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

			<div id="if_success"></div>

			{{ $errors->first('title', '<div class="callout-warning">:message</div>') }}
			{{ $errors->first('content', '<div class="callout-warning">:message</div>') }}

			<div id="bbs_content_main" class="tabs lu_content_main clear">
				<ul class="tabNavigation bbs_tab">
					<a class="lu_left active" href="#first">

							<span>爱诊所</span>
							{{ HTML::image('assets/images/boys.png') }}

					</a>
					<a class="lu_left" href="#second">

							<span>男生帮</span>
							{{ HTML::image('assets/images/boys.png') }}

					</a>
					<a class="lu_left" id="bbs_tab_end" href="#third">

							<span>女生窝</span>
							{{ HTML::image('assets/images/girls.png') }}

					</a>
				</ul>
				<div style="display: none;" id="first">
					<div id="first_inner"></div>
					<div class="if_error_1"></div>

					{{ Form::open(array(
						'class'			=> 'bbs_bottom',
						'autocomplete' 	=> 'off',
						'action'		=> 'ForumController@postNew'
						))
					}}
						<input name="category_id" type="hidden" value="1" />
						<div class="bbs_bottom_new lu_left">
							{{ HTML::image('assets/images/release.png') }}
							<span>发表新帖</span>
						</div>
						<input class="bbs_bottom_title lu_left bbs_bottom_title_1" type="text" name="title" placeholder="添加题目" value="{{ Input::old('title') }}" required="required">
						<br />
						<br />
						<br />
						{{ Umeditor::css() }}
						{{ Umeditor::content(Input::old('content'), ['id'=> 'cat1_editor', 'class'=>'myEditor text-umeditor bbs_bottom', 'name' => 'content', 'height' => '220']) }}
						{{ Umeditor::js() }}
						{{ Form::button('发表', array('class' => 'bbs_bottom_btn bbs_bottom_btn_1', 'data-action-id' => '1')) }}
					{{ Form::close() }}

				</div>

				@if($cat2_status)
				<div style="display: none;" id="second">
					<div id="second_inner"></div>
					<div class="if_error_2"></div>

					{{ Form::open(array(
						'class'			=> 'bbs_bottom',
						'autocomplete' 	=> 'off',
						'action'		=> 'ForumController@postNew'
						))
					}}
						<input name="category_id" type="hidden" value="2" />
						<div class="bbs_bottom_new lu_left">
							{{ HTML::image('assets/images/release.png') }}
							<span>发表新帖</span>
						</div>
						<input class="bbs_bottom_title lu_left bbs_bottom_title_2" type="text" name="title" placeholder="添加题目" value="{{ Input::old('title') }}" required="required">
						<br />
						<br />
						<br />
						{{ Umeditor::css() }}
						{{ Umeditor::content(Input::old('content'), ['id'=> 'cat2_editor', 'class'=>'myEditor text-umeditor bbs_bottom', 'name' => 'content', 'height' => '220']) }}
						{{ Umeditor::js() }}
						{{ Form::button('发表', array('class' => 'bbs_bottom_btn bbs_bottom_btn_2', 'data-action-id' => '2')) }}
					{{ Form::close() }}

				</div>
				@else
				<div style="display: none;" id="second">
					<div class="callout-warning">这个版块会在每晚9点到10点开放哦。</div>
					{{ HTML::image('assets/images/cat2_close.jpg') }}
				</div>
				@endif

				@if($cat3_status)
				<div style="display: none;" id="third">
					<div id="third_inner"></div>
					<div class="if_error_3"></div>

					{{ Form::open(array(
						'class'			=> 'bbs_bottom',
						'autocomplete' 	=> 'off',
						'action'		=> 'ForumController@postNew'
						))
					}}
						<input name="category_id" type="hidden" value="3" />
						<div class="bbs_bottom_new lu_left">
							{{ HTML::image('assets/images/release.png') }}
							<span>发表新帖</span>
						</div>
						<input class="bbs_bottom_title lu_left bbs_bottom_title_3" type="text" name="title" placeholder="添加题目" value="{{ Input::old('title') }}" required="required">
						<br />
						<br />
						<br />
						{{ Umeditor::css() }}
						{{ Umeditor::content(Input::old('content'), ['id'=> 'cat3_editor', 'class'=>'myEditor text-umeditor bbs_bottom', 'name' => 'content', 'height' => '220']) }}
						{{ Umeditor::js() }}
						{{ Form::button('发表', array('class' => 'bbs_bottom_btn bbs_bottom_btn_3', 'data-action-id' => '3')) }}
					{{ Form::close() }}

				</div>
				@else
				<div style="display: none;" id="third">
					<div class="callout-warning">这个版块会在每晚9点到10点开放哦。</div>
					{{ HTML::image('assets/images/cat3_close.jpg') }}
				</div>
				@endif
			</div>

			<div class="lu_content_right">
				{{ HTML::image('assets/images/sidebar_3.jpg') }}
			</div>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

@include('forum.footer')
@yield('content')