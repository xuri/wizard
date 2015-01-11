@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{ $resourceName }}管理</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					@include('layout.notification')
				</div>

				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							{{ $resourceName }}帖子列表
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="{{-- dataTables-example --}}">
									<thead>
										<tr>
											<th>ID</th>
											<th style="text-align:center;">分类</th>
											<th>发帖用户</th>
											<th>标题</th>
											<th>评论数</th>
											<th>创建时间 {{ order_by('created_at', 'desc') }}</th>
											<th style="width:12.5em;text-align:center;">操作</th>
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
											<td>昵称：<a href="{{ route('users.edit', $user->id) }}" target="_blank" title="编辑或查看此用户资料" alt="编辑或查看此用户资料">{{ $user->nickname }}<a></td>
											@elseif($data->email)
											<td>邮箱：<a href="{{ route('users.edit', $user->id) }}" target="_blank" title="编辑或查看此用户资料" alt="编辑或查看此用户资料">{{ $user->email }}</a></td>
											@elseif($data->phone)
											<td>手机：<a href="{{ route('users.edit', $user->id) }}" target="_blank" title="编辑或查看此用户资料" alt="编辑或查看此用户资料">{{ $user->phone }}</a></td>
											@else
											<td>ID：<a href="{{ route('users.edit', $user->id) }}" target="_blank" title="编辑或查看此用户资料" alt="编辑或查看此用户资料">{{ $user->id }}</a></td>
											@endif
											<td>{{ close_tags(Str::limit($data->title, 20)) }}</td>
											<td class="center">{{ $comments->count() }}</td>
											<td class="center">{{ $data->created_at }}</td>
											<td class="center">
												<a href="{{ route('forum.show', $data->id) }}" class="btn btn-xs btn-info" target="_blank">查看</a>
												@if($data->top)
												<a href="{{ route($resource.'.untop', $data->id) }}" class="btn btn-xs btn-primary">取顶</a>
												@else
												<a href="{{ route($resource.'.top', $data->id) }}" class="btn btn-xs btn-success">置顶</a>
												@endif
												@if($data->block)
												<a href="{{ route($resource.'.forum.unclock', $data->id) }}" class="btn btn-xs btn-primary btn-outline">解锁</a>
												@else
												<a href="{{ route($resource.'.block', $data->id) }}" class="btn btn-xs btn-warning">屏蔽</a>
												@endif
												<a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="modal('{{ route($resource.'.destroy', $data->id) }}')">删除</a>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>

								{{ pagination($datas->appends(Input::except('page')), 'admin.paginator') }}

							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel -->
				</div>
				<!-- /.col-lg-12 -->
			</div>
		</div>
		<!-- /#page-wrapper -->

	</div>
	<!-- /#wrapper -->

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
