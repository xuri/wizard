<!DOCTYPE html>
<html lang="{{ Session::get('language', Config::get('app.locale')) }}">

<head>
	<title>{{ Lang::get('navigation.pinai') }}</title>

	@include('layout.meta')
	@yield('content')

	{{-- Bootstrap Core CSS --}}
	{{ HTML::style('assets/bootstrap-3.3.0/css/bootstrap.min.css') }}


	{{-- MetisMenu CSS --}}
	{{ HTML::style('assets/css/admin/plugins/metisMenu/metisMenu.min.css') }}


	{{-- Timeline CSS --}}
	{{ HTML::style('assets/css/admin/plugins/timeline.css') }}

	{{-- Custom CSS --}}
	{{ HTML::style('assets/css/admin/admin.css') }}


	{{-- Morris Charts CSS --}}
	{{ HTML::style('assets/css/admin/plugins/morris.css') }}

	{{-- Custom Fonts --}}
	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	{{ Minify::stylesheet(array(
		'/assets/css/cms-nav.css'
	)) }}

	{{-- jQuery Version 1.11.0 --}}
	{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}
</head>
<body>

	<div id="wrapper">
		@if(Agent::isMobile())
			<style type="text/css">
				img {
					max-width: 290px;
				}
			</style>
		@else
			@include('layout.navigation')
			@yield('content')
		@endif

		@if(Agent::isMobile())
		@else
			<div class="row row-offcanvas row-offcanvas-right" style="margin-top: 8%">
		@endif
		        <div class="col-md-8 col-md-offset-2">

		            <div class="row">

		                <div class="col-12 col-sm-12 col-lg-12 panel">


							<div class="row">
								<div class="col-lg-12">
									<h1 class="page-header">{{ Lang::get('admin/support/promotion.promotion_management') }}</h1>
								</div>
								{{-- /.col-lg-12 --}}
							</div>
							{{-- /.row --}}
							<div class="row">
								<div class="col-lg-12">
									@include('layout.notification')
								</div>

								<div class="col-lg-6">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover" id="{{-- dataTables-example --}}">
											<thead>
												<tr>
													<th colspan="2" style="text-align:center;">{{ Lang::get('admin/support/promotion.complete_profile') }}</th>
												</tr>
												<tr>
													<th style="text-align:center;">{{ Lang::get('admin/support/promotion.master_agent') }} ID</th>
													<th style="text-align:center;">{{ Lang::get('admin/support/promotion.total_users') }}</th>
												</tr>
											</thead>
											<tbody>
												<?php

													// Foreach master agent ID index of complete profile user list
													foreach ($completeProfileUserListAnalyticsIndexArray as $completeProfileUserListAnalyticsIndexArrayKey) {

														// Initialization register user count of complete profile user list
														$completeProfileUserListAnalyticsSum = 0;

														// Calculate register user count according to master agent ID of complete profile user list
														foreach ($completeProfileUserListAnalytics as $key => $completeProfileUserListAnalyticsValue)
														{
															// Determin masert agent ID is in master agent ID index array
														    if (substr($key, 0, -2) == $completeProfileUserListAnalyticsIndexArrayKey)

														    	// The cumulative number of users
														        $completeProfileUserListAnalyticsSum += $completeProfileUserListAnalyticsValue;
														}

														// Print result
														echo '<tr class="odd gradeX"><td>' . $completeProfileUserListAnalyticsIndexArrayKey . '</td><td>' . $completeProfileUserListAnalyticsSum . '</td></tr>';
													}
												?>
											</tbody>
										</table>

										<table class="table table-striped table-bordered table-hover" id="{{-- dataTables-example --}}">
											<thead>
												<tr>
													<th colspan="2" style="text-align:center;">{{ Lang::get('admin/support/promotion.complete_profile') }}</th>
												</tr>
												<tr>
													<th style="text-align:center;">{{ Lang::get('admin/support/promotion.agent') }} ID</th>
													<th style="text-align:center;">{{ Lang::get('admin/support/promotion.users') }}</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($completeProfileUserList as $completeProfileUserListKey => $completeProfileUserListVaule)
												<tr class="odd gradeX">
													<td>{{ $completeProfileUserList[$completeProfileUserListKey] }}</td>
													<td>{{ Support::whereRaw("content regexp '^[0-9]{3,4}$'")
														->where('content', $completeProfileUserList[$completeProfileUserListKey])
														->whereHas('hasOneUser', function($hasUncompleteProfile) {
														$hasUncompleteProfile->whereNotNull('school')
																->whereNotNull('bio')
																->whereNotNull('portrait')
																->whereNotNull('born_year');
														})
														->distinct()
														->get(array('user_id'))
														->count() }}</td>
												</tr>
												@endforeach
											</tbody>
										</table>

									</div>
									{{-- /.table-responsive --}}
								</div>
								{{-- /.col-lg-6 --}}

								<div class="col-lg-6">
									<div class="table-responsive">

										<table class="table table-striped table-bordered table-hover" id="{{-- dataTables-example --}}">
											<thead>
												<tr>
													<th colspan="2" style="text-align:center;">{{ Lang::get('admin/support/promotion.complete_profile') }}</th>
												</tr>
												<tr>
													<th style="text-align:center;">{{ Lang::get('admin/support/promotion.master_agent') }} ID</th>
													<th style="text-align:center;">{{ Lang::get('admin/support/promotion.total_users') }}</th>
												</tr>
											</thead>
											<tbody>
												<?php

													// Foreach master agent ID index of uncomplete profile user list
													foreach ($uncompleteProfileUserListAnalyticsIndexArray as $uncompleteProfileUserListAnalyticsIndexArrayKey) {

														// Initialization register user count of uncomplete profile user list
														$uncompleteProfileUserListAnalyticsSum = 0;

														// Calculate register user count according to master agent ID of uncomplete profile user list
														foreach ($uncompleteProfileUserListAnalytics as $key => $uncompleteProfileUserListAnalyticsValue)
														{
															// Determin masert agent ID is in master agent ID index array
														    if (substr($key, 0, -2) == $uncompleteProfileUserListAnalyticsIndexArrayKey)

														    	// The cumulative number of users
														        $uncompleteProfileUserListAnalyticsSum += $uncompleteProfileUserListAnalyticsValue;
														}

														// Print result
														echo '<tr class="odd gradeX"><td>' . $uncompleteProfileUserListAnalyticsIndexArrayKey . '</td><td>' . $uncompleteProfileUserListAnalyticsSum . '</td></tr>';
													}
												?>
											</tbody>
										</table>

										<table class="table table-striped table-bordered table-hover" id="{{-- dataTables-example --}}">
											<thead>
												<tr>
													<th colspan="2" style="text-align:center;">{{ Lang::get('admin/support/promotion.uncomplete_profile') }}</th>
												</tr>
												<tr>
													<th style="text-align:center;">{{ Lang::get('admin/support/promotion.agent') }} ID</th>
													<th style="text-align:center;">{{ Lang::get('admin/support/promotion.users') }}</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($uncompleteProfileUserList as $uncompleteProfileUserListKey => $uncompleteProfileUserListVaule)
												<tr class="odd gradeX">
													<td>{{ $uncompleteProfileUserList[$uncompleteProfileUserListKey] }}</td>
													<td>{{ Support::whereRaw("content regexp '^[0-9]{3,4}$'")
														->where('content', $uncompleteProfileUserList[$uncompleteProfileUserListKey])
														->distinct()
														->get(array('user_id'))
														->count() -
														Support::whereRaw("content regexp '^[0-9]{3,4}$'")
															->where('content', $uncompleteProfileUserList[$uncompleteProfileUserListKey])
															->whereHas('hasOneUser', function($hasUncompleteProfile) {
															$hasUncompleteProfile->whereNotNull('school')
																	->whereNotNull('portrait')
																	->whereNotNull('born_year');
															})
															->distinct()
															->get(array('user_id'))
															->count() }}</td>
												</tr>
												@endforeach
											</tbody>
										</table>

									</div>
									{{-- /.table-responsive --}}
								</div>
								{{-- /.col-lg-12 --}}
							</div>

		                </div>{{--/span--}}
		            </div>{{--/row--}}
		        </div>{{--/span--}}



		    </div>{{--/row--}}
		</div>
		{{-- /#page-wrapper --}}

		@if(Agent::isMobile())
			@include('layout.analytics')
			@yield('content')
		@else
			@include('layout.copyright')
			@yield('content')
			<style>
				span {
					white-space: normal !important;
				}

				div.footer {
					text-align: center;
					color: #FFF;
					width: 100%;
					height: 50px;
					padding: 15px;
					margin: 0;
					background: #6E6E86;
					font-size: 12px;
					float: left;
					display: inline;
					bottom:0;
				}

				div.footer a {
					color: #FFF;
					font-size: 12px;
					margin: 0 auto;
				}
			</style>
		@endif

	{{-- jQuery Version 1.11.0 --}}
	{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

	{{-- Bootstrap Core JavaScript --}}
	{{ HTML::script('assets/bootstrap-3.3.0/js/bootstrap.min.js') }}

</body>
</html>