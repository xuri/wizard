@include('layout.header')
@yield('content')

	@include('layout.navigation')
	@yield('content')

	<div class="content_1" id="1">
		<p> 这里不是拼脸的地方</br>
			寻找真爱请用
		</p>

		{{ HTML::image('assets/images/xin1.png', 'alt content', array('id' => 'xin1', 'class' => 'xin1')); }}
		{{ HTML::image('assets/images/xin2.png', 'alt content', array('id' => 'xin2', 'class' => 'xin2')); }}
		{{ HTML::image('assets/images/xin3.png', 'alt content', array('id' => 'xin3', 'class' => 'xin3')); }}
	</div>
	<div class="content_2" id="2">
		{{ HTML::image('assets/images/boy.png', 'alt content', array('id' => 'boy')); }}
		<p>这里是爱升华的地方</br>
			我们主张“心灵美”
		</p>
	</div>
	<div class="content_3" id="3">
		<div>
			<p>聘则为妻，妻者，夫之爱也</p>
			<p>爱，生活也</p>
		</div>
		{{ HTML::image('assets/images/girl.png', 'alt content', array('id' => 'girl')); }}
	</div>
	<div class="content_4" id="4">
		{{ HTML::image('assets/images/love.png'); }}
		<div>
			<p>这里不是商场</p>
			<p>我们真正为你寻找靠谱的爱</p>
		</div>
	</div>
	<div class="content_5" id="5">
		<div class="content_5_div">
			<a class="android left" href="#">安卓客户端下载</a>
			<a class="ios right" href="#">苹果客户端下载</a>
			<a href="{{ route('members.index') }}" class="EPS">立即体验</a>
		</div>
	</div>

	@include('layout.copyright')
	@yield('content')

@include('layout.footer')
@yield('content')