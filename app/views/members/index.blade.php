@include('members.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="lu_content">
		<div class="lu_con_title">缘分大厅{{ Session::get('university') }}{{ Session::get('sex')}}</div>
		<div class="lu_con_img">
			<span class="lu_line1"></span>
			<span class="lu_line2"></span>
			{{ HTML::image('assets/images/hert.png') }}

		</div>
		<div class="lu_content_box clear">
			<div class="lu_content_main clear">
				{{ Form::open(array('method' => 'get', 'class' => 'lu_content_main_tab')) }}
					<select class="lu_school lu_public" name="university" id="university_select" rel="{{ Session::get('university') }}">
						<option value="all">所有学校</option>
						@foreach($open_universities as $open_university)
						<option value="{{ $open_university->university }}">{{ $open_university->university }}</option>
						@endforeach
						<option value="others">其他学校</option>
						@foreach($pending_universities as $pending_university)∂
						<option vlaue="">{{ $pending_university->university }}（{{ date('m月d日', strtotime($pending_university->created_at)) }}开放）</option>
						@endforeach
					</select>
					{{
						Form::select(
							'target',
							array('all' => '性别', 'M' => '男', 'F' => '女'),
							Input::get('target', 'sex'),
							array('class' => 'lu_sex lu_public', 'name' => 'sex', 'id' => 'sex_select', 'rel' => Session::get('sex'))
						)
					}}
					{{
						Form::select(
							'target',
							array('' => '入学年份',
									'2015' => '2015年',
									'2014' => '2014年',
									'2013' => '2013年',
									'2012' => '2012年',
									'2011' => '2011年',
									'2010' => '2010年',
								),
							Input::get('target', 'grade'),
							array('class' => 'lu_school lu_public', 'name' => 'grade', 'id' => 'grade_select', 'rel' => Session::get('grade'))
						)
					}}
					<button type="submit" class="lu_search lu_public">搜索</button>
					<a href="{{ route('account') }}" class="lu_release lu_public">我也发个</a>
				{{ Form::close() }}

				<div id="load-ajax">
					@foreach($datas as $data)
					@if($data->portrait)
					<?php
						$profile = Profile::where('user_id', $data->id)->first();
						$tag_str = explode(',', substr($profile->tag_str, 1));
					?>
					<div class="lu_resumes clear">
						<div class="lu_resumes_user clear">
							{{ HTML::image('portrait/'.$data->portrait, '', array('class' => 'lu_img')) }}
							<div class="lu_userMessage">
							{{ HTML::image('assets/images/arrow.png', '', array('class' => 'lu_userMessage_arrow')) }}
							@if($data->sex == 'M')
							{{ HTML::image('assets/images/symbol.png', '', array('class' => 'lu_left')) }}
							@else
							{{ HTML::image('assets/images/g.jpg', '', array('class' => 'lu_left')) }}
							@endif
								@if($profile->crenew >= 30)
									<p class="lu_te lu_userMessage_name lu_left">
										@if($data->is_admin)
										<span class="admin">管理员</span>
										@else
										@endif
										<span style="color: #FF9900;">{{ $data->nickname }}</span>
									</p>
								@else
									<p class="lu_te lu_userMessage_name lu_left">
										@if($data->is_admin)
											<span class="admin">管理员</span>
										@else
										@endif
										{{ $data->nickname }}
									</p>
								@endif
								<p class="lu_te lu_userMessage_p lu_userMessage_school lu_left">{{ $data->school }}</p>
								<p class="lu_userMessage_p lu_left">{{ $profile->grade }}届</p>
								<a class="lu_userMessage_detail lu_right" href="{{ route('members.show', $data->id) }}">详细资料</a>
								<p class="lu_userMessage_readme lu_left">{{ $profile->self_intro }}</p>
								<ul class="lu_userMessage_character">

									@foreach($tag_str as $tag)
									<li>{{ getTagName($tag) }}</li>
									@endforeach
								</ul>
							</div>
						</div>
					</div>
					@else
					@endif
					@endforeach
					{{ pagination($datas->appends(Input::except('page')), 'layout.paginator') }}
				</div>
			</div>
			<div class="lu_content_right">
				{{ HTML::image('assets/images/sidebar_1.jpg') }}


			</div>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

</body>

{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

<script type="text/javascript">
	var aColor=['#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900',
				'#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900',
				'#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900',
				'#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900',
				'#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900'];
	function loop(classValue){
		var aT=document.getElementsByClassName(classValue);
		for(var i=0;i<aT.length;i++){
			var aLi=aT[i].getElementsByTagName('li');
			for(var a=0;a<aLi.length;a++){
				aLi[a].style.background=aColor[a];
			}
		}
	}
	loop('lu_userMessage_character');

	// Ajax pagination

	$(function() {
		$('#load-ajax').on('click', '.lu_paging a', function (e) {
			getPosts($(this).attr('href').split('page=')[1]);
			e.preventDefault();
		});
	});

	function getPosts(page) {
		$.ajax({
			url : '?page=' + page,
			dataType: 'json',
		}).done(function (data) {
			$('#load-ajax').html(data);
		}).fail(function () {
			alert('Posts could not be loaded.');
		});
	}

	@if(Session::get('university'))
	$("#university_select").val($("#university_select").attr("rel"));
	@endif

	@if(Session::get('sex'))
	$("#sex_select").val($("#sex_select").attr("rel"));
	@endif

	@if(Session::get('grade'))
	$("#grade_select").val($("#grade_select").attr("rel"));
	@endif
</script>
</html>