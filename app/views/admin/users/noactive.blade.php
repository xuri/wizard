@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{ Lang::get('navigation.admin_user_noactive') }}</h1>
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
							{{ Lang::get('admin/users/noactive.table_title') }}
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>ID</th>
											<th>{{ Lang::get('admin/users/noactive.sender_id') }}</th>
											<th>{{ Lang::get('admin/users/noactive.sender') }}</th>
											<th>{{ Lang::get('admin/users/noactive.request_at') }}</th>
											<th>{{ Lang::get('admin/users/noactive.receiver_id') }}</th>
											<th>{{ Lang::get('admin/users/noactive.receiver') }}</th>
											<th>{{ Lang::get('admin/users/noactive.receiver_updated_at') }}</th>
											<th>{{ Lang::get('admin/users/noactive.receiver_contact') }}</th>
											<th style="width:5em;text-align:center;">{{ Lang::get('admin/users/index.operating') }}</th>
										</tr>
									</thead>
									<tbody>
										@foreach($datas as $data)
											<?php
												$receiver	= User::find($data->receiver_id);
												$sender		= User::find($data->sender_id);
												$passtime	= strtotime($data->updated_at) - strtotime($receiver->updated_at);
											?>
											@if($passtime > 259200)
											<tr>
												<td>{{ $data->id }}</td>
												<td><a href="{{ route('users.edit', $sender->id) }}" alt="{{ Lang::get('navigation.admin_user_management') }}" title="{{ Lang::get('navigation.admin_user_management') }}" target="_blank"><i class="fa fa-pencil-square-o"></i>&nbsp;{{ $sender->id }}</a></td>

												@if($sender->nickname)
													<td><a href="{{ route('members.show', $sender->id) }}" alt="{{ Lang::get('admin/users/noactive.user_profile') }}" title="{{ Lang::get('admin/users/noactive.user_profile') }}" target="_blank"><i class="fa fa-external-link"></i>&nbsp;{{ $sender->nickname }}</a></td>
												@else
													<td><a href="{{ route('members.show', $sender->id) }}" alt="{{ Lang::get('admin/users/noactive.user_profile') }}" title="{{ Lang::get('admin/users/noactive.user_profile') }}" target="_blank"><i class="fa fa-external-link"></i></a>&nbsp;{{ Lang::get('admin/users/noactive.no_nickname') }}</td>
												@endif

												<td>{{ $data->updated_at }}</td>
												<td><a href="{{ route('users.edit', $receiver->id) }}" alt="用户管理" title="用户管理" target="_blank"><i class="fa fa-pencil-square-o"></i>&nbsp;{{ $receiver->id }}</a></td>

												@if($receiver->nickname)
													<td><a href="{{ route('members.show', $receiver->id) }}" alt="{{ Lang::get('admin/users/noactive.user_profile') }}" title="{{ Lang::get('admin/users/noactive.user_profile') }}" target="_blank"><i class="fa fa-external-link"></i>&nbsp;{{ $receiver->nickname }}</a></td>
												@else
													<td><a href="{{ route('members.show', $receiver->id) }}" alt="{{ Lang::get('admin/users/noactive.user_profile') }}" title="{{ Lang::get('admin/users/noactive.user_profile') }}" target="_blank"><i class="fa fa-external-link"></i></a>&nbsp;{{ Lang::get('admin/users/noactive.no_nickname') }}</td>
												@endif

												<td>{{ $receiver->updated_at }}</td>

												@if($receiver->email)
													<td><a href="mailto:{{ $receiver->email }}" target="_blank"><i class="fa fa-envelope-o"></i></a>&nbsp;{{ $receiver->email }}</td>
												@else
													<td>{{ $receiver->phone }}</td>
												@endif

												@if($data->is_notify == 0)
													@if($receiver->email)
														<td><a href="{{ route('users.sms_notify', $receiver->id) }}" class="btn btn-xs btn-warning">{{ Lang::get('admin/users/noactive.email_notify') }}</a></td>
													@else
														<td><a href="{{ route('users.sms_notify', $receiver->id) }}" class="btn btn-xs btn-warning">{{ Lang::get('admin/users/noactive.sms_notify') }}</a></td>
													@endif
												@else
													<td><a href="{{ route('users.sms_notify', $receiver->id) }}" class="btn btn-xs btn-success">{{ Lang::get('admin/users/noactive.re_notify') }}</a></td>
												@endif
											</tr>
											@endif
										@endforeach
									</tbody>
								</table>

								{{ pagination($datas->appends(Input::except('page')), 'admin.paginator') }}

							</div>
						</div>
						{{-- /.panel-body --}}
					</div>
					{{-- /.panel --}}
				</div>
				{{-- /.col-lg-12 --}}
			</div>
			{{-- /.row --}}
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

	{{-- Custom Theme JavaScript --}}
	{{ HTML::script('assets/js/admin/admin.js') }}
</body>

</html>