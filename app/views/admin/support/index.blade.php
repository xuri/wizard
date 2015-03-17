@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{ Lang::get('navigation.admin_support_management') }}</h1>
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
							{{ Lang::get('admin/support/index.feedback_table') }}
						</div>
						{{-- /.panel-heading --}}
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="{{-- dataTables-example --}}">
									<thead>
										<tr>
											<th>ID {{ order_by('id', 'desc') }}</th>
											<th style="text-align:center;">{{ Lang::get('admin/support/index.category') }} {{ order_by('category') }}</th>
											<th>{{ Lang::get('admin/support/index.user') }} {{ order_by('user_id') }}</th>
											<th>{{ Lang::get('admin/support/index.title') }} {{ order_by('title') }}</th>
											<th>{{ Lang::get('admin/support/index.created_at') }} {{ order_by('created_at') }}</th>
											<th style="width:6em;text-align:center;">{{ Lang::get('admin/support/index.status') }} {{ order_by('status') }}</th>
											<th style="width:7.5em;text-align:center;">{{ Lang::get('admin/support/index.operating') }}</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($datas as $data)
										<tr class="odd gradeX">
											<?php
												$user = User::where('id', $data->user_id)->first();

											?>
											<td>{{ $data->id }}</td>
											<td style="text-align:center;"><a href="{{ route('forum.index') }}">{{ $data->category }}</a></td>
											@if($user->nickname)
											<td>{{ Lang::get('admin/support/index.nickname') }}: <a href="{{ route('users.edit', $user->id) }}" target="_blank" title="{{ Lang::get('admin/support/index.profile') }}" alt="{{ Lang::get('admin/support/index.profile') }}">{{ $user->nickname }}<a></td>
											@elseif($data->email)
											<td>{{ Lang::get('admin/support/index.email') }}: <a href="{{ route('users.edit', $user->id) }}" target="_blank" title="{{ Lang::get('admin/support/index.profile') }}" alt="{{ Lang::get('admin/support/index.profile') }}">{{ $user->email }}</a></td>
											@elseif($data->phone)
											<td>{{ Lang::get('admin/support/index.phone') }}： <a href="{{ route('users.edit', $user->id) }}" target="_blank" title="{{ Lang::get('admin/support/index.profile') }}" alt="{{ Lang::get('admin/support/index.profile') }}">{{ $user->phone }}</a></td>
											@else
											<td>ID：<a href="{{ route('users.edit', $user->id) }}" target="_blank" title="{{ Lang::get('admin/support/index.profile') }}" alt="{{ Lang::get('admin/support/index.profile') }}">{{ $user->id }}</a></td>
											@endif
											<td class="center">{{ $data->title }}</td>
											<td class="center">{{ $data->created_at }}</td>
											<td class="center" style="text-align:center;">
												@if($data->status)
												<a href="{{ route($resource.'.unread', $data->id) }}" class="btn btn-xs btn-success">{{ Lang::get('admin/support/index.processed') }}</a>
												@else
												<a href="{{ route($resource.'.read', $data->id) }}" class="btn btn-xs btn-warning">{{ Lang::get('admin/support/index.unread') }}</a>
												@endif
											</td>
											<td class="center" style="text-align:center;">
												<a href="{{ route($resource.'.show', $data->id) }}" class="btn btn-xs btn-info" target="_blank">{{ Lang::get('admin/support/index.see') }}</a>
												<a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="modal('{{ route($resource.'.destroy', $data->id) }}')">{{ Lang::get('admin/support/index.delete') }}</a>
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
			'message' => Lang::get('system.delete_confirm') . Lang::get('admin/support/index.resourceName') . '?',
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