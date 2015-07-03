@include('admin.header')
@yield('content')

    <div id="wrapper">

        @include('admin.navigation')
        @yield('content')

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">{{ $resourceName }}内容</h1>
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
                            @if($user->nickname)
                                此{{ $resourceName }}来自用户{{ $user->nickname }}
                            @elseif($user->phone)
                                此{{ $resourceName }}来自手机号为{{ $user->phone }}的用户
                            @elseif($user->email)
                                此{{ $resourceName }}来自E-mail号为{{ $user->email }}的用户
                            @endif
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <h3 id="grid-offsetting">
                                @if($data->title)
                                    $data->title
                                @else
                                    此{{ $resourceName }}未设置标题
                                @endif
                            </h3>
                            <p>{{ $data->content }}</p>
                        </div>

                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <div class="panel-heading">
                    @if($user->id!=Auth::user()->id)
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-default">查看此用户的详情</a>
                    <a href="{{ route('users.notify', $user->id) }}" class="btn btn-default">向此用户推送系统通知</a>
                    @else
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-default">此反馈由您发出，不能进行回复操作</a>
                    @endif
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

</body>

</html>