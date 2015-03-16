<div class="renew_error"></div>
<div class="sgnin_top">
	@if(Auth::user()->nickname)
		@if($crenew)
			<div style="color: #FF9900;"><span>{{ Lang::get('account/points.welcome') }}: </span>{{ Auth::user()->nickname }}</div>
		@else
			<div><span>{{ Lang::get('account/points.nickname') }}: </span>{{ Auth::user()->nickname }}</div>
		@endif
	@else
		{{ Lang::get('account/points.welcome') }}
	@endif
	<div>
		<span>{{ Lang::get('account/points.points') }}: </span><em>{{ Auth::user()->points }}</em><strong> ({{ Lang::get('account/points.renew_input') }})</strong>
		@if($profile->crenew)
		<span> &nbsp;{{ Lang::get('account/points.crenew') }}: </span><em>{{ $profile->crenew }}{{ Lang::get('account/points.days') }}</em>
		@endif
	</div>
</div>

<div class="sgnin_con">
	<div class="comeon">
		<span class="comeon_title">{{ Lang::get('account/points.renew_title') }}</span>
		<a id="clickon" href="javascript:void(0);">{{ Lang::get('account/points.earn') }}</a>
		<div id="instr">
			<div>当你连续加油<span>30</span>天后，会得到代表(活跃用户标志)的<em>橙色昵称</em></div>
			<!-- <div>当你连续加油累积<span>30</span>天后，会得到由聘爱送出的精美礼品。</span></div> -->
			<div>只要你相信真爱，就会惊喜不断，让我们一起为真爱加油助威吧</div>
		</div>
	</div>
	<div class="pillars">
		<div id="pillars_fixed">
			<div id="pillars_auto" style="width: {{ Auth::user()->points }}0px;">
				{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
				<div>
					@if($profile->renew == null)
					{{ Lang::get('account/points.has_renew') }}<span>0</span>{{ Lang::get('account/points.days') }}
					@else
					{{ Lang::get('account/points.has_renew') }}<span>{{ $profile->renew }}</span>{{ Lang::get('account/points.days') }}
					@endif
				</div>
			</div>
			<span class="num num1" style="width: 50px;">{{ Lang::get('account/points.points') }}:0</span>
			<span class="num num2">25</span>
			<span class="num num3">50</span>
		</div>
	</div>
</div>