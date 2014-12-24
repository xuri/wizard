<ul id="bbs_main_clinic" class="bbs_main">
	@foreach($items as $post)
	<li class="bbs_main_boy">
		<a href="{{ route('forum.show', $post->id) }}" target="_blank">{{ $post->title }}</a>
		<p>{{ $post->content }}</p>
		<span class="bbs_main_look">{{ ForumComments::where('post_id', $post->id)->count() }}</span>
		<span class="bbs_main_time">{{ date("H:m",strtotime($post->created_at)) }}</span>
	</li>
	@endforeach
</ul>

{{ pagination($items->appends(Input::except('page')), 'layout.paginator') }}

{{ $errors->first('title', '<div class="callout-warning">:message</div>') }}
{{ $errors->first('content', '<div class="callout-warning">:message</div>') }}
{{ Form::open(array(
	'class'			=> 'bbs_bottom',
	'autocomplete' 	=> 'off',
	'action'		=> 'ForumController@postNew'
	))
}}
	<input name="category_id" type="hidden" value="{{ $categoryCode }}" />
	<div class="bbs_bottom_new lu_left">
		{{ HTML::image('assets/images/release.png') }}
		<span>发表新帖</span>
	</div>
	<input class="bbs_bottom_title lu_left" type="text" name="title" placeholder="添加题目" value="{{ Input::old('title') }}" required="required">
	<br />
	<br />
	<br />
	{{ Umeditor::css() }}
	{{ Umeditor::content(Input::old('content'), ['id'=> $editorCode, 'class'=>'myEditor text-umeditor bbs_bottom', 'name' => 'content', 'height' => '220']) }}
	{{ Umeditor::js() }}
	{{ Form::submit('发表', array('class' => 'bbs_bottom_btn')) }}
{{ Form::close() }}

<script type="text/javascript">
	// Instantiate editor
	var um = UM.getEditor('{{ $editorCode }}');
</script>