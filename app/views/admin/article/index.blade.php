@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{ $resourceName }}管理
						<div class="pull-right">
							<a href="{{ route($resource.'.create') }}" class="btn btn-sm btn-primary">
								添加新{{ $resourceName }}
							</a>
						</div>
					</h1>
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
							{{ $resourceName }}列表
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							{{ Form::open(array('method' => 'get')) }}
								<div class="input-group col-md-9" style="margin:1em auto 0 auto;">
									<span class="input-group-btn" style="width:6em;">
										{{
											Form::select(
												'target',
												array('title' => '标题'),
												Input::get('target', 'title'),
												array('class' => 'form-control input-sm')
											)
										}}
									</span>
									<input type="text" class="form-control input-sm" name="like" placeholder="请输入搜索条件" value="{{ Input::get('like') }}">
									<span class="input-group-btn">
										<button class="btn btn-sm btn-default" type="submit" style="width:5em;">搜索</button>
									</span>
								</div>
							{{ Form::close() }}
						</div>
						<!-- /.panel-body -->

						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>标题 {{ order_by('title') }}</th>
											<th>创建时间 {{ order_by('created_at', 'desc') }}</th>
											<th style="width:10.5em;text-align:center;">操作</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($datas as $data)
										<tr>
											<td>
												<a href="{{ route('show', $data->slug) }}" target="_blank">
													<i class="glyphicon glyphicon-share" style="font-size: 0.8em;"></i>
												</a>
												{{ $data->title }}
											</td>
											<td>{{ $data->created_at }}</td>
											<td>
												<a href="{{ route($resource.'.edit', $data->id) }}" class="btn btn-xs btn-info">编辑</a>
												@if($data->status == 1)
												<a href="{{ route($resource.'.close', $data->id) }}" class="btn btn-xs btn-success">已发布</a>
												@else
												<a href="{{ route($resource.'.open', $data->id) }}" class="btn btn-xs btn-warning">未发布</a>
												@endif
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
