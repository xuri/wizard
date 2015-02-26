@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{ $resourceName }}管理</h1>
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
							{{ $resourceName }}列表
						</div>
						<div class="panel-body">
							{{ Form::open(array('method' => 'get', 'action' => array('Admin_UniversityResource@index'))) }}
								<div class="input-group col-md-12" style="margin:0 0 1em 0">
									<span class="input-group-btn" style="width: 20%; padding: 0 10px 0 0;">
										<select class="form-control input-sm" name="province">
											<option value="">所有省份</option>
											@foreach($provinces as $province)
											<option value="{{ $province->id }}">{{ $province->province }}</option>
											@endforeach
										</select>
									</span>
									<span class="input-group-btn" style="width: 10%; padding: 0 10px 0 0;">
										{{
											Form::select(
												'status',
												array(
													'' => '全部学校',
													'0' => '暂未开放',
													'1' => '即将开放',
													'2' => '已经开放'
												),
												Input::get('status'),
												array('class' => 'form-control input-sm')
											)
										}}
									</span>
									<input type="text" class="form-control input-sm" name="like" placeholder="请输入搜索条件" value="{{ Input::get('like') }}">
									<span class="input-group-btn">
										<button class="btn btn-sm btn-default" type="submit" style="width:5em;">筛选</button>
									</span>
								</div>
							{{ Form::close() }}

							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="{{-- dataTables-example --}}">
									<thead>
										<tr>
											<th>ID <a href="{{ route('admin.university.index') }}?sort_up=id" class="glyphicon glyphicon-random"></a></th>
											<th style="text-align:center;">高校 {{ order_by('university', 'desc') }}</th>
											<th>注册人数<a href="{{ route('admin.university.index') }}" class="glyphicon glyphicon-chevron-down"></a></th>
											<th>创建时间（即将开放） <a href="{{ route('admin.university.index') }}?sort_up=created_at" class="glyphicon glyphicon-random"></a></th>
											<th style="width:10.5em;text-align:center;">操作 <a href="{{ route('admin.university.index') }}?sort_up=status" class="glyphicon glyphicon-random"></a></th>
										</tr>
									</thead>
									<tbody>
									<?php
										$arr = $universities_list;

										$rows_per_page = 10;

										// get total number of rows

										$numrows = count($arr);

										// Calculate number of $lastpage
										$lastpage = ceil($numrows/$rows_per_page);

										// condition inputs/set default
										if (isset($_GET['pageno'])) {
										   $pageno = $_GET['pageno'];
										} else {
										   $pageno = 1;
										}

										// validate/limit requested $pageno
										$pageno = (int)$pageno;
										if ($pageno > $lastpage) {
										   $pageno = $lastpage;
										}
										if ($pageno < 1) {
										   $pageno = 1;
										}

										// Find start and end array index that corresponds to the reuqeted pageno
										$start = ($pageno - 1) * $rows_per_page;
										$end = $start + $rows_per_page -1;

										// limit $end to highest array index
										if($end > $numrows - 1){
											$end = $numrows - 1;
										}

										// display array from $start to $end
										for($i = $start;$i <= $end;$i++){
											$data = University::where('id', $arr[$i]['id'])->first();

									?>

										<tr class="odd gradeX">
											<td>{{ $arr[$i]['id'] }}</td>
											<td style="text-align:center;">{{ $arr[$i]['name'] }}</td>
											<td class="center">{{ $arr[$i]['all_users'] }}</td>
											<td class="center">{{ $data->created_at }}</td>
											<td class="center">
												@if($data->status == 2)
												<a href="{{ route($resource.'.close', $data->id) }}" class="btn btn-xs btn-success">已开放</a>
												@elseif($data->status == 1)
												<a href="{{ route($resource.'.open', $data->id) }}" class="btn btn-xs btn-primary">等待中</a>
												@elseif($data->status == 0 || $data->status = 0)
												<a href="{{ route($resource.'.open', $data->id) }}" class="btn btn-xs btn-warning">未开放</a>
												@endif
												<a href="{{ route($resource.'.edit', $data->id) }}" class="btn btn-xs btn-info">编辑</a>
												<a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="modal('{{ route($resource.'.destroy', $data->id) }}')">删除</a>
											</td>
										</tr>
									<?php
										}
									?>
									</tbody>
								</table>

								<ul class="pagination pagination-sm">

									<?php
										// first/prev pagination hyperlinks
										if ($pageno == 1) {
										   echo ' <li class="disabled"><span>«</span></li> ';
										} else {
										   echo " <li><a href='?pageno=1'>最前</a></li> ";
										   $prevpage = $pageno-1;
										   echo " <li><a href='?pageno=$prevpage'>前一页</a></li> ";
										}

										// Display current page or pages
										echo '<li class="disabled"><span>( 第' . $pageno . '页，共' . $lastpage . '页 )</span></li>';

										// next/last pagination hyperlinks
										if ($pageno == $lastpage) {
										   echo '<li class="disabled"><span>»</span></li>';
										} else {
										   $nextpage = $pageno+1;
										   echo " <li><a href='?pageno=$nextpage'>下一页</a></li> ";
										   echo " <li><a href='?pageno=$lastpage'>最后</a></li> ";
										}
									?>

								</ul>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel -->
				</div>
				<!-- /.col-lg-12 -->
			</div>
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

	<?php
	$modalData['modal'] = array(
		'id'      => 'myModal',
		'title'   => '系统提示',
		'message' => '确认删除此'.$resourceName.'？',
		'footer'  =>
			Form::open(array('id' => 'real-delete', 'method' => 'delete')).'
				<button type="button" class="btn btn-sm btn-default btn-bordered" data-dismiss="modal">取消</button>
				<button type="submit" class="btn btn-sm btn-danger">确认删除</button>'.
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
