<div class="nav">

	<div class="nav_main">
		{{ HTML::image('assets/images/nav_main_bg.png', '', array('id' => 'nav_main')) }}

		<p>欢迎来到聘爱!
			@if(Auth::guest()){{-- Guest --}}
			<a href="{{ route('signin') }}" id="signIn">{{ Lang::get('navigation.signin') }}</a>
			<a href="#" id="signUp">/</a>
			<a href="{{ route('signup') }}" id="signUp">{{ Lang::get('navigation.signup') }}</a>
			@elseif(! Auth::user()->is_admin){{-- Users --}}
				@if(Auth::user()->nickname)
				<a href="{{ route('account') }}" id="signIn">{{ Auth::user()->nickname }}</a>
				@else
				<a href="{{ route('account') }}" id="signIn">{{ Lang::get('navigation.profile') }}</a>
				@endif
				<a href="#" id="signUp">/</a>
				<a href="{{ route('signout') }}" id="signUp">{{ Lang::get('navigation.signout') }}</a>
			@elseif(Auth::user()->is_admin) {{-- Admin --}}
				@if(Auth::user()->nickname)
				<a href="{{ route('account') }}" id="signIn">{{ Auth::user()->nickname }}</a>
				@else
				<a href="{{ route('account') }}" id="signIn">{{ Lang::get('navigation.profile') }}</a>
				@endif
				<a href="#" id="signUp">/</a>
				<a href="{{ route('admin') }}" id="signUp">{{ Lang::get('navigation.admin') }}</a>
				<a href="#" id="signUp">/</a>
				<a href="{{ route('signout') }}" id="signUp">{{ Lang::get('navigation.signout') }}</a>
			@endif
		</p>
	</div>
</div>