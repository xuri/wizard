@foreach($friendNotifications as $friendNotification)
<?php
	$sender			= User::where('id', $friendNotification->sender_id)->first();
	$notifications	= getNotification($friendNotification->category, $friendNotification->sender_id);
?>
<li class="item">
	<a href="{{ route('members.show', $sender->id) }}">
		@if($sender->portrait)
		{{ HTML::image('portrait/'.$sender->portrait, '', array('class' => 'new_main_head')) }}
		@else
		{{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('class' => 'new_main_head')) }}
		@endif
	</a>
	@if($sender->sex == 'M')
	{{ HTML::image('assets/images/symbol.png', '', array('class' => 'new_main_sex')) }}
	@else
	{{ HTML::image('assets/images/g.jpg', '', array('class' => 'new_main_sex')) }}
	@endif

	<a href="{{ route('members.show', $sender->id) }}" class="new_main_name">{{ $sender->nickname }}</a>
	<h3 class="new_main_school">{{ $sender->school }}</h6>
	<span class="new_main_time">{{ date("m-d H:m",strtotime($friendNotification->created_at)) }}</span>
	<p>{{ $notifications['content'] }}</p>
	<a href="{{ route('members.show', $sender->id) }}" class="new_main_state unread">查看</a>
</li>
@endforeach
{{ pagination($friendNotifications->appends(Input::except('page')), 'layout.paginator') }}