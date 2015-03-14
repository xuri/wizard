<div class="nav">
	<div class="nav_main">
		{{ HTML::image('assets/images/nav_main_bg.png', '', array('id' => 'nav_main')) }}
		<ul>
			<li class="cl-effect-1"><a href="{{ route('home') }}" class="a">{{ Lang::get('navigation.index') }}</a></li>
			<li class="cl-effect-1"><a href="{{ route('members.index') }}" class="a">{{ Lang::get('navigation.discover') }}</a></li>
			<li class="cl-effect-1"><a href="{{ route('forum.index') }}" class="a">{{ Lang::get('navigation.forum') }}</a></li>
			<li><a href="{{ route('account') }}" class="a">{{ HTML::image('assets/images/logo.png', '', array('width' => '40', 'style' => 'margin-top: 0.3em;')) }}</a></li>
			@if(Auth::guest()){{-- Guest --}}
			<li class="cl-effect-1"><a href="{{ route('signin') }}" class="a">{{ Lang::get('navigation.signin') }}</a></li>
			<li class="cl-effect-1"><a href="{{ route('signup') }}" class="a">{{ Lang::get('navigation.signup') }}</a></li>
			<li class="cl-effect-1"><a href="{{ route('home') }}/article/about.html" class="a">{{ Lang::get('navigation.about') }}</a></li>
			@elseif(! Auth::user()->is_admin){{-- User --}}
			<li class="cl-effect-1"><a href="{{ route('account') }}" class="a">{{ Lang::get('navigation.profile') }}</a></li>
			<li class="cl-effect-1"><a href="{{ route('home') }}/article/about.html" class="a">{{ Lang::get('navigation.about') }}</a></li>
			<li class="cl-effect-1"><a href="{{ route('signout') }}" class="a">{{ Lang::get('navigation.signout') }}</a></li>
			@elseif(Auth::user()->is_admin) {{-- Admin --}}
			<li class="cl-effect-1"><a href="{{ route('account') }}" class="a">{{ Lang::get('navigation.profile') }}</a></li>
			<li class="cl-effect-1"><a href="{{ route('home') }}/article/about.html" class="a">{{ Lang::get('navigation.about') }}</a></li>
			<li class="cl-effect-1"><a href="{{ route('signout') }}" class="a">{{ Lang::get('navigation.signout') }}</a></li>
			@endif
		</ul>
	</div>
</div>
{{-- HTML::script('assets/js/nav.js') --}}