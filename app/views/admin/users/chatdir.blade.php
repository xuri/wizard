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
							以下是用户：{{ $data->nickname }}的聊天记录归档， <a href="{{ route('users.edit', $data->id) }}">点此返回用户编辑。</a>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>聊天记录每日归档</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$i = 1;
										echo '<tr>';
										foreach($directories as $chatrecord){
											$date					= substr($chatrecord, 0, 4) . '年' . substr($chatrecord, 4, -6) . '月' . substr($chatrecord, 6, -4) . '日';
											if ($i % 4 == 0)
												echo "</tr><tr>";
											echo '<td><a href="' . route('users.chatrecord', $data->id . '-' . $chatrecord) . '" title="点击查看详细聊天内容" alt="点击查看详细聊天内容" target="_blank">' . $date . '</a></td>';
											$i++;
										}
										echo '</tr>';
									?>
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