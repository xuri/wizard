@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">用户好友关系</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>

			<!-- /.row -->
			<div class="row">
				<div class="panel-heading">
					以下是用户"{{ $data->nickname }}"的好友关系列表， <a href="{{ route('users.edit', $data->id) }}">点此返回用户编辑。</a>
				</div>
				<div class="col-lg-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							发出的好友邀请（追过的人）
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>对方昵称</th>
											<th>创建时间</th>
											<th>次数</th>
											<th>当前状态</th>
										</tr>
									</thead>
									<tbody>
										@foreach($sends as $send)
										<?php
											$receiver = User::where('id', $send->receiver_id)->first();
											$statusCode = $send->status;

											switch ($statusCode) {
												case '0':
													$status = "等待接受";
													break;

												case '1':
													$status = "对方已接受";
													break;

												case '2':
													$status = "对方已拒绝";
													break;

												case '3':
													$status = "对方已拉黑";
													break;

												case '4':
													$status = "已拉黑对方";
													break;
											}
										?>
										<tr>
											<td>{{ $count ++ }}</td>
											<td><a href="{{ route('members.show', $receiver->id) }}">{{ $receiver->nickname }}</a></td>
											<td>{{ $send->created_at }}</td>
											<td>{{ $send->count }}</td>
											<td>{{ $status }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel -->
				</div>
				<!-- /.col-lg-6 -->
				<div class="col-lg-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							接受的好友邀请（追此用户的人）
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>对方昵称</th>
											<th>创建时间</th>
											<th>次数</th>
											<th>当前状态</th>
										</tr>
									</thead>
									<tbody>
										@foreach($inboxs as $inbox)
										<?php
											$sender = User::where('id', $send->sender_id)->first();
											$statusCode = $send->status;

											switch ($statusCode) {
												case '0':
													$status = "等待接受";
													break;

												case '1':
													$status = "对方已接受";
													break;

												case '2':
													$status = "对方已拒绝";
													break;

												case '3':
													$status = "对方已拉黑";
													break;

												case '4':
													$status = "已拉黑对方";
													break;
											}
										?>
										<tr>
											<td>{{ $count ++ }}</td>
											<td><a href="{{ route('members.show', $sender->id) }}">{{ $sender->nickname }}</a></td>
											<td>{{ $inbox->created_at }}</td>
											<td>{{ $send->count }}</td>
											<td>{{ $status }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel -->
				</div>
				<!-- /.col-lg-6 -->
			</div>
			<!-- /.row -->
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

</body>

</html>
