<div id="courtship-mine">
    <ul class="clear">
        @foreach($forumNotifications as $forumNotification)
        <?php
            $post = ForumPost::find($forumNotification->post_id);
        ?>
        <li class="clear">
            @if($forumNotification->category == 6)
            <a href="{{ route('forum.show', $forumNotification->post_id) }}"><p>有人评论了你的帖子“{{ $post->title }}”，快去查看一下吧。</p></a>
            @else
            <a href="{{ route('forum.show', $forumNotification->post_id) }}"><p>有人回复了你的评论，快去查看一下吧。</p></a>
            @endif
            <a class="date">{{ date("m-d H:m",strtotime($forumNotification->created_at)) }}</a>
            <i class="fa fa-trash-o"></i>
        </li>
        @endforeach
    </ul>
</div>
{{ pagination($forumNotifications->appends(Input::except('page')), 'layout.paginator') }}