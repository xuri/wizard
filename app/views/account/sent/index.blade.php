@include('account.sent.header')
@yield('content')

	@include('account.sent.navigation')
	@yield('content')

	<div id="content" class="clear">
		<div class="con_title">{{ Lang::get('navigation.friends') }}</div>
		<div class="con_img">
			<span class="line1"></span>
			<span class="line2"></span>
			{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
		</div>

		<div id="wrap" class="clear">
			<div class="w_left">
				<ul class="w_nav"><li><a href="{{ route('account') }}" class="a1 fa fa-tasks">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.profile') }}</a></li>
					<li><a href="{{ route('account.sent') }}" class="active a2 fa fa-heart-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.friends') }}</a></li>
					<li><a href="{{ route('account.inbox') }}" class="a2 fa fa-star">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.followers') }}</a></li>
					<li><a href="{{ route('account.notifications') }}" class="a3 fa fa-inbox">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.inbox') }}</a></li>
					<li><a href="{{ route('account.posts') }}" class="a3 fa fa-flag-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.my_posts') }}</a></li>
					<li><a href="{{ route('support.index') }}" class="a5 fa fa-life-ring">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.support') }}</a></li>
					<li><a href="{{ route('home') }}/article/about.html" class="a5 fa fa-bookmark-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.about') }}</a></li>
				</ul>
				@include('account.qrcode')
				@yield('content')
			</div>
			<div class="w_right">
				<div class="clear">
					<div class="img">
						@if(Auth::user()->portrait)
						{{ HTML::image('portrait/'.Auth::user()->portrait) }}
						@else
						{{ HTML::image('assets/images/preInfoEdit/peo.png') }}
						@endif
					</div>

					<div class="sgnin">
						@if ($message = Session::get('success'))
						<div class="sgnin_top" style="margin:0 0 10px 0">
							<div>
								<span>
									<a href="javascript:;" style="color: #297fb8;">&times;</a>
									{{ $message }}
								</span>
							</div>
						</div>
						@endif

						@include('account.points')
						@yield('content')
					</div>
				</div>


				{{-- Profile --}}
				<div id="data">
					<div class="data_top clear">
						<span></span> {{-- Left pink section --}}
						<p>{{ Lang::get('navigation.friends') }}</p>
					</div>
					<div id="courtship">
						<ul class="clear">
							@foreach($datas as $data)
							<?php
								$user	= User::where('id', $data->receiver_id)->first();
								$Date_1	= date("Y-m-d");
								$Date_2	= date("Y-m-d",strtotime($data->created_at));
								$d1		= strtotime($Date_1);
								$d2		= strtotime($Date_2);
								$Days	= round(($d1-$d2)/3600/24);
							?>
							<li class="preLi">
								{{ HTML::image('portrait/'.$user->portrait, '', array('width' => '186', 'height' => '186','class' => '_headPic')) }}
								{{ Form::open(array(
										'action' => array('MemberController@like', $data->receiver_id)
									))
								}}
								<div class="courtship_title">
									@if($user->sex == 'M')
									{{ HTML::image('assets/images/symbol.png') }}
									@else(Auth::user()->sex == 'F')
									{{ HTML::image('assets/images/g.jpg') }}
									@endif
									<a href="{{ route('members.show', $user->id) }}"><span>{{ $user->nickname }}</span></a>
								</div>
								<div class="cour_bottom">
									<span style="margin: 0 15px 0px 10px; line-height: 2em;"> {{ Lang::get('account/chat.friend_request') }}<em>{{ $data->count }}</em>{{ Lang::get('account/chat.count') }}</span>
									<span style="margin: 0 15px 0px 10px; line-height: 2em;">{{ Lang::get('account/chat.friend_request') }}<em>{{ $Days }}</em>{{ Lang::get('account/chat.days') }}</span><br />
									@if($data->status == 3)
									<a href="{{ route('members.show', $user->id) }}" class="button-block">{{ Lang::get('account/chat.lock_you') }}</a>
									@elseif($data->status == 1)
										<input name="status" type="hidden" value="sender_block" />
										<input type="submit" class="button-resent"
											@if($user->sex == 'M')
											value="{{ Lang::get('account/chat.lock_he') }}"
											@else(Auth::user()->sex == 'F')
											value="{{ Lang::get('account/chat.lock_she') }}"
											@endif
										/>
									@elseif($data->status == 4)
										<input name="status" type="hidden" value="sender_recover" />
										<input type="submit" class="button-unclock" value="{{ Lang::get('account/chat.unlock') }}"
										/>
									@else
									<a href="{{ route('members.show', $user->id) }}" class="button-resent">
									{{ Lang::get('account/chat.request_again') }}</a>
									@endif
									@if($data->status == 0)
									<a href="{{ route('members.show', $user->id) }}" class="button-wait">
									{{ Lang::get('account/chat.waiting') }}</a>
									@elseif($data->status == 1)
									<a href="javascript:;" class="remodal-bg button-blue chat_start" data-id="{{ $user->id }}" id="chat_start" data-nickname="{{ $user->nickname }}">{{ Lang::get('account/chat.start_chat') }}</a>
									@elseif($data->status == 2)
									<a href="javascript:;" class="button-block">{{ Lang::get('account/chat.is_reject') }}</a>
									@elseif($data->status == 4)
									<a href="javascript:;" class="button-block">{{ Lang::get('account/chat.is_lock') }}</a>
									@endif
								</div>
								<input type="hidden" value="" id="{{ $user->id }}"/>
							</li>
							{{ Form::close() }}
							@endforeach

						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>
	@include('account.sent.chat')
	@yield('content')

	@include('layout.copyright')
	@yield('content')

@include('account.sent.footer')
@yield('content')