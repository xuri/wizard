@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">控制面板</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="row">
								<div class="col-xs-3">
									<i class="fa fa-support fa-5x"></i>
								</div>
								<div class="col-xs-9 text-right">
									<div class="huge">{{ $unreadSupport }}</div>
									<div>未读反馈</div>
								</div>
							</div>
						</div>
						<a href="{{ route('admin.support.index') }}">
							<div class="panel-footer">
								<span class="pull-left">查看详情</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="panel panel-green">
						<div class="panel-heading">
							<div class="row">
								<div class="col-xs-3">
									<i class="fa fa-tasks fa-5x"></i>
								</div>
								<div class="col-xs-9 text-right">
									<div class="huge">{{ $totalUser }}</div>
									<div>注册用户</div>
								</div>
							</div>
						</div>
						<a href="{{ route('users.index') }}">
							<div class="panel-footer">
								<span class="pull-left">用户管理</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="panel panel-yellow">
						<div class="panel-heading">
							<div class="row">
								<div class="col-xs-3">
									<i class="fa fa-male fa-5x"></i>
								</div>
								<div class="col-xs-9 text-right">
									<div class="huge">{{ $maleUser }}</div>
									<div>男用户</div>
								</div>
							</div>
						</div>
						<a href="{{ route('analytics.usercharts') }}">
							<div class="panel-footer">
								<span class="pull-left">用户详细趋势图</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="panel panel-red">
						<div class="panel-heading">
							<div class="row">
								<div class="col-xs-3">
									<i class="fa fa-female fa-5x"></i>
								</div>
								<div class="col-xs-9 text-right">
									<div class="huge">{{ $femaleUser }}</div>
									<div>女用户</div>
								</div>
							</div>
						</div>
						<a href="{{ route('analytics.userform') }}">
							<div class="panel-footer">
								<span class="pull-left">用户详情报表</span>
								<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
								<div class="clearfix"></div>
							</div>
						</a>
					</div>
				</div>
			</div>
			<!-- /.row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-bar-chart-o fa-fw"></i> 用户数据统计图例
							<div class="pull-right">
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
										其它选项
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu pull-right" role="menu">
										<li><a href="{{ route('analytics.forumcharts') }}">论坛活动趋势图</a>
										</li>
										<li class="divider"></li>
										<li><a href="{{ route('analytics.likecharts') }}">用户互动趋势图</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="flot-chart">
								<div class="flot-chart-content" id="flot-line-chart-1"></div>
							</div>
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel -->
				</div>
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

	{{-- Flot Charts JavaScript --}}
	{{ HTML::script('assets/js/admin/plugins/flot/excanvas.min.js') }}
	{{ HTML::script('assets/js/admin/plugins/flot/jquery.flot.js') }}
	{{ HTML::script('assets/js/admin/plugins/flot/jquery.flot.pie.js') }}
	{{ HTML::script('assets/js/admin/plugins/flot/jquery.flot.resize.js') }}
	{{ HTML::script('assets/js/admin/plugins/flot/jquery.flot.tooltip.min.js') }}

	<script>
	{{-- User Basic Analytics Section --}}
	$(function() {
		var offset = 0;
		plot();

		function plot() {
			var obj = {{ $userBasicAnalytics }};

			seriesData = [];

			for (var prop in obj) {
				seriesData.push({label: prop, data:$.map(obj[prop], function(i,j){
					 return [[new Date(i[0],i[1]-1, i[2]-1).getTime(), i[3]]];
				})});
			}

			var options = {
				series: {
					shadowSize: 4, {{-- Line shadow --}}
					lines: {
						show: true
					},
					points: {
						show: true
					}
				},
				grid: {
					hoverable: true {{-- IMPORTANT! this is needed for tooltip to work --}}
				},
				xaxis: { mode: "time", timeformat: "%y-%m-%d" },
				tooltip: true,
				tooltipOpts: {
					content: "截止 %x.1 %s： %y.4",
					shifts: {
						x: -60,
						y: 25
					}
				}
			};

			var plotObj = $.plot($("#flot-line-chart-1"), seriesData, options);
		}
	});
	</script>

	{{-- Custom Theme JavaScript --}}
	{{ HTML::script('assets/js/admin/admin.js') }}


</body>

</html>
