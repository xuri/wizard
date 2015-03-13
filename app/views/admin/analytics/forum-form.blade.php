@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{ Lang::get('navigation.admin_analytics_forum_table') }}</h1>
				</div>
				{{-- /.col-lg-12 --}}
			</div>

			{{-- /.row --}}
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							网站论坛情况综合数据汇总表
						</div>
						{{-- /.panel-heading --}}
						<div class="panel-body" style="font-size: 12px;">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>ID</th>
											<th>截止时间</th>
											<th>发帖总数</th>
											<th>分类一</th>
											<th>分类二</th>
											<th>分类三</th>
											<th>日发帖</th>
											<th>分类一日发帖</th>
											<th>分类二日发帖</th>
											<th>分类三日发帖</th>
											<th>日男用户发帖</th>
											<th>日女用户发帖</th>
										</tr>
									<thead>
									<tbody>
										@foreach($analyticsForums as $analyticsForum)
										<tr>
											<td>{{ $analyticsForum->id }}</td>
											<td>{{ date("Y年m月d日 H:m:s",strtotime($analyticsForum->created_at)) }}</td>
											<td>{{ $analyticsForum->all_post }}</td>
											<td>{{ $analyticsForum->cat1_post }}</td>
											<td>{{ $analyticsForum->cat2_post }}</td>
											<td>{{ $analyticsForum->cat3_post }}</td>
											<td>{{ $analyticsForum->dailypost }}</td>
											<td>{{ $analyticsForum->cat1_daily_post }}</td>
											<td>{{ $analyticsForum->cat2_daily_post }}</td>
											<td>{{ $analyticsForum->cat3_daily_post }}</td>
											<td>{{ $analyticsForum->daily_male_post }}</td>
											<td>{{ $analyticsForum->daily_female_post }}</td>
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
					{{ pagination($analyticsForums->appends(Input::except('page')), 'admin.paginator') }}
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