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
							@if($data->nickname)
							向用户"{{ $data->nickname }}"推送系统消息， <a href="{{ route('users.edit', $data->id) }}">点此返回用户编辑。</a>
							@else
							向此用户推送系统消息， <a href="{{ route('users.edit', $data->id) }}">点此返回用户编辑。</a>
							@endif
						</div>
						<div class="panel-body">
							<div class="row">
								{{ Form::open(array(
									'autocomplete'	=> 'off',
									'action'		=> 'Admin_UserResource@postNotify'
									))
								}}
									<div class="col-lg-12">
										<div class="form-group">
											<label>爱情宣言</label>
											<textarea class="form-control" rows="3" name="system_notification">{{ Input::old('system_notification') }}</textarea>
										</div>
										<button type="submit" class="btn btn-default">推 送</button>
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
</body>

</html>