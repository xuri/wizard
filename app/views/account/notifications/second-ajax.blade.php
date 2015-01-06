<div id="courtship-mine">
	<ul class="clear">
		@foreach($forumNotifications as $forumNotification)
		<li class="clear">
			<span>39</span>
			<p>这个里面是内容绝对的内容sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss</p>
			<a class="date">{{ date("m-d H:m",strtotime($forumNotification->created_at)) }}</a>
			<i class="fa fa-trash-o"></i>
		</li>
		@endforeach
	</ul>
</div>
{{ pagination($forumNotifications->appends(Input::except('page')), 'layout.paginator') }}