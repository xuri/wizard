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
					<li style="background:#e64150;">{{ getTagName($tag) }}</li>
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
</script>