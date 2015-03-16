@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h3>
						{{ $resourceName }}管理
						<div class="pull-right">
							<a href="{{ route($resource.'.create') }}" class="btn btn-sm btn-primary">
								添加新{{ $resourceName }}
							</a>
						</div>
					</h3>
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
							{{ $resourceName }}列表
						</div>
						{{-- /.panel-heading --}}

						<div class="panel-body">
							<div class="table-responsive">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>排序</th>
												<th>名称</th>
												<th>创建时间</th>
												<th style="width:7em;text-align:center;">操作</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($datas as $data)
											<tr>
												<td>{{ $data->sort_order }}</td>
												<td>{{ $data->name }}</td>
												<td>{{ $data->created_at }}</td>
												<td>
													<a href="{{ route($resource.'.edit', $data->id) }}" class="btn btn-xs">编辑</a>
													<a href="javascript:void(0)" class="btn btn-xs btn-danger"
														 onclick="modal('{{ route($resource.'.destroy', $data->id) }}')">删除</a>
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>

									{{ pagination($datas->appends(Input::except('page')), 'admin.paginator') }}

								</div>
							</div>
						</div>

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
