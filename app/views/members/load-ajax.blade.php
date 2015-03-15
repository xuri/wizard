<div id="load-ajax">
	@foreach($datas as $data)
	@if($data->portrait)
	<?php
		$profile = Profile::where('user_id', $data->id)->first();
		$tag_str = explode(',', substr($profile->tag_str, 1));
	?>
	<div class="lu_resumes clear">
		<div class="lu_resumes_user clear">
				@if($data->is_verify == 1)
				<a href="javascript:void(0);" class="icon_verify" title="{{ Lang::get('members/index.verify') }}" alt="{{ Lang::get('members/index.verify') }}"><span class="icon_approve"></span></a>
				@else
				@endif

				<a href="{{ route('members.show', $data->id) }}">
					{{ HTML::image('portrait/' . $data->portrait, '', array('class' => 'lu_img')) }}
				</a>

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
						<span class="admin"><a href="{{ route('members.show', $data->id) }}">{{ Lang::get('system.moderator') }}</a></span>
						@else
						@endif
						<span style="color: #FF9900;"><a href="{{ route('members.show', $data->id) }}" class="nickname">{{ $data->nickname }}</a></span>
					</p>
				@else
					<p class="lu_te lu_userMessage_name lu_left">
						@if($data->is_admin)
							<span class="admin"><a href="{{ route('members.show', $data->id) }}">{{ Lang::get('system.moderator') }}</a></span>
						@else
						@endif
						<a href="{{ route('members.show', $data->id) }}" class="nickname">{{ $data->nickname }}</a>
					</p>
				@endif
				<p class="lu_te lu_userMessage_p lu_userMessage_school lu_left">{{ $data->school }}</p>
				<p class="lu_userMessage_p lu_left">{{ $profile->grade }}{{ Lang::get('members/index.grade') }}</p>
				<a class="lu_userMessage_detail lu_right" href="{{ route('members.show', $data->id) }}">{{ Lang::get('members/index.detail') }}</a>
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