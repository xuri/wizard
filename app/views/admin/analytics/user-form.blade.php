@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">用户详情报表</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>

			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							网站用户注册情况综合数据汇总表
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body" style="font-size: 12px;">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>ID</th>
											<th>截止时间</th>
											<th>用户总数</th>
											<th>日活</th>
											<th>周活</th>
											<th>月活</th>
											<th>男用户 / 比例</th>
											<th>女用户 / 比例</th>
											<th>日活跃男用户</th>
											<th>周活跃男用户</th>
											<th>月活跃男用户</th>
											<th>日活跃女用户</th>
											<th>周活跃女用户</th>
											<th>月活跃女用户</th>
											<th>用户资料完整度比例</th>
											<th>网站注册</th>
											<th>Android注册</th>
											<th>iOS注册</th>
										</tr>
									<thead>
									<tbody>
										@foreach($analyticsUsers as $analyticsUser)
										<tr>
											<td>{{ $analyticsUser->id }}</td>
											<td>{{ date("Y年m月d日 H:m:s",strtotime($analyticsUser->created_at)) }}</td>
											<td>{{ $analyticsUser->all_user }}</td>
											<td>{{ $analyticsUser->daily_active_user }}</td>
											<td>{{ $analyticsUser->weekly_active_user }}</td>
											<td>{{ $analyticsUser->monthly_active_user }}</td>
											<td>{{ $analyticsUser->all_male_user }} / <?php echo number_format(($analyticsUser->all_male_user / $analyticsUser->all_user) * 100, 2); ?>%</td>
											<td>{{ $analyticsUser->all_female_user }} / <?php echo number_format(($analyticsUser->all_female_user / $analyticsUser->all_user) * 100, 2); ?>%</td>
											<td>{{ $analyticsUser->daily_active_male_user }}</td>
											<td>{{ $analyticsUser->weekly_active_male_user }}</td>
											<td>{{ $analyticsUser->monthly_active_male_user }}</td>
											<td>{{ $analyticsUser->daily_active_female_user }}</td>
											<td>{{ $analyticsUser->weekly_active_female_user }}</td>
											<td>{{ $analyticsUser->monthly_active_female_user }}</td>
											<td>{{ $analyticsUser->complete_profile_user_ratio }}%</td>
											<td>{{ $analyticsUser->from_web }}</td>
											<td>{{ $analyticsUser->from_android }}</td>
											<td>{{ $analyticsUser->from_ios }}</td>
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
					{{ pagination($analyticsUsers->appends(Input::except('page')), 'admin.paginator') }}
				</div>
				<!-- /.col-lg-12 -->
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
