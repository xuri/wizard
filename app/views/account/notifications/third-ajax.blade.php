@foreach($systemNotifications as $systemNotification)
<li>
	{{ HTML::image('assets/images/logo3.jpg', '', array('class' => 'new_main_head')) }}
	{{ HTML::image('assets/images/login_bg.png', '', array('class' => 'new_main_sex')) }}
	<span class="new_main_name">管理员</span>
	<h6 class="new_main_school">聘爱网总部</h6>
	<span class="new_main_time">11-11 06:30</span>
	<p>您的消息未通过审核</p>
	<span class="new_main_state unread">查看</span>
</li>
@endforeach
{{ pagination($systemNotifications->appends(Input::except('page')), 'layout.paginator') }}