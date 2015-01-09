<div class="sgnin_con">
	<div class="comeon">
		<span class="comeon_title">为爱情正能量加油</span>
		<a id="clickon" href="javascript:;">加油</a>
		<div id="instr">
			<div>当你连续加油<span>15</span>天后，会得到代表(活跃用户标志)的<em>橙色昵称</em></div>
			<div>当你连续加油累积<span>30</span>天后，会得到由聘爱网送出的精美礼品。</span></div>
			<div>只要你相信真爱，就会惊喜不断，让我们一起为真爱加油助威吧</div>
		</div>
	</div>
	<div class="pillars">
		<div id="pillars_fixed">
			<div id="pillars_auto" style="width: {{ Auth::user()->points }}0px;">
				{{ HTML::image('assets/images/preInfoEdit/hert.png') }}
				<div>已加油<span>{{ $profile->renew }}</span>天</div>
			</div>
			<span class="num num1">0</span>
			<span class="num num2">25</span>
			<span class="num num3">50</span>
		</div>
	</div>
</div>