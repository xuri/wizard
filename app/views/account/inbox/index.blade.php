@include('account.inbox.header')
@yield('content')

	@include('account.inbox.navigation')
	@yield('content')

	<div id="content" class="clear">
		<div class="con_title">追我的人</div>
		<div class="con_img">
			<span class="line1"></span>
			<span class="line2"></span>
			{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
		</div>

		<div id="wrap" class="clear">
			<div class="w_left">
				<ul class="w_nav">
					<li><a href="{{ route('account') }}" class="a1 fa fa-tasks" target="_blank">&nbsp;&nbsp;&nbsp;我的资料</a></li>
					<li><a href="{{ route('account.sent') }}" class="a2 fa fa-heart-o">&nbsp;&nbsp;&nbsp;我追的人</a></li>
					<li><a href="{{ route('account.inbox') }}" class="active a2 fa fa-star">&nbsp;&nbsp;&nbsp;追我的人</a></li>
					<li><a href="{{ route('account.notifications') }}" class="a3 fa fa-inbox" target="_blank">&nbsp;&nbsp;&nbsp;我的来信</a></li>
					<li><a href="{{ route('account.posts') }}" class="a3 fa fa-flag-o" target="_blank">&nbsp;&nbsp;&nbsp;我的帖子</a></li>
					<li><a href="{{ route('support.index') }}" class="a5 fa fa-life-ring" target="_blank">&nbsp;&nbsp;&nbsp;联系客服</a></li>
					<li><a href="{{ route('home') }}/article/about.html" class="a5 fa fa-bookmark" target="_blank">&nbsp;&nbsp;&nbsp;关于我们</a></li>
				</ul>
				<div id="download">
					<div>移动客户端下载</div>
					{{ HTML::image('assets/images/preInfoEdit/app.png') }}
				</div>
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
						<p>追我的人</p>
					</div>
					<div id="courtship">
						<ul class="clear">
							@foreach($datas as $data)
							<?php
								$user   = User::where('id', $data->sender_id)->first();
								$Date_1 = date("Y-m-d");
								$Date_2 = date("Y-m-d",strtotime($data->created_at));
								$d1     = strtotime($Date_1);
								$d2     = strtotime($Date_2);
								$Days   = round(($d1-$d2)/3600/24);
							?>
							<li class="preLi">
							@if($user->portrait)
								{{ HTML::image('portrait/'.$user->portrait, '', array('width' => '186', 'height' => '186', 'class' => '_headPic')) }}
							@else
							{{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('width' => '186', 'height' => '186', 'class' => '_headPic')) }}
							@endif
							{{ Form::open(array(
									'action' => array('MemberController@like', $user->id)
								))
							}}
								<div class="courtship_title">
									@if($user->sex == 'M')
									{{ HTML::image('assets/images/symbol.png') }}
									@else(Auth::user()->sex == 'F')
									{{ HTML::image('assets/images/g.jpg') }}
									@endif
									<span>{{ $user->nickname }}</span></div>
								<div class="cour_bottom">
									<span style="margin: 0 15px 0px 10px; line-height: 2em;"> 已追<em>{{ $data->count }}</em>次</span>
									<span style="margin: 0 15px 0px 10px; line-height: 2em;">已追<em>{{ $Days }}</em>天</span><br />
								@if($data->status == 3)
									<input name="status" type="hidden" value="recover" />
									<input type="submit" class="button-resent" value="取消拉黑"
									/>
								@elseif($data->status == 4)
									<a href="{{ route('members.show', $user->id) }}" class="button-block">
									对方已经把你拉黑了</a>
								@else
									<input name="status" type="hidden" value="block" />
									<input type="submit" class="button-resent"
										@if($user->sex == 'M')
										value="把他拉黑"
										@else(Auth::user()->sex == 'F')
										value="把她拉黑"
										@endif
									/>
								@endif
								@if($data->status == 0)
									<a href="{{ route('members.show', $user->id) }}" class="button-wait">等你回复</a>
								@elseif($data->status == 1)
								<a href="javascript:;" class="remodal-bg button-blue chat_start" data-id="{{ $user->id }}" id="chat_start" data-nickname="{{ $user->nickname }}">开始聊天</a>
								@elseif($data->status == 2)
								<a href="javascript:void(0)" class="button-block">已经拒绝</a>
								@elseif($data->status == 3)
								<a href="javascript:void(0)" class="button-block">已经拉黑</a>
								@endif
								</div>
							</li>

							{{ Form::close() }}
							@endforeach
						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>


	@include('account.inbox.chat')
	@yield('content')

	@include('layout.copyright')
	@yield('content')

@include('account.inbox.footer')
@yield('content')