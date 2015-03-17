@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{ Lang::get('navigation.admin_school_management') }}</h1>
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
							{{ Lang::get('admin/university/index.school_table') }}
						</div>
						<div class="panel-body">
							{{ Form::open(array('method' => 'get')) }}
								<div class="input-group col-md-12" style="margin:0 0 1em 0">
									<span class="input-group-btn" style="width: 20%; padding: 0 10px 0 0;">
										<select class="form-control input-sm" name="province">
											<option value="">{{ Lang::get('admin/university/index.all_province') }}</option>
											@foreach($provinces as $province)
											<option value="{{ $province->id }}">{{ $province->province }}</option>
											@endforeach
										</select>
									</span>
									<span class="input-group-btn" style="width: 10%; padding: 0 10px 0 0;">
										{{
											Form::select(
												'status',
												array(
													'' => Lang::get('admin/university/index.all_school'),
													'0' => Lang::get('admin/university/index.closed'),
													'1' => Lang::get('admin/university/index.pending'),
													'2' => Lang::get('admin/university/index.opening')
												),
												Input::get('status'),
												array('class' => 'form-control input-sm')
											)
										}}
									</span>
									<input type="text" class="form-control input-sm" name="like" placeholder="{{ Lang::get('admin/university/index.select_input') }}" value="{{ Input::get('like') }}">
									<span class="input-group-btn">
										<button class="btn btn-sm btn-default" type="submit" style="width:5em;">{{ Lang::get('admin/university/index.select') }}</button>
									</span>
								</div>
							{{ Form::close() }}

							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="{{-- dataTables-example --}}">
									<thead>
										<tr>
											<th>ID {{ order_by('id', 'desc') }}</th>
											<th style="text-align:center;">{{ Lang::get('admin/university/index.school') }} {{ order_by('university', 'desc') }}</th>
											<th>{{ Lang::get('admin/university/index.users') }} <a href="{{ route('admin.university.order_by_users_desc') }}" class="glyphicon glyphicon-random"></a></th>
											<th>{{ Lang::get('admin/university/index.date') }} {{ order_by('created_at', 'desc') }}</th>
											<th style="width:7em;text-align:center;">{{ Lang::get('admin/university/index.status') }} {{ order_by('status', 'desc') }}</th>
											<th style="width:8em;text-align:center;">{{ Lang::get('admin/university/index.operating') }}</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($datas as $data)
										<tr class="odd gradeX">
											<td>{{ $data->id }}</td>
											<td style="text-align:center;">{{ $data->university }}</td>
											<td class="center">{{ User::where('school', $data->university)->count() }}</td>
											<td class="center">{{ $data->created_at }}</td>
											<td class="center" style="text-align:center;">
												@if($data->status == 2)
												<a href="{{ route($resource.'.close', $data->id) }}" class="btn btn-xs btn-success">{{ Lang::get('admin/university/index.opening') }}</a>
												@elseif($data->status == 1)
												<a href="{{ route($resource.'.open', $data->id) }}" class="btn btn-xs btn-primary">{{ Lang::get('admin/university/index.pending') }}</a>
												@elseif($data->status == 0 || $data->status = 0)
												<a href="{{ route($resource.'.open', $data->id) }}" class="btn btn-xs btn-warning">{{ Lang::get('admin/university/index.closed') }}</a>
												@endif
											</td>
											<td class="center" style="text-align:center;">
												<a href="{{ route($resource.'.edit', $data->id) }}" class="btn btn-xs btn-info">{{ Lang::get('admin/university/index.edit') }}</a>
												<a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="modal('{{ route($resource.'.destroy', $data->id) }}')">{{ Lang::get('admin/university/index.delete') }}</a>
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
		'title'   => Lang::get('system.system_prompt'),
		'message' => Lang::get('system.delete_confirm') . Lang::get('admin/university/index.resourceName') . '?',
		'footer'  =>
			Form::open(array('id' => 'real-delete', 'method' => 'delete')).'
				<button type="button" class="btn btn-sm btn-default btn-bordered" data-dismiss="modal">' . Lang::get('system.cancel') . '</button>
				<button type="submit" class="btn btn-sm btn-danger">' . Lang::get('system.delete_confirm') . '</button>'.
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