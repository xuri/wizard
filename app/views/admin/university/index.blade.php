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
							{{ $resourceName }}列表
						</div>
						<div class="panel-body">
							<div class="col-md-4">
							{{ Form::open(array('method' => 'get', 'class' => 'form-horizontal')) }}
								{{
									Form::select(
										'status',
										array('all' => '所有', '1' => '管理员', '0' => '普通用户'),
										Input::get('status', 'all'),
										array('class' => 'form-control')
									)
								}}
							</div>
							<div class="col-sm-4">
								{{
									Form::select(
										'target',
										array('email' => '邮箱'),
										Input::get('target', 'email'),
										array('class' => 'form-control')
									)
								}}</div>
							<div class="col-sm-4">
								<input type="text" class="input-light input-large brad valign-top search-box" placeholder="请输入搜索条件..." name="like" value="{{ Input::get('like') }}">
								<button class="btn btn-primary" style="margin: 0px;" type="submit">搜索</button>
							{{ Form::close() }}
							</div>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="{{-- dataTables-example --}}">
									<thead>
										<tr>
											<th>ID</th>
											<th style="text-align:center;">高校</th>
											<th>注册人数</th>
											<th>创建时间 {{ order_by('created_at', 'desc') }}</th>
											<th style="width:10.5em;text-align:center;">操作</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($datas as $data)
										<tr class="odd gradeX">
											<td>{{ $data->university_id }}</td>
											<td style="text-align:center;">{{ $data->university }}</td>
											<td class="center">11</td>
											<td class="center">{{ $data->created_at }}</td>
											<td class="center">
												@if($data->status)
												<a href="{{ route($resource.'.unread', $data->id) }}" class="btn btn-xs btn-success">已开通</a>
												@else
												<a href="{{ route($resource.'.read', $data->id) }}" class="btn btn-xs btn-warning">未开通</a>
												@endif
												<a href="{{ route($resource.'.show', $data->id) }}" class="btn btn-xs btn-info" target="_blank">查看</a>
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
