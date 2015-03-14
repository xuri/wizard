@include('support.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div id="content" class="clear">
		<div class="con_title">{{ Lang::get('navigation.support') }}</div>
		<div class="con_img">
			<span class="line1"></span>
			<span class="line2"></span>
			{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
		</div>

		<div id="wrap" class="clear">
			<div class="w_left">
				<ul class="w_nav">
					<li><a href="{{ route('account') }}" class="a1 fa fa-tasks">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.profile') }}</a></li>
					<li><a href="{{ route('account.sent') }}" class="a2 fa fa-heart-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.friends') }}</a></li>
					<li><a href="{{ route('account.inbox') }}" class="a2 fa fa-star">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.followers') }}</a></li>
					<li><a href="{{ route('account.notifications') }}" class="a3 fa fa-inbox">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.inbox') }}</a></li>
					<li><a href="{{ route('account.posts') }}" class="a3 fa fa-flag-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.my_posts') }}</a></li>
					<li><a href="{{ route('support.index') }}" class="a5 fa fa-life-ring active">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.support') }}</a></li>
					<li><a href="{{ route('home') }}/article/about.html" class="a5 fa fa-bookmark-o">&nbsp;&nbsp;&nbsp;{{ Lang::get('navigation.about') }}</a></li>
				</ul>
				<div id="download">
					<div>移动客户端下载</div>
					{{ HTML::image('assets/images/preInfoEdit/app.png') }}
				</div>
			</div>
			<div class="w_right">

				@if ($message = Session::get('success'))
					<div class="callout-warning">
						<a class="fa fa-check-circle" style="color: #ef698a"></a> {{ $message }}
					</div>
				@endif
				@if ($message = Session::get('error'))
					<div class="callout-warning">
						<a class="fa fa-times-circle" style="color: #ef698a"></a> {{ $message }}
					</div>
				@endif
				{{ $errors->first('content', '<div class="callout-warning"><a class="fa fa-times-circle" style="color: #ef698a"></a> :message</div>') }}


				<div id="data">

					<div class="data_top clear">
						<span></span> {{-- Left pink border --}}
						<p><a class="fa fa-phone"></a> 电话服务：15636129303</p>

					</div>
					<div class="content">
						<p>如您在使用过程中，遇到问题需要系客服，我们会第一时间为您解决。电话服务时间：工作日（8：00 - 18：00）。</p>
					</div>
					<div class="data_top clear">
						<span></span> {{-- Left pink border --}}
						<p><a class="fa fa-qq"></a> 客服QQ：523591643</p>
					</div>
					<div class="content">
						<p>QQ服务时间：每天（8：00 - 18：00）。</p>
					</div>
					<div class="data_top clear">
						<span></span> {{-- Left pink border --}}
						<p><a class="fa fa-envelope-o"></a> 客服邮箱：support@pinai521.com</p>
					</div>
					<div class="content">
						<p>如您对“聘爱”有任何意见或建议，欢迎发送邮件向我们反馈，您将在一个工作日内收到回复。</p>
					</div>
					<div class="data_top clear">
						<span></span> {{-- Left pink border --}}
						<p><a class="fa fa-quote-left"></a> 意见反馈</p>
					</div>
					<div class="content">
						{{ Form::open() }}
						<p>您还可以通过下面的在线反馈，将您遇到的需要帮助的问题发送给我们，我们会及时为您解决。</p>
						<textarea class="support" name="content" rows="6"></textarea>
						<input type="submit" value="发表" class="submit_support">
						{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

@include('support.footer')
@yield('content')