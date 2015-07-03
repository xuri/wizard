@include('admin.header')
@yield('content')

    <div id="wrapper">

        @include('admin.navigation')
        @yield('content')

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">{{ Lang::get('navigation.admin_user_management') }}</h1>
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
                            批量添加用户
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                {{ Form::open(array(
                                    'autocomplete' => 'off',
                                    ))
                                }}
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>创建男用户</label>
                                        </div>
                                        {{ $errors->first('m_from', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="form-group">
                                            <label>注册来源: </label>&nbsp;
                                            <label class="radio-inline">
                                                <input type="radio" name="m_from" id="optionsRadiosInline1" value="1"> <i class="fa fa-android"></i> Android 客户端
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="m_from" id="optionsRadiosInline1" value="2"> <i class="fa fa-apple"></i> iOS 客户端
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="m_from" id="optionsRadiosInline1" value="0"> <i class="fa fa-laptop"></i> Web 网站
                                            </label>
                                        </div>

                                        {{ $errors->first('m_password', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">登陆密码</span>
                                            <input type="text" class="form-control" placeholder="留空则使用默认密码 'password'" value="{{ Input::old('m_password') }}" name="m_password">
                                        </div>
                                        {{ $errors->first('m_phone', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="input-group col-md-12" style="margin:0 0 1em 0">
                                            <span class="input-group-addon">手机号段</span>
                                            <input type="text" class="form-control" placeholder="前三位，留空则使用187" value="{{ Input::old('m_phone') }}" name="m_phone">
                                        </div>
                                        {{ $errors->first('m_create', '<strong class="error" style="color: #cc0000;">:message</strong>') }}

                                        <div class="input-group col-md-12" style="margin:0 0 1em 0">
                                            <span class="input-group-addon">添加数量</span>
                                            <input type="text" class="form-control" placeholder="一次添加男用户的数量(必填)" value="{{ Input::old('m_create') }}" name="m_create">
                                        </div>
                                    </div>
                                    {{-- /.col-lg-6 (nested) --}}
                                    <div class="col-lg-6">

                                        <div class="form-group">
                                            <label>创建女用户</label>
                                        </div>
                                        {{ $errors->first('f_from', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="form-group">
                                            <label>注册来源: </label>
                                            <label class="radio-inline">&nbsp;
                                                <input type="radio" name="f_from" id="optionsRadiosInline1" value="1"> <i class="fa fa-android"></i> Android 客户端
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="f_from" id="optionsRadiosInline1" value="2"> <i class="fa fa-apple"></i> iOS 客户端
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="f_from" id="optionsRadiosInline1" value="0"> <i class="fa fa-laptop"></i> Web 网站
                                            </label>
                                        </div>

                                        {{ $errors->first('f_password', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">登陆密码</span>
                                            <input type="text" class="form-control" placeholder="留空则使用默认密码 'password'" value="{{ Input::old('f_password') }}" name="f_password">
                                        </div>
                                        {{ $errors->first('f_phone', '<strong class="error" style="color: #cc0000;">:message</strong>') }}
                                        <div class="input-group col-md-12" style="margin:0 0 1em 0">
                                            <span class="input-group-addon">手机号段</span>
                                            <input type="text" class="form-control" placeholder="前三位，留空则使用187" value="{{ Input::old('f_phone') }}" name="f_phone">
                                        </div>

                                        {{ $errors->first('f_create', '<strong class="error" style="color: #cc0000;">:message</strong>') }}

                                        <div class="input-group col-md-12" style="margin:0 0 1em 0">
                                            <span class="input-group-addon">添加数量</span>
                                            <input type="text" class="form-control" placeholder="一次添加女用户的数量(必填)" value="{{ Input::old('f_create') }}" name="f_create">
                                        </div>


                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">创 建</button>
                                            <button type="reset" class="btn btn-default">重 置</button>
                                        </div>
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

</body>

</html>