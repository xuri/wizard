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
							<p class="lu_te lu_userMessage_name lu_left">{{ $data->nickname }}</p>
							<p class="lu_te lu_userMessage_p lu_userMessage_school lu_left">啊撒大声地啊撒大声地阿斯达阿斯达阿斯达按时d{{-- $data->school --}}</p>
							<p class="lu_userMessage_p lu_left">{{ $profile->grade }}届</p>
							<a class="lu_userMessage_detail lu_right" href="{{ route('members.show', $data->id) }}">详细资料</a>
							<p class="lu_userMessage_readme lu_left">{{ $profile->self_intro }}</p>
							<ul class="lu_userMessage_character">

								@foreach($tag_str as $tag)
								<li style="background:#e64150;">{{ getTagName($tag) }}</li>
								@endforeach
								<!-- <li style="background:#e64150;">帅哥控</li>
								<li style="background:#5cd5d5;">美女控</li>
								<li style="background:#8acd47;">御姐控</li>
								<li style="background:#ffcc00;">文艺青年</li>
								<li style="background:#a036a0;">技术宅</li> -->
							</ul>
						</div>
					</div>
				</div>
				@else
				@endif
				@endforeach
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
<script type="text/javascript">
	var aColor=['#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0'];
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
</script>
</html>