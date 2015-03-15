@include('members.show-header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="lu_content">
		<div class="lu_con_title">{{ Lang::get('navigation.profile') }}</div>
		<div class="lu_con_img">
			<span class="lu_line1"></span>
			<span class="lu_line2"></span>
			{{ HTML::image('assets/images/hert.png') }}
		</div>
		<div class="lu_content_box clear">
			<div class="lu_content_main clear">
				<span class="pi_red lu_left"></span>
				<h2 class="pi_inf lu_left" >{{ $data->nickname }}{{ Lang::get('members/show.users_profile') }}</h2>
				<div class="pi_content_center">

					@if ($message = Session::get('error'))
					<div class="callout-warning">{{ $message }}</div>
					@endif

					<div class="pi_center_top">
						@if($data->is_verify == 1)
						<a href="javascript:void(0);" class="icon_verify" title="{{ Lang::get('members/show.verify') }}" alt="{{ Lang::get('members/show.verify') }}"><span class="icon_approve"></span></a>
						@else
						@endif
						@if($data->portrait)
						{{ HTML::image('portrait/'.$data->portrait, '', array('class' => 'pi_userhead lu_left')) }}
						@else
						{{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('class' => 'pi_userhead lu_left')) }}
						@endif
						<h3 class="pi_person lu_left">{{ Lang::get('members/show.self_intro') }}</h3>
						<p class="pi_introduce lu_left">{{ $profile->self_intro}}</p>
					</div>
					<div class="pi_center_user">
						@if($data->sex == 'M')
						{{ HTML::image('assets/images/symbol.png', '', array('class' => 'pi_sex')) }}
						@elseif($data->sex == 'F')
						{{ HTML::image('assets/images/g.jpg', '', array('class' => 'pi_sex')) }}
						@else
						{{ HTML::image('assets/images/g.jpg', '', array('class' => 'pi_sex')) }}
						@endif
						@if($crenew)
							@if($data->is_admin)
							<span class="admin">{{ Lang::get('system.moderator') }}</span>
							@else
							@endif
							<span class="pi_name" style="color: #FF9900;">{{ $data->nickname }}</span>
						@else
							@if($data->is_admin)
							<span class="admin">{{ Lang::get('system.moderator') }}</span>
							@else
							@endif
							<span class="pi_name">{{ $data->nickname }}</span>
						@endif

					</div>
					<ul class="pi_center_main">
						<li>
							<span>{{ Lang::get('members/show.birthday') }}:</span>
							<p>{{ $data->born_year }}</p>
						</li>
						<li>
							<span>{{ Lang::get('members/show.school') }}:</span>
							<p>{{ $data->school }}</p>
						</li>
						<li>
							<span>{{ Lang::get('members/show.grade') }}:</span>
							<p>{{ $profile->grade }}</p>
						</li>
						<li>
							<span>{{ Lang::get('members/show.constellation') }}:</span>
							{{ HTML::image('assets/images/preInfoEdit/constellation/'.$constellationInfo['icon'], '', array('width' => '30', 'height' => '30')) }}
							<p class="pi_special" style="margin: 0 0 0 2em;">{{ $constellationInfo['name'] }}</p>
						</li>
						<li>
							<span>{{ Lang::get('members/show.tags') }}:</span>
							<p class="lu_userMessage_character" style="height: 55px; overflow: hidden;">
							@foreach($tag_str as $tag)
								<a class="tags">{{ getTagName($tag) }}</a>
							@endforeach
							</p>
						</li>
						<li><div class="pi_line"></div></li>
						<li>
							<span>{{ Lang::get('members/show.hobbies') }}:</span>
							<p>{{ $profile->hobbies }}</p>
						</li>
						<li>
							<span>{{ Lang::get('members/show.bio') }}:</span>
							<p>{{ $data->bio }}</p>
						</li>
						<li><div class="pi_line"></div></li>

				{{-- Other user like this user --}}

				{{-- User profile --}}

				@if(Auth::user()->id == $data->id)
						<li>
							<span class="pi_trial">{{ Lang::get('members/show.my_question') }}:{{ $profile->question }}</span>
						</li>
					</ul>

				@elseif($like)

					{{-- Receiver block user --}}

					@if($like->status == 3)

							<li>
								<span class="pi_trial">
								{{ $sex }}{{ Lang::get('members/show.question') }}:{{ $profile->question }}</span>
							</li>
						</ul>
						<div class="callout-warning">{{ $sex }}已经把你拉黑了。</div>

					{{-- Sender block receiver user --}}

					@elseif($like->status == 4)
						<li>
								<span class="pi_trial">
								{{ $sex }}{{ Lang::get('members/show.question') }}:{{ $profile->question }}</span>
							</li>
						</ul>
						<div class="callout-warning">你已经把对方拉黑了，考虑下是不是要恢复和{{ $sex }}的朋友关系呢？</div>

					{{-- User like other user ago --}}

					@else
							<li>
								<span class="pi_trial">
								{{ $sex }}的爱情考验：{{ $profile->question }}</span>
							</li>
						</ul>
						{{ Form::open() }}
						<input name="status" type="hidden" value="like" />
						{{ $errors->first('answer', '<div class="callout-warning">:message</div>') }}
						<textarea name="answer" class="answer" rows="3" placeholder="{{ Lang::get('members/show.answer_input') }}"></textarea>
						<div class="pi_center_bottom">
							<button type="submit">再追一次</button>
						{{ Form::close() }}
						</div>
					@endif

				{{-- Normal --}}

				@elseif($like_me)

					{{-- Receiver block user --}}

					@if($like_me->status == 4)

							<li>
								<span class="pi_trial">
								{{ $sex }}{{ Lang::get('members/show.question') }}:{{ $profile->question }}</span>
							</li>
						</ul>
						<div class="callout-warning">{{ $sex }}已经把你拉黑了。</div>

					{{-- Sender block receiver user --}}

					@elseif($like_me->status == 3)
						<li>
								<span class="pi_trial">
								{{ $sex }}{{ Lang::get('members/show.question') }}:{{ $profile->question }}</span>
							</li>
						</ul>
						<div class="callout-warning">你已经把对方拉黑了，考虑下是不是要恢复和{{ $sex }}的朋友关系呢？</div>

					{{-- Receiver accept like --}}

					@elseif($like_me->status == 1)
							<li>
								<span class="pi_trial">
								{{ $sex }}{{ Lang::get('members/show.question') }}:{{ $profile->question }}</span>
							</li>
						</ul>
						<div class="callout-warning">你已接受{{ $sex }}的邀请。</div>
					@else

							<li>
								<span class="pi_trial">
								{{ $sex }}{{ Lang::get('members/show.question') }}:{{ $profile->question }}</span>
							</li>
						</ul>
						<div class="callout-warning">{{ $sex }}{{ Lang::get('members/show.answer_for_me') }} {{ $like_me->answer }}</div>
						<div class="pi_center_bottom">
						{{ Form::open() }}
							<input name="status" type="hidden" value="accept" />
							<input type="submit" value="{{ Lang::get('members/show.accept') }}" />
						{{ Form::close() }}
						{{ Form::open() }}
							<input name="status" type="hidden" value="reject" />
							<input type="submit" value="{{ Lang::get('members/show.reject') }}" />
						{{ Form::close() }}
						</div>

					@endif

				@else
						<li>
							<span class="pi_trial">{{ $sex }}{{ Lang::get('members/show.question') }}:{{ $profile->question }}</span>
						</li>
					</ul>
					{{ Form::open() }}
					<input name="status" type="hidden" value="like" />
					{{ $errors->first('answer', '<div class="callout-warning">:message</div>') }}
					<textarea name="answer" class="answer" rows="3" placeholder="{{ Lang::get('members/show.answer_input') }}"></textarea>
					<div class="pi_center_bottom">
						<button type="submit">{{ Lang::get('members/show.friend_request') }}{{ $sex }}</button>
					{{ Form::close() }}
					</div>
				@endif

				</div>
			</div>
			<div class="lu_content_right">
				{{ HTML::image('assets/images/sidebar_2.jpg') }}
			</div>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

	<script type="text/javascript">
		var aColor=['#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900',
					'#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900',
					'#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900',
					'#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900',
					'#e64150','#5cd5d5','#8acd47','#ffcc00','#a036a0', '#FF3399', '#6699FF', '#FF9900'];
		function loop(classValue){
			var aT=document.getElementsByClassName(classValue);
			for(var i=0;i<aT.length;i++){
				var aLi=aT[i].getElementsByTagName('a');
				for(var a=0;a<aLi.length;a++){
					aLi[a].style.background=aColor[a];
				}
			}
		}
		loop('lu_userMessage_character');
	</script>

</body>
</html>