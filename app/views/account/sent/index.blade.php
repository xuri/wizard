@include('account.sent.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="content" class="clear">
		<div class="con_title">我追的人</div>
		<div class="con_img">
			<span class="line1"></span>
			<span class="line2"></span>
			{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
		</div>

		<div id="wrap" class="clear">
			<div class="w_left">
				<ul class="w_nav">
					<li><a href="{{ route('account') }}" class="a1 fa fa-tasks">&nbsp;&nbsp;&nbsp;我的资料</a></li>
					<li><a href="{{ route('members.index') }}" class="a1 fa fa-users">&nbsp;&nbsp;&nbsp;缘来在这</a></li>
					<li><a href="{{ route('account.sent') }}" class="active a2 fa fa-heart-o">&nbsp;&nbsp;&nbsp;我追的人</a></li>
					<li><a href="{{ route('account.inbox') }}" class="a2 fa fa-star">&nbsp;&nbsp;&nbsp;追我的人</a></li>
					<li><a href="{{ route('account.notifications') }}" class="a3 fa fa-inbox">&nbsp;&nbsp;&nbsp;我的来信</a></li>
					<li><a href="{{ route('forum.index') }}" class="a3 fa fa-user">&nbsp;&nbsp;&nbsp;单身公寓</a></li>
					<li><a href="#" class="a5 fa fa-bookmark-o">&nbsp;&nbsp;&nbsp;关注我们</a></li>
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
						<div class="sgnin_top">
							<div><span>昵称 : </span>{{ Auth::user()->nickname }}</div>
							<div><span>精灵豆 : </span><em>{{ Auth::user()->points }}</em><strong>　(每天为爱情正能量加油可以获取精灵豆哦)</strong></div>
						</div>
						<div class="sgnin_con">
							<div class="comeon">
								<span class="comeon_title">为爱情正能量加油</span>
								<a id="clickon" href="javascript:;">加油</a>
								<div id="instr">
									<div>当你加油累积<span>10</span>天后，会得到代表(活跃用户标志)的<em>橙色昵称</em></div>
									<div>当你加油累积<span>30</span>天后，会得到代表粉丝级用户标志的<span>头像加冠</span></div>
									<div>当你加油累积<span>50</span>天后，会得到价值<span>120</span>元的公仔一个</div>
									<div>如果你加油累积到<span>50天以后</span>呢？只要你相信真爱，就会惊喜不断，让我们一起为真爱加油助威吧</div>
									<div><strong>注意：如果断签一天会扣除2天的能量值</strong></div>
								</div>
							</div>
							<div class="pillars">
								<div id="pillars_fixed">
									<div id="pillars_auto" style=" width: 0px;">
										{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
										<div>已加油<span>0</span>天</div>
									</div>
									<span class="num num1">0</span>
									<span class="num num2">25</span>
									<span class="num num3">50</span>
								</div>
							</div>
						</div>
					</div>
				</div>


				{{-- Profile --}}
				<div id="data">
					<div class="data_top clear">
						<span></span> {{-- Left pink section --}}
						<p>我追的人</p>
					</div>
					<div id="courtship">
						<ul class="clear">
							@foreach($datas as $data)
							<?php
								$user 	= User::where('id', $data->receiver_id)->first();
								$Date_1 = date("Y-m-d");
								$Date_2 = date("Y-m-d",strtotime($data->created_at));
								$d1     = strtotime($Date_1);
								$d2     = strtotime($Date_2);
								$Days   = round(($d1-$d2)/3600/24);
							?>
							<li>
								{{ HTML::image('portrait/'.$user->portrait, '', array('width' => '152', 'height' => '186')) }}
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
									<span>{{ $user->nickname }}</span></div>
								<div class="cour_bottom">
									<span style="margin: 0 15px 0px 10px; line-height: 2em;"> 已追<em>{{ $data->count }}</em>次</span>
									<span style="margin: 0 15px 0px 10px; line-height: 2em;">已追<em>{{ $Days }}</em>天</span><br />
									@if($data->status == 3)
									<a href="{{ route('members.show', $user->id) }}" class="button-block">对方已经把你拉黑了</a>
									@elseif($data->status == 1)
										<input name="status" type="hidden" value="sender_block" />
										<input type="submit" class="button-resent"
											@if($user->sex == 'M')
											value="把他拉黑"
											@else(Auth::user()->sex == 'F')
											value="把她拉黑"
											@endif
										/>
									@elseif($data->status == 4)
										<input name="status" type="hidden" value="sender_recover" />
										<input type="submit" class="button-unclock" value="取消拉黑"
										/>
									@else
									<a href="{{ route('members.show', $user->id) }}" class="button-resent">
									再追一次</a>
									@endif
									@if($data->status == 0)
									<a href="{{ route('members.show', $user->id) }}" class="button-wait">
									静待缘分</a>
									@elseif($data->status == 1)
									<a href="javascript:;" class="remodal-bg button-blue" data-id="{{ $user->id }}" id="chat_start">开始聊天</a>
									@elseif($data->status == 2)
									<a href="javascript:;" class="button-block">已经拒绝</a>
									@elseif($data->status == 4)
									<a href="javascript:;" class="button-block">已经拉黑</a>
									@endif
								</div>
								{{ Form::close() }}
							</li>
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