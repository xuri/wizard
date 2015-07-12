@foreach($systemNotifications as $systemNotification)

<?php
    $data = NotificationsContent::find($systemNotification->id);
?>
<li class="item">
    {{ HTML::image('assets/images/logo3.jpg', '', array('class' => 'new_main_head')) }}

    <p class="list_3">系统消息 {{ $data->created_at }}</p>
    <p class="list_3">{{ $data->content }}</p>
</li>
@endforeach

{{ pagination($systemNotifications->appends(Input::except('page')), 'layout.paginator') }}