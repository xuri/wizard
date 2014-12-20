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
							用户详细信息编辑
						</div>
						<div class="panel-body">
							<div class="row">
								{{ Form::open(array(
									'autocomplete' => 'off',
									'action'       => 'Admin_UserResource@update',
									))
								}}
									<div class="col-lg-6">
										<div class="form-group">
											<label>用户ID：{{ $data->id }}， 注册来源：
												@if($data->from == 1)
												<i class="fa fa-android"></i> Android 客户端
												@elseif($data->from == 2)
												<i class="fa fa-apple"></i> iOS 客户端
												@else
												<i class="fa fa-laptop"></i> Web 网站
												@endif
											</label>
										</div>
										<div class="form-group">
											@if($data->email)
											<label>邮箱地址：{{ $data->email }}</label>
											@else
											<label>邮箱地址：未设置</label>
											@endif
										</div>
										<div class="form-group">
											<label>用户权限类型： </label>
											<label class="radio-inline">
												<input type="radio" name="is_admin" id="optionsRadiosInline1" value="0" checked>普通用户
											</label>
											<label class="radio-inline">
												<input type="radio" name="is_admin" id="optionsRadiosInline2" value="1">管理员
											</label>
										</div>
										<div class="form-group input-group">
											<label>头像预览</label>
											<p class="form-control-static">
											{{ HTML::image('portrait/'.$data->portrait, '', array('width' => '150')) }}
											</p>
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">注册时间</span>
											<input type="text" class="form-control" placeholder="暂无数据" value="{{ Input::old('created_at', $data->created_at) }}" name="created_at">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">最后登录</span>
											<input type="text" class="form-control" placeholder="暂无数据" value="{{ Input::old('signin_at', $profile->signin_at) }}" name="signin_at">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">用户昵称</span>
											<input type="text" class="form-control" placeholder="未设置" value="{{ Input::old('nickname', $data->nickname) }}" name="nickname">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">手机号码</span>
											<input type="text" class="form-control" placeholder="未设置" value="{{ Input::old('phone', $data->phone) }}" name="phone">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">出生年份</span>
											<input type="text" class="form-control" placeholder="未设置" value="{{ Input::old('born_year', $data->born_year) }}" name="born_year">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">所在高校</span>
											<input type="text" class="form-control" placeholder="未设置" value="{{ Input::old('school', $data->school) }}" name="school">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">头像文件</span>
											<input type="text" class="form-control" placeholder="未设置" value="{{ Input::old('portrait', $data->portrait) }}" name="portrait">
										</div>

										<div class="form-group">
											<label>性别</label>
											<select class="form-control" name="sex">
												<option>男</option>
												<option value="F">女</option>
											</select>
										</div>
										<a href="{{ route($resource.'.detail', $data->id) }}" class="btn btn-default">查看此用户的好友关系详情</a>
									</div>
									<!-- /.col-lg-6 (nested) -->
									<div class="col-lg-6">
										<div class="form-group input-group">
											<span class="input-group-addon">累计积分</span>
											<input type="text" class="form-control" placeholder="无积分" value="{{ Input::old('points', $data->points) }}" name="points">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">签到次数</span>
											<input type="text" class="form-control" placeholder="无签到" value="{{ Input::old('renew', $profile->renew) }}" name="renew">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">入学年份</span>
											<input type="text" class="form-control" placeholder="无积分" value="{{ Input::old('grade', $profile->grade) }}" name="grade">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">星座代码</span>
											<input type="text" class="form-control" placeholder="未设置星座" value="{{ Input::old('constellation', $profile->constellation) }}" name="constellation">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">标签代码</span>
											<input type="text" class="form-control" placeholder="未设置个性标签" value="{{ Input::old('tag_str', $profile->tag_str) }}" name="tag_str">
										</div>
										<div class="form-group">
											<label>爱情宣言</label>
											<textarea class="form-control" rows="3" name="bio">{{ Input::old('bio', $data->bio) }}</textarea>
										</div>
										<div class="form-group">
											<label>个人爱好</label>
											<textarea class="form-control" rows="3" name="hobbies">{{ Input::old('hobbies', $profile->hobbies) }}</textarea>
										</div>
										<div class="form-group">
											<label>自我介绍</label>
											<textarea class="form-control" rows="3" name="self_intro">{{ Input::old('self_intro', $profile->self_intro) }}</textarea>
										</div>
										<div class="form-group">
											<label>爱情考验问题</label>
											<textarea class="form-control" rows="3" name="question">{{ Input::old('question', $profile->question) }}</textarea>
										</div>
										<div class="form-group">
											<label>向此用户推送系统通知</label>
											<textarea class="form-control" rows="3">{{ Input::old('notification') }}</textarea>
										</div>
									<!-- /.col-lg-6 (nested) -->
									<button type="submit" class="btn btn-default">保 存</button>
									<button type="reset" class="btn btn-default">重 置</button>
								</form>
							</div>
							<!-- /.row (nested) -->
						</div>
						<!-- /.panel-body -->
					</div>
					<!-- /.panel -->
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

	{{-- Custom Theme JavaScript --}}
	{{ HTML::script('assets/js/admin/admin.js') }}

</body>

</html>
