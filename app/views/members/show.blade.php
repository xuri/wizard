@include('members.show-header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="lu_content">
		<div class="lu_con_title">资料详情</div>
		<div class="lu_con_img">
			<span class="lu_line1"></span>
			<span class="lu_line2"></span>
			{{ HTML::image('assets/images/hert.png') }}
		</div>
		<div class="lu_content_box clear">
			<div class="lu_content_main clear">
				<span class="pi_red lu_left"></span>
				<h2 class="pi_inf lu_left" >{{ $data->nickname }}的资料</h2>
				<div class="pi_content_center">
					@if ($message = Session::get('success'))
					<a href="javascript:;" style="color: #297fb8;">&times;</a> {{ $message }}
					@endif
					<div class="pi_center_top">
						{{ HTML::image('portrait/'.$data->portrait, '', array('class' => 'pi_userhead lu_left')) }}
						<h3 class="pi_person lu_left">个人简介</h3>
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

						<span class="pi_name">{{ $data->nickname }}</span>
					</div>
					<ul class="pi_center_main">
						<li>
							<span>出生年:</span>
							<p>{{ $data->born_year }}</p>
						</li>
						<li>
							<span>学校:</span>
							<p>{{ $data->school }}</p>
						</li>
						<li>
							<span>入学年:</span>
							<p>{{ $profile->grade }}</p>
						</li>
						<li>
							<span>星座:</span>
							{{ HTML::image('assets/images/preInfoEdit/constellation/'.$constellationInfo['icon'], '', array('width' => '30', 'height' => '30')) }}
							<p class="pi_special">{{ $constellationInfo['name'] }}</p>
						</li>
						<li>
							<span>标签:</span>
							<p>
							@foreach($tag_str as $tag)
								{{ getTagName($tag) }} &nbsp;
							@endforeach
							</p>
						</li>
						<li><div class="pi_line"></div></li>
						<li>
							<span>爱好:</span>
							<p>{{ $profile->hobbies }}</p>
						</li>
						<li>
							<span>真爱寄语:</span>
							<p>{{ $data->bio }}</p>
						</li>
						<li><div class="pi_line"></div></li>
						<li>
							<span class="pi_trial">爱情考验:</span>
							<p class="pi_trial">{{ $profile->question }}</p>
						</li>
					</ul>
					{{ Form::open() }}
					<input name="_token" type="hidden" value="{{ csrf_token() }}" />
					<input name="like" type="hidden" value="{{ $data->id }}" />
					{{ $errors->first('answer', '<strong class="error" style="color: #cc0000">:message</strong>') }}
					<textarea name="answer"></textarea>
					<div class="pi_center_bottom">
						@if(Auth::user()->id == $data->id)
						@elseif($like)
							<button type="submit">再追一次</button>
						@elseif(Auth::user()->portrait)
							<button type="submit">追 &nbsp;
							@if($data->sex == 'M')
							他
							@elseif($data->sex == 'F')
							她
							@else
							TA
							@endif
							</button>
						@else
							<a href="{{ route('account.complete') }}">需要完善自己的信息，才能追@if($data->sex == 'M')他哦，
							@elseif($data->sex == 'F')她哦，
							@elseTA哦，
							@endif
							快去完善简历吧。</a>
						@endif
					{{ Form::close() }}
					</div>
				</div>
			</div>
			<div class="lu_content_right">
				{{ HTML::image('assets/images/user_1.png', '', array('lu_img')) }}
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