@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">用户管理</h1>
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
							以下是符合“接收方至少3天未登录”的好友请求记录列表
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>ID</th>
										<th>发送方ID</th>
										<th>发送方</th>
										<th>最后请求时间</th>
										<th>接收方ID</th>
										<th>接收方</th>
										<th>接收方最后活动时间</th>
										<th>接收方联系方式</th>
									</tr>
								</thead>
								<tbody>
									@foreach($datas as $data)
										<?php
											$receiver = User::find($data->receiver_id);
											$sender = User::find($data->sender_id);
											$passtime = strtotime($data->updated_at) - strtotime($receiver->updated_at);
										?>
										@if($passtime > 259200)
										<tr>
											<td>{{ $data->id }}</td>
											<td><a href="{{ route('users.edit', $sender->id) }}" alt="用户管理" title="用户管理" target="_blank"><i class="fa fa-pencil-square-o"></i>&nbsp;{{ $sender->id }}</a></td>

											@if($sender->nickname)
												<td><a href="{{ route('members.show', $sender->id) }}" alt="查看资料" title="查看资料" target="_blank"><i class="fa fa-external-link"></i>&nbsp;{{ $sender->nickname }}</a></td>
											@endif

											<td>{{ $data->updated_at }}</td>
											<td><a href="{{ route('users.edit', $receiver->id) }}" alt="用户管理" title="用户管理" target="_blank"><i class="fa fa-pencil-square-o"></i>&nbsp;{{ $receiver->id }}</a></td>

											@if($receiver->nickname)
												<td><a href="{{ route('members.show', $receiver->id) }}" alt="查看资料" title="查看资料" target="_blank"><i class="fa fa-external-link"></i>&nbsp;{{ $receiver->nickname }}</a></td>
											@endif

											<td>{{ $receiver->updated_at }}</td>

											@if($receiver->email)
												<td><a href="mailto:{{ $receiver->email }}" target="_blank"><i class="fa fa-envelope-o"></i></a>&nbsp;{{ $receiver->email }}</td>
											@else
												<td>{{ $receiver->phone }}</td>
											@endif
										</tr>
										@endif
									@endforeach
								</tbody>
							</table>
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