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
											<label>用户权限类型（谨慎操作）： </label>
											@if($data->is_admin == 1)
											<label class="radio-inline">
												<input type="radio" name="is_admin" id="optionsRadiosInline1" value="0">普通用户
											</label>
											<label class="radio-inline">
												<input type="radio" name="is_admin" id="optionsRadiosInline2" value="1" checked="checked">管理员
											</label>
											@else
											<label class="radio-inline">
												<input type="radio" name="is_admin" id="optionsRadiosInline1" value="0" checked="checked">普通用户
											</label>
											<label class="radio-inline">
												<input type="radio" name="is_admin" id="optionsRadiosInline2" value="1">管理员
											</label>
											@endif
										</div>
										<div class="form-group input-group">
											<label>头像预览</label>
											<p class="form-control-static">
											{{ HTML::image('portrait/'.$data->portrait, '', array('width' => '150')) }}
											</p>
										</div>

										<div class="form-group">
											<label>性别</label>
											<select class="form-control" id="sex" name="sex" onchange="setsex();" rel="{{ $data->sex }}">
												<option value="M">男</option>
												<option value="F">女</option>
											</select>
										</div>
										<div class="form-group">
											<label>出生年份</label>
											<select class="form-control" id="born_year" name="born_year" onchange="setborn_year();" rel="{{ $data->born_year }}">
												<option value="">请选择</option>
												<option value="1996">1996</option>
												<option value="1995">1995</option>
												<option value="1994">1994</option>
												<option value="1993">1993</option>
												<option value="1992">1992</option>
												<option value="1991">1991</option>
												<option value="1990">1990</option>
												<option value="1989">1989</option>
												<option value="1988">1988</option>
												<option value="1987">1987</option>
											</select>
										</div>
										<div class="form-group">
											<label>所在高校</label>
											<select class="form-control" id="school" name="school" onchange="setschool();" rel="{{ $data->school }}">
												<option value="">请选择</option>
												@foreach($universities as $university)
												<option value="{{ $university->university }}">{{ $university->university }}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label>入学年份</label>
											<select class="form-control" id="grade" name="grade" onchange="setgrade();" rel="{{ $profile->grade }}">
												<option value="">未设置入学年份</option>
												<option value="2015">2015</option>
												<option value="2014">2014</option>
												<option value="2013">2013</option>
												<option value="2012">2012</option>
												<option value="2011">2011</option>
												<option value="2010">2010</option>
												<option value="2009">2009</option>
												<option value="2008">2008</option>
											</select>
										</div>
										<div class="form-group">
											<label>星座</label>
											<select class="form-control" id="constellation" name="constellation" onchange="setconstellation();" rel="{{ $profile->constellation }}">
												<option value="">未设置星座</option>
												<option value="1">水瓶座</option>
												<option value="2">双鱼座</option>
												<option value="3">白羊座</option>
												<option value="4">金牛座</option>
												<option value="5">双子座</option>
												<option value="6">巨蟹座</option>
												<option value="7">狮子座</option>
												<option value="8">处女座</option>
												<option value="9">天秤座</option>
												<option value="10">天蝎座</option>
												<option value="11">射手座</option>
												<option value="12">摩羯座</option>
											</select>
										</div>
										<div class="form-group">
											<label>向此用户推送系统通知</label>
											<textarea class="form-control" rows="3" name="system_notification">{{ Input::old('notification') }}</textarea>
										</div>
										<a href="{{ route($resource.'.detail', $data->id) }}" class="btn btn-default">查看此用户的好友关系详情</a>
									</div>
									{{-- /.col-lg-6 (nested) --}}
									<div class="col-lg-6">
										<div class="form-group input-group">
											<span class="input-group-addon">注册时间</span>
											<input type="text" class="form-control" placeholder="暂无数据" value="{{ Input::old('created_at', $data->created_at) }}" name="created_at">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">最后登录</span>
											<input type="text" class="form-control" placeholder="暂无数据" value="{{ Input::old('signin_at', $data->signin_at) }}" name="signin_at">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">用户昵称</span>
											<input type="text" class="form-control" placeholder="未设置" value="{{ Input::old('nickname', $data->nickname) }}" name="nickname">
										</div>
										{{ $errors->first('phone', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
										<div class="form-group input-group">
											<span class="input-group-addon">手机号码</span>
											<input type="text" class="form-control" placeholder="未设置" value="{{ Input::old('phone', $data->phone) }}" name="phone">
										</div>
										<div class="form-group input-group">
											<span class="input-group-addon">头像文件</span>
											<input type="text" class="form-control" placeholder="未设置" value="{{ Input::old('portrait', $data->portrait) }}" name="portrait">
										</div>
										{{ $errors->first('points', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
										<div class="form-group input-group">
											<span class="input-group-addon">累计积分</span>
											<input type="text" class="form-control" placeholder="无积分" value="{{ Input::old('points', $data->points) }}" name="points">
										</div>
										{{ $errors->first('renew', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
										<div class="form-group input-group">
											<span class="input-group-addon">签到次数</span>
											<input type="text" class="form-control" placeholder="无签到" value="{{ Input::old('renew', $profile->renew) }}" name="renew">
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
									{{-- /.col-lg-6 (nested) --}}
									<button type="submit" class="btn btn-default">保 存</button>
									<button type="reset" class="btn btn-default">重 置</button>
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
	<script>
		$("#sex").val($("#sex").attr("rel"));
		$("#born_year").val($("#born_year").attr("rel"));
		$("#school").val($("#school").attr("rel"));
		$("#grade").val($("#grade").attr("rel"));
		$("#constellation").val($("#constellation").attr("rel"));
	</script>
</body>

</html>