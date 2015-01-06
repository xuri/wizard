@if(Auth::guest())
<style>
body, html {
	background-image: url("{{ route('home') }}/assets/images/themes/background_1.png");
	background-repeat: repeat;
}
</style>
@elseif(Auth::user()->sex == 'F')
<style>
body, html {
	background-image: url("{{ route('home') }}/assets/images/themes/background_1.png");
	background-repeat: repeat;
}
</style>
@elseif(Auth::user()->sex == 'M')
<style>
body, html {
	background-image: url("{{ route('home') }}/assets/images/themes/background_2.png");
	background-repeat: repeat;
}
</style>
@else
<style>
body, html {
	background-image: url("{{ route('home') }}/assets/images/themes/background_1.png");
	background-repeat: repeat;
}
</style>
@endif