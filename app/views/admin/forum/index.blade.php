@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{ Lang::get('navigation.admin_forum_management') }}</h1>
				</div>
				{{-- /.col-lg-12 --}}
			</div>
			{{-- /.row --}}
			<div class="row">
				<div class="col-lg-12">
					@include('layout.notification')
				</div>

				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							{{ Lang::get('admin/forum/index.table_of_posts_in_forum') }}
						</div>
						{{-- /.panel-heading --}}
						<div class="panel-body">
							{{ Form::open(array('method' => 'get')) }}
								<div class="input-group col-md-12" style="margin:0 0 1em 0">
									<span class="input-group-btn" style="width: 20%; padding: 0 10px 0 0;">
										<select class="form-control input-sm" name="category">
											<option value="">{{ Lang::get('admin/forum/index.forum_cat') }}</option>
											@foreach($categories as $category)
											<option value="{{ $category->id }}">{{ $category->name }}</option>
											@endforeach
										</select>
									</span>
									<input type="text" class="form-control input-sm" name="like" placeholder="{{ Lang::get('admin/forum/index.select_input') }}" value="{{ Input::get('like') }}">
									<span class="input-group-btn">
										<button class="btn btn-sm btn-default" type="submit" style="width:5em;">{{ Lang::get('admin/forum/index.select') }}</button>
									</span>
								</div>
							{{ Form::close() }}

							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="{{-- dataTables-example --}}">
									<thead>
										<tr>
											<th>ID {{ order_by('id', 'desc') }}</th>
											<th style="text-align:center;">{{ Lang::get('admin/forum/index.category') }}</th>
											<th>{{ Lang::get('admin/forum/index.category') }}</th>
											<th>{{ Lang::get('admin/forum/index.title') }}</th>
											<th>{{ Lang::get('admin/forum/index.comments') }}</th>
											<th>{{ Lang::get('admin/forum/index.created_at') }} {{ order_by('created_at', 'desc') }}</th>
											<th style="width:4.5em;text-align:center;">{{ Lang::get('admin/forum/index.fix_top') }} {{ order_by('top', 'desc') }}</th>
											<th style="width:5.5em;text-align:center;">{{ Lang::get('admin/forum/index.block') }} {{ order_by('block', 'desc') }}</th>
											<th style="width:8em;text-align:center;">{{ Lang::get('admin/forum/index.operating') }}</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$currentId = Auth::user()->id;
										?>
										@foreach ($datas as $data)
										<tr class="odd gradeX">
											<?php
												$user = User::where('id', $data->user_id)->first();
												$category = ForumCategories::where('id', $data->category_id)->first();
												$comments = ForumComments::where('post_id', $data->id)->get();
											?>
											<td>{{ $data->id }}</td>
											<td style="text-align:center;"><a href="{{ route('forum.index') }}">{{ $category->name }}</a></td>
											@if($user->nickname)
											<td>{{ Lang::get('admin/forum/index.nickname') }}: <a href="{{ route('users.edit', $user->id) }}" target="_blank" title="{{ Lang::get('admin/forum/index.profile') }}" alt="{{ Lang::get('admin/forum/index.profile') }}">{{ $user->nickname }}<a></td>
											@elseif($data->email)
											<td>E-mail: <a href="{{ route('users.edit', $user->id) }}" target="_blank" title="{{ Lang::get('admin/forum/index.profile') }}" alt="{{ Lang::get('admin/forum/index.profile') }}">{{ $user->email }}</a></td>
											@elseif($data->phone)
											<td>{{ Lang::get('admin/forum/index.phone') }}: <a href="{{ route('users.edit', $user->id) }}" target="_blank" title="{{ Lang::get('admin/forum/index.profile') }}" alt="{{ Lang::get('admin/forum/index.profile') }}">{{ $user->phone }}</a></td>
											@else
											<td>ID：<a href="{{ route('users.edit', $user->id) }}" target="_blank" title="{{ Lang::get('admin/forum/index.profile') }}" alt="{{ Lang::get('admin/forum/index.profile') }}">{{ $user->id }}</a></td>
											@endif
											<td>{{ close_tags(Str::limit($data->title, 20)) }}</td>
											<td class="center">{{ $comments->count() }}</td>
											<td class="center">{{ $data->created_at }}</td>
											<td class="center" style="text-align:center;">
												@if($data->top)
												<a href="{{ route($resource . '.untop', $data->id) }}" class="btn btn-xs btn-primary">{{ Lang::get('admin/forum/index.unfix_top') }}</a>
												@else
												<a href="{{ route($resource . '.top', $data->id) }}" class="btn btn-xs btn-success">{{ Lang::get('admin/forum/index.fix_top') }}</a>
												@endif
											</td>
											<td class="center" style="text-align:center;">
												@if($data->block)
												<a href="{{ route($resource . '.unlock', $data->id) }}" class="btn btn-xs btn-primary btn-outline">{{ Lang::get('admin/forum/index.unlock') }}</a>
												@else
												<a href="{{ route($resource . '.block', $data->id) }}" class="btn btn-xs btn-warning">{{ Lang::get('admin/forum/index.block') }}</a>
												@endif
											</td>
											<td class="center" style="text-align:center;">
												<a href="{{ route('forum.show', $data->id) }}" class="btn btn-xs btn-info" target="_blank">{{ Lang::get('admin/forum/index.show') }}</a>
												<a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="modal('{{ route($resource . '.destroy', $data->id) }}')">{{ Lang::get('admin/forum/index.delete') }}</a>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>

								{{ pagination($datas->appends(Input::except('page')), 'admin.paginator') }}

							</div>
							{{-- /.table-responsive --}}
						</div>
						{{-- /.panel-body --}}
					</div>
					{{-- /.panel --}}
				</div>
				{{-- /.col-lg-12 --}}
			</div>
		</div>
		{{-- /#page-wrapper --}}

	</div>
	{{-- /#wrapper --}}

	{{-- jQuery Version 1.11.0 --}}
	{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

	{{-- Bootstrap Core JavaScript --}}
	{{ HTML::script('assets/bootstrap-3.3.0/js/bootstrap.min.js') }}

	{{-- Metis Menu Plugin JavaScript --}}
	{{ HTML::script('assets/js/admin/plugins/metisMenu/metisMenu.min.js') }}

	{{-- DataTables JavaScript --}}
	{{ HTML::script('assets/js/admin/plugins/dataTables/jquery.dataTables.js') }}
	{{ HTML::script('assets/js/admin/plugins/dataTables/dataTables.bootstrap.js') }}

	{{-- Custom Theme JavaScript --}}
	{{ HTML::script('assets/js/admin/admin.js') }}

	{{-- Page-Level Demo Scripts - Tables - Use for reference --}}
	<script>
	$(document).ready(function() {
		$('#dataTables-example').dataTable();
	});
	</script>

	<?php
	$modalData['modal'] = array(
		'id'      => 'myModal',
		'title'   => '系统提示',
		'message' => '确认删除此'.$resourceName.'？',
		'footer'  =>
			Form::open(array('id' => 'real-delete', 'method' => 'delete')).'
				<button type="button" class="btn btn-sm btn-default btn-bordered" data-dismiss="modal">取消</button>
				<button type="submit" class="btn btn-sm btn-danger">确认删除</button>'.
			Form::close(),
	);
	?>
	@include('layout.modal', $modalData)
	<script>
		function modal(href) {
			$('#real-delete').attr('action', href);
			$('#myModal').modal();
		}
	</script>
</body>
</html>