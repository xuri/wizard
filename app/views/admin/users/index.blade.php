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
						{{-- /.panel-heading --}}
						<div class="panel-body">
							{{ Form::open(array('method' => 'get')) }}
								<div class="input-group col-md-12" style="margin:0 0 1em 0">
									<span class="input-group-btn" style="width: 20%; padding: 0 10px 0 0;">
										<select class="form-control input-sm" name="province">
											<option value="">所有省份</option>
											@foreach($provinces as $province)
											<option value="{{ $province->id }}">{{ $province->province }}</option>
											@endforeach
										</select>
									</span>

									<span class="input-group-btn" style="width: 10%; padding: 0 10px 0 0;">
										{{
											Form::select(
												'sex',
												array(
													'' => '性别',
													'M' => '男',
													'F' => '女'
												),
												Input::get('sex'),
												array('class' => 'form-control input-sm')
											)
										}}
									</span>
									<input type="text" class="form-control input-sm" name="like" placeholder="模糊搜索ID、E-mail和昵称" value="{{ Input::get('like') }}">
									<span class="input-group-btn">
											<button class="btn btn-sm btn-default" type="submit" style="width:5em;">筛选</button>
									</span>
								</div>
							{{ Form::close() }}

							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="{{-- dataTables-example --}}">
									<thead>
										<tr>
											<th>ID</th>
											<th>身份 {{ order_by('is_admin') }}</th>
											<th style="text-align:center;">头像</th>
											<th>邮箱 / 手机</th>
											<th>昵称 {{ order_by('nickname') }}</th>
											<th>注册时间 {{ order_by('created_at', 'desc') }}</th>
											<th>最后登录时间 {{ order_by('signin_at') }}</th>
											<th style="width:9.5em;text-align:center;">操作</th>
										</tr>
									</thead>
									<tbody>
										<?php $currentId = Auth::user()->id; ?>
										@foreach ($datas as $data)
										<tr class="odd gradeX">
											<td>{{ $data->id }}</td>
											<td>{{ $data->is_admin ? '管理员' : '普通用户' }}</td>
											<td style="text-align:center;">
												<a href="{{ route('members.show', $data->id) }}">
													@if($data->portrait)
													{{ HTML::image('portrait/'.$data->portrait, '', array('width' => '20')) }}
													@else
													{{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('width' => '20')) }}
													@endif
												</a>
											</td>
											@if($data->email)
											<td>邮箱：<a href="mailto:{{ $data->email }}">{{ $data->email }}<a></td>
											@else
											<td>手机：<a href="tel:{{ $data->phone }}" value="{{ $data->phone }}">{{ $data->phone }}</a></td>
											@endif
											<td class="center">{{ $data->nickname }}</td>

											<td class="center">{{ $data->created_at }}</td>
											<td class="center">{{ $data->signin_at }}</td>
											<td class="center">
												@if($data->block)
													{{ Form::open(array(
														'autocomplete'	=> 'off',
														'action'		=> 'Admin_UserResource@unclock'
														))
													}}
													@if($data->id!=$currentId)
													<a href="{{ route($resource.'.edit', $data->id) }}" class="btn btn-xs btn-info">编辑</a>
													{{ Form::hidden('id', $data->id) }}
													<button type="submit" class="btn btn-xs btn-success">解锁</button>
													<a href="javascript:void(0);" class="btn btn-xs btn-danger" onclick="modal('{{ route($resource.'.destroy', $data->id) }}')">删除</a>
													@endif
													{{ Form::close() }}
												@else
													{{ Form::open(array(
														'autocomplete'	=> 'off',
														'action'		=> 'Admin_UserResource@block'
														))
													}}
													@if($data->id!=$currentId)
													<a href="{{ route($resource.'.edit', $data->id) }}" class="btn btn-xs btn-info">编辑</a>
													{{ Form::hidden('id', $data->id) }}
													<button type="submit" class="btn btn-xs btn-warning">锁定</button>
													<a href="javascript:void(0);" class="btn btn-xs btn-danger" onclick="modal('{{ route($resource.'.destroy', $data->id) }}')">删除</a>
													@endif
													{{ Form::close() }}
												@endif
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