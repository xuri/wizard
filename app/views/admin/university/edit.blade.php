@include('admin.header')
@yield('content')

	<div id="wrapper">

		@include('admin.navigation')
		@yield('content')

		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{ $resourceName }}开放时间设定</h1>
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
							设置在{{ $data->university }}的开放时间，格式为：2015-01-13 08:12:22， 在高校管理中设定开放功能后才可生效。<a href="{{ route('admin.university.index') }}">点此返回{{ $resourceName }}管理列表。</a>
						</div>
						<div class="panel-body">
							<div class="row">
								{{ Form::open(array(
									'autocomplete'	=> 'off'
									))
								}}
									<div class="col-lg-12">

										<div class="form-group">
											<label>学校ID：{{ $data->id }}</label>
										</div>

										<div class="form-group input-group">
											<span class="input-group-addon">设定开放时间</span>
											<input type="text" class="form-control" placeholder="暂无数据" value="{{ $data->open_at }}" name="open_at">
										</div>

										{{ $errors->first('university', '<strong class="error" style="color: #cc0000">:message</strong>') }}

										<div class="form-group input-group">
											<span class="input-group-addon">更改学校名称</span>
											<input type="text" class="form-control" placeholder="暂无数据" value="{{ $data->university }}" name="university">
										</div>

										{{ $errors->first('province', '<strong class="error" style="color: #cc0000">:message</strong>') }}

										<div class="form-group input-group">
											<span class="input-group-addon">更改学校省份</span>
											<select class="form-control input-sm" name="province_id" id="province_select" rel="{{ $data->province_id }}">
												<option value="">所有省份</option>
												@foreach($provinces as $province)
												<option value="{{ $province->id }}">{{ $province->province }}</option>
												@endforeach
											</select>
										</div>

										<button type="submit" class="btn btn-default">确 定</button>
										<button type="reset" class="btn btn-default">重 置</button>
									</div>
									{{-- /.col-lg-12 (nested) --}}

								{{ Form::close() }}
							</div>
							{{-- /.row (nested) --}}
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

	<script type="text/javascript">
		$("#province_select").val($("#province_select").attr("rel"));
	</script>
</body>

</html>