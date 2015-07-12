@include('admin.header')
@yield('content')

    <div id="wrapper">

        @include('admin.navigation')
        @yield('content')

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">{{ Lang::get('navigation.admin_news_post') }}
                        <div class="pull-right">
                            <a href="{{ route($resource.'.create') }}" class="btn btn-sm btn-primary">
                                {{ Lang::get('admin/article/index.new') }}
                            </a>
                        </div>
                    </h1>
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
                            {{ Lang::get('admin/article/index.article_table') }}
                        </div>
                        {{-- /.panel-heading --}}
                        <div class="panel-body">
                            {{ Form::open(array('method' => 'get')) }}
                                <div class="input-group col-md-12" style="margin:0 0 1em 0">
                                    <span class="input-group-btn" style="width: 10%; padding: 0 10px 0 0;">
                                        {{
                                            Form::select(
                                                'target',
                                                array('title' => Lang::get('admin/article/index.title')),
                                                Input::get('target', 'title'),
                                                array('class' => 'form-control input-sm')
                                            )
                                        }}
                                    </span>
                                    <input type="text" class="form-control input-sm" name="like" placeholder="{{ Lang::get('admin/article/index.select_input') }}" value="{{ Input::get('like') }}">
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="submit" style="width:5em;">{{ Lang::get('admin/article/index.select') }}</button>
                                    </span>
                                </div>
                            {{ Form::close() }}

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID {{ order_by('id', 'desc') }}</th>
                                            <th>{{ Lang::get('admin/article/index.title') }} {{ order_by('title') }}</th>
                                            <th>创建时间 {{ order_by('created_at') }}</th>
                                            <th style="width:6em;text-align:center;">{{ Lang::get('admin/article/index.status') }} {{ order_by('status') }}</th>
                                            <th style="width:7.5em;text-align:center;">{{ Lang::get('admin/article/index.operating') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datas as $data)
                                        <tr>
                                            <td>{{ $data->id }}</td>
                                            <td>
                                                <a href="{{ route('show', $data->slug) }}" target="_blank">
                                                    <i class="glyphicon glyphicon-share" style="font-size: 0.8em;"></i>
                                                </a>
                                                {{ $data->title }}
                                            </td>
                                            <td>{{ $data->created_at }}</td>
                                            <td style="text-align:center;">
                                                @if($data->status == 1)
                                                <a href="{{ route($resource.'.close', $data->id) }}" class="btn btn-xs btn-success">{{ Lang::get('admin/article/index.publish') }}</a>
                                                @else
                                                <a href="{{ route($resource.'.open', $data->id) }}" class="btn btn-xs btn-warning">{{ Lang::get('admin/article/index.draft') }}</a>
                                                @endif
                                            </td>
                                            <td style="text-align:center;">
                                                <a href="{{ route($resource.'.edit', $data->id) }}" class="btn btn-xs btn-info">{{ Lang::get('admin/article/index.edit') }}</a>
                                                <a href="javascript:void(0)" class="btn btn-xs btn-danger"
                                                     onclick="modal('{{ route($resource.'.destroy', $data->id) }}')">{{ Lang::get('admin/article/index.delete') }}</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{ pagination($datas->appends(Input::except('page')), 'admin.paginator') }}

                            </div>
                        </div>

                    </div>
                    {{-- /.panel --}}
                </div>
                {{-- /.col-lg-12 --}}
            </div>
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
            'title'   => Lang::get('system.system_prompt'),
            'message' => Lang::get('system.delete_confirm') . Lang::get('admin/article/index.resourceName') . '?',
            'footer'  =>
                Form::open(array('id' => 'real-delete', 'method' => 'delete')).'
                    <button type="button" class="btn btn-sm btn-default btn-bordered" data-dismiss="modal">' . Lang::get('system.cancel') . '</button>
                    <button type="submit" class="btn btn-sm btn-danger">' . Lang::get('system.delete_confirm') . '</button>'.
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
