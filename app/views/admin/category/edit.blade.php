@include('admin.header')
@yield('content')

    <div id="wrapper">

        @include('admin.navigation')
        @yield('content')

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3>
                        编辑{{ $resourceName }}
                        <div class="pull-right">
                            <a href="{{ route($resource.'.index') }}" class="btn btn-sm btn-default">
                                &laquo; 返回{{ $resourceName }}列表
                            </a>
                        </div>
                    </h3>
                </div>
                <!-- /.col-lg-12 -->

                <div class="col-lg-12">
                    @include('layout.notification')
                </div>
            </div>
            <!-- /.row -->



            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-general" data-toggle="tab">主要内容</a></li>
            </ul>

            <form class="form-horizontal" method="post" action="{{ route($resource.'.update', $data->id) }}" autocomplete="off" style="background:#f8f8f8;padding:1em;border:1px solid #ddd;border-top:0;">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="_method" value="PUT" />

                <!-- Tabs Content -->
                <div class="tab-content">

                    <!-- General tab -->
                    <div class="tab-pane active" id="tab-general" style="margin:0 1em;">

                        <div class="form-group">
                            <label for="name">名称</label>
                            {{ $errors->first('name', '<span style="color:#c7254e;margin:0 1em;">:message</span>') }}
                            <input class="form-control" type="text" name="name" id="name" value="{{ Input::old('name', $data->name) }}" />
                        </div>

                        <div class="form-group">
                            <label for="sort_order">排序</label>
                            {{ $errors->first('sort_order', '<span style="color:#c7254e;margin:0 1em;">:message</span>') }}
                            <input class="form-control" type="text" name="sort_order" id="sort_order" value="{{ Input::old('sort_order', $data->sort_order) }}" />
                        </div>

                    </div>

                </div>

                <!-- Form actions -->
                <div class="control-group">
                    <div class="controls">
                        <a class="btn btn-default" href="{{ route($resource.'.edit', $data->id) }}">重 置</a>
                        <button type="submit" class="btn btn-success">提 交</button>
                    </div>
                </div>
            </form>
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
</body>

</html>