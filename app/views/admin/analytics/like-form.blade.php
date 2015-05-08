@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{ Lang::get('navigation.admin_analytics_active_table') }}</h1>
				</div>
				{{-- /.col-lg-12 --}}
			</div>

			{{-- /.row --}}
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							网站用户互动情况综合数据汇总表
						</div>
						{{-- /.panel-heading --}}
						<div class="panel-body" style="font-size: 12px;">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>ID</th>
											<th>截止时间</th>
											<th>日互动数</th>
											<th>周互动数</th>
											<th>月互动数</th>
											<th>累计男追女</th>
											<th>累计女追男</th>
											<th>日男追女</th>
											<th>周男追女</th>
											<th>月男追女</th>
											<th>日女追男</th>
											<th>周女追男</th>
											<th>月女追男</th>
											<th>男追女成功率</th>
											<th>女追男成功率</th>
											<th>平均周期</th>
										</tr>
									<thead>
									<tbody>
										@foreach($analyticsLikes as $analyticsLike)
										<tr>
											<td>{{ $analyticsLike->id }}</td>
											<td>{{ date("Y年m月d日 H:m:s",strtotime($analyticsLike->created_at)) }}</td>
											<td>{{ $analyticsLike->daily_like }}</td>
											<td>{{ $analyticsLike->weekly_like }}</td>
											<td>{{ $analyticsLike->monthly_like }}</td>
											<td>{{ $analyticsLike->all_male_like }}</td>
											<td>{{ $analyticsLike->all_female_like }}</td>
											<td>{{ $analyticsLike->daily_male_like }}</td>
											<td>{{ $analyticsLike->weekly_male_like }}</td>
											<td>{{ $analyticsLike->monthly_male_like }}</td>
											<td>{{ $analyticsLike->daily_female_like }}</td>
											<td>{{ $analyticsLike->weekly_female_like }}</td>
											<td>{{ $analyticsLike->monthly_female_like }}</td>
											<td>{{ $analyticsLike->all_male_accept_ratio }}%</td>
											<td>{{ $analyticsLike->all_female_accept_ratio }}%</td>
											<td>{{ $analyticsLike->average_like_duration }}天</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
							{{-- /.table-responsive --}}
						</div>
						{{-- /.panel-body --}}
					</div>
					{{-- /.panel --}}
					{{ pagination($analyticsLikes->appends(Input::except('page')), 'admin.paginator') }}
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