@include('account.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="content" class="clear">
		<div class="con_title">个人中心</div>
		<div class="con_img">
			<span class="line1"></span>
			<span class="line2"></span>
			{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
		</div>

		<div id="wrap" class="clear">
			<div class="w_left">
				<ul class="w_nav">
					<li><a href="{{ route('account') }}" class="active a1 fa fa-tasks">&nbsp;&nbsp;&nbsp;我的资料</a></li>
					<li><a href="{{ route('account.sent') }}" class="a2 fa fa-heart-o">&nbsp;&nbsp;&nbsp;我追的人</a></li>
					<li><a href="{{ route('account.inbox') }}" class="a2 fa fa-star">&nbsp;&nbsp;&nbsp;追我的人</a></li>
					<li><a href="{{ route('account.notifications') }}" class="a3 fa fa-inbox">&nbsp;&nbsp;&nbsp;我的来信</a></li>
					<li><a href="{{ route('account.posts') }}" class="a3 fa fa-flag-o">&nbsp;&nbsp;&nbsp;我的帖子</a></li>
					<li><a href="{{ route('support.index') }}" class="a5 fa fa-life-ring">&nbsp;&nbsp;&nbsp;联系客服</a></li>
					<li><a href="{{ route('home') }}/article/about.html" class="a5 fa fa-bookmark">&nbsp;&nbsp;&nbsp;关于我们</a></li>

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
							<div><span>昵称 : </span>
								@if(Auth::user()->nickname)
									{{ Auth::user()->nickname }}
								@else
								欢迎来到聘爱网
								@endif
							</div>
							<div><span>精灵豆 : </span><em>{{ Auth::user()->points }}</em><strong>　(每天为爱情正能量加油可以获取精灵豆哦)</strong></div>
						</div>
						@include('account.points')
						@yield('content')
					</div>
				</div>


				{{-- Profile  --}}
				<div id="data">

					<a href="{{ route('account.complete') }}" class="editor">点击编辑</a>
					<div class="data_top clear">
						<span></span> {{-- Left pink section --}}
						<p>我的资料</p>
					</div>
					<table>
						<tr>
							<td class="data_td1">昵称：</td><td class="data_td2">
								@if(Auth::user()->nickname)
									{{ Auth::user()->nickname }}
								@else
									你还没有设置昵称，快去完善资料吧
								@endif
							</td>
						</tr>
						<tr>
							<td class="data_td1">性别：</td><td class="data_td2">
								@if(Auth::user()->sex == 'M')
								{{ HTML::image('assets/images/symbol.png') }}
								@elseif(Auth::user()->sex == 'F')
								{{ HTML::image('assets/images/g.jpg') }}
								@else
								?
								@endif
							</td>
						</tr>
						<tr>
							<td class="data_td1">出生年：</td><td class="data_td2">
							{{ Auth::user()->born_year }}
						</td>
						</tr>
						<tr>
							<td class="data_td1">学校：</td><td class="data_td2">
							{{ Auth::user()->school }}
						</td>
						</tr>
						<tr>
							<td class="data_td1">入学年：</td><td class="data_td2">{{ $profile->grade }}</td>
						</tr>
						<tr>
							<td class="data_td1">星座：</td><td class="data_td2 constellation">
								<img src="{{ route('home') }}/assets/images/preInfoEdit/constellation/{{ $constellationIcon }}" width="30" height="30">
								<span style="margin-left:50px;">{{ $constellationName }}</span></td>
						</tr>
						<tr>
							<td class="data_td1">标签：</td>
							<td class="data_td2 character">
								@foreach($tag_str as $tag)
								<span>{{ getTagName($tag) }}</span>
								@endforeach
							</td>
						</tr>
						<tr>
							<td class="data_td1 vertical_c">爱好：</td><td class="data_td2 vertical_c">
								{{ $profile->hobbies }}
							</td>
						</tr>
						<tr>
							<td class="data_td1">个人简介：</td><td class="data_td2">
								{{ $profile->self_intro }}
							</td>
						</tr>
						<tr class="end_tr">
							<td class="data_td1">真爱寄语：</td><td class="data_td2">{{ Auth::user()->bio }}</td>
						</tr>
						<tr class="love_problem">
							<td class="data_td1">爱情考验：</td><td class="data_td2">{{ $profile->question }}</td>
						</tr>
					</table>
				</div>

			</div>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

@include('account.footer')
@yield('content')