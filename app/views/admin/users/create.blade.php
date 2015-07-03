@include('admin.header')
@yield('content')

    <div id="wrapper">

        @include('admin.navigation')
        @yield('content')

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">{{ Lang::get('navigation.admin_user_all') }}</h1>
                </div>
                {{-- /.col-lg-12 --}}
            </div>
            {{-- /.row --}}
            <div class="row">
                <div class="col-lg-12">
                    @include('layout.notification')
                </div>

                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            {{ Lang::get('admin/users/index.new') }}
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                {{ Form::open(array(
                                    'autocomplete' => 'off',
                                    ))
                                }}
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>用户ID: 将由系统自动生成</label>
                                        </div>
                                        {{ $errors->first('from', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="form-group">
                                            <label>注册来源:</label>
                                            <label class="radio-inline">
                                                <input type="radio" name="from" id="optionsRadiosInline1" value="1"> <i class="fa fa-android"></i> Android 客户端
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="from" id="optionsRadiosInline1" value="2"> <i class="fa fa-apple"></i> iOS 客户端
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="from" id="optionsRadiosInline1" value="0"> <i class="fa fa-laptop"></i> Web 网站
                                            </label>
                                        </div>
                                        {{ $errors->first('is_admin', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="form-group">
                                            <label>用户权限类型（谨慎操作）: </label>

                                            <label class="radio-inline">
                                                <input type="radio" name="is_admin" id="optionsRadiosInline1" value="0" checked="checked">普通用户
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="is_admin" id="optionsRadiosInline2" value="1">{{ Lang::get('system.moderator') }}
                                            </label>

                                            <label class="radio-inline">
                                                <input type="checkbox" name="is_verify" id="optionsRadiosInline3" value="1">&nbsp;认证用户
                                            </label>
                                        </div>
                                        {{ $errors->first('sex', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="form-group">
                                            <label>性别: </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="sex" id="optionsRadiosInline1" value="M"> 男
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="sex" id="optionsRadiosInline1" value="F"> 女
                                            </label>
                                        </div>
                                        {{ $errors->first('password', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">登陆密码</span>
                                            <input type="text" class="form-control" placeholder="留空则使用默认密码 'password'" value="{{ Input::old('password') }}" name="password">
                                        </div>
                                        {{ $errors->first('phone', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="input-group col-md-12" style="margin:0 0 1em 0">
                                            <span class="input-group-addon">手机号码</span>
                                            <input type="text" class="form-control" placeholder="未设置" value="{{ Input::old('phone') }}" name="phone" id="phone">
                                            <span class="input-group-btn">
                                                <a class="btn btn-default" id="random_phone">{{ Lang::get('admin/users/create.generate') }}</a>
                                            </span>
                                        </div>
                                        <div class="input-group col-md-12" style="margin:0 0 1em 0">
                                            <span class="input-group-addon">注册时间</span>
                                            <input type="text" class="form-control" placeholder="留空则默认为当前时间" value="{{ Input::old('created_at') }}" name="created_at" id="created_at">
                                            <span class="input-group-btn">
                                                <a class="btn btn-default" id="random_created_at">{{ Lang::get('admin/users/create.generate') }}</a>
                                            </span>
                                        </div>

                                        <div class="form-group input-group">
                                            <label>上传头像</label>
                                            <p class="form-control-static">暂不可用</p>
                                        </div>

                                        <div class="form-group">
                                            <label>出生年份</label>
                                            <select class="form-control" id="born_year" name="born_year">
                                                <option value="">请选择</option>
                                                <option value="1996">1997</option>
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
                                            <select class="form-control selectpicker input-light brad" data-live-search="true" id="school" name="school">
                                                <option value="">请选择</option>
                                                @foreach($universities as $university)
                                                <option value="{{ $university->university }}">{{ $university->university }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>入学年份</label>
                                            <select class="form-control" id="grade" name="grade">
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
                                            <select class="form-control" id="constellation" name="constellation">
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
                                            <label>薪资范围</label>
                                            <select class="form-control" id="salary" name="salary">
                                                <option value="">未设置薪资范围</option>
                                                <option value="0">在校学生</option>
                                                <option value="1">0-2000</option>
                                                <option value="2">2000-5000</option>
                                                <option value="3">5000-9000</option>
                                                <option value="4">9000以上</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>所在省份</label>
                                            <select class="form-control" id="province" name="province">
                                                <option value="">请选择</option>
                                                @foreach($provinces as $province)
                                                <option value="{{ $province->id }}">{{ $province->province }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>首选语言</label>
                                            <select class="form-control" id="language" name="language">
                                                <option value="">未设置首选语言</option>
                                                <option value="zh">简体中文</option>
                                                <option value="en">English</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- /.col-lg-6 (nested) --}}
                                    <div class="col-lg-6">

                                        <div class="form-group input-group">
                                            <span class="input-group-addon">用户昵称</span>
                                            <input type="text" class="form-control" placeholder="未设置" value="{{ Input::old('nickname') }}" name="nickname">
                                        </div>

                                        <div class="form-group input-group">
                                            <span class="input-group-addon">最后登录</span>
                                            <input type="text" class="form-control" placeholder="暂无数据" value="{{ Input::old('signin_at') }}" name="updated_at">
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">头像文件</span>
                                            <input type="text" class="form-control" placeholder="未设置" value="{{ Input::old('portrait') }}" name="portrait">
                                        </div>
                                        {{ $errors->first('points', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">累计积分</span>
                                            <input type="text" class="form-control" placeholder="无积分" value="{{ Input::old('points') }}" name="points">
                                        </div>
                                        {{ $errors->first('renew', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">签到次数</span>
                                            <input type="text" class="form-control" placeholder="无签到" value="{{ Input::old('renew') }}" name="renew">
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">标签代码</span>
                                            <input type="text" class="form-control" placeholder="未设置个性标签" value="{{ Input::old('tag_str') }}" name="tag_str">
                                        </div>

                                        <div class="form-group">
                                            <label>爱情宣言</label>
                                            <textarea class="form-control" rows="3" name="bio">{{ Input::old('bio') }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>个人爱好</label>
                                            <textarea class="form-control" rows="3" name="hobbies">{{ Input::old('hobbies') }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>自我介绍</label>
                                            <textarea class="form-control" rows="3" name="self_intro">{{ Input::old('self_intro') }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>爱情考验问题</label>
                                            <textarea class="form-control" rows="3" name="question">{{ Input::old('question') }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline btn-success">创 建</button>
                                            <button type="reset" class="btn btn-default">重 置</button>
                                        </div>
                                    {{-- /.col-lg-6 (nested) --}}

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
        $('#random_phone').click(function(){
            var phone = Math.floor(Math.random() * 90000000) + 10000000;
            $('#phone').val('187' + phone);
        });

        $('#random_created_at').click(function(){
            d           = new Date();
            var year    = d.getFullYear();
            var month   = ( '0' + (Math.floor(Math.random() * ('0' + (d.getMonth()+1) ).slice( -2 )) + 1)).slice( -2 );
            var day     = ( '0' + (Math.floor(Math.random() * ('0' + d.getDate()).slice(-2)) + 1)).slice( -2 );
            var hours   = ( '0' + (Math.floor(Math.random() * ('0' + d.getHours()).slice(-2)) + 1)).slice( -2 );
            var minutes = ( '0' + (Math.floor(Math.random() * ('0' + d.getMinutes()).slice(-2)) + 1)).slice( -2 );
            var seconds = ( '0' + (Math.floor(Math.random() * ('0' + d.getSeconds()).slice(-2)) + 1)).slice( -2 );

            // $('#created_at').val( year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds);
            $('#created_at').val( year + '-' + '3' + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds);
        });
    </script>
</body>

</html>