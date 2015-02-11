<div class="renew_error"></div>
<div class="sgnin_top">
	@if(Auth::user()->nickname)
		@if($crenew)
			<div style="color: #FF9900;"><span>昵称 : </span>{{ Auth::user()->nickname }}</div>
		@else
			<div><span>昵称 : </span>{{ Auth::user()->nickname }}</div>
		@endif
	@else
		欢迎来到聘爱网
	@endif
	<div>
		<span>精灵豆 : </span><em>{{ Auth::user()->points }}</em><strong>　(每天为爱情正能量加油可以获取精灵豆哦)</strong>
		@if($profile->crenew)
		<span> &nbsp; &nbsp; 已连续签到 : </span><em>{{ $profile->crenew }}天</em>
		@endif
	</div>
</div>

<div class="sgnin_con">
	<div class="comeon">
		<span class="comeon_title">为爱情正能量加油</span>
		<a id="clickon" href="javascript:void(0);">加油</a>
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
					已加油<span>0</span>天
					@else
					已加油<span>{{ $profile->renew }}</span>天
					@endif
				</div>
			</div>
			<span class="num num1">0</span>
			<span class="num num2">25</span>
			<span class="num num3">50</span>
		</div>
	</div>
</div>