@include('admin.header')
@yield('content')

    <div id="wrapper">

        @include('admin.navigation')
        @yield('content')

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">{{ Lang::get('navigation.admin_user_all') }}
                        <div class="pull-right">
                            <a href="{{ route($resource.'.create') }}" class="btn btn-sm btn-primary">
                                {{ Lang::get('admin/users/index.new') }}
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
                            {{ Lang::get('admin/users/index.users') }} {{ Lang::get('admin/users/index.table') }}
                        </div>
                        {{-- /.panel-heading --}}
                        <div class="panel-body">
                            {{ Form::open(array('method' => 'get')) }}
                                <div class="input-group col-md-12" style="margin:0 0 1em 0">
                                    <span class="input-group-btn" style="width: 12%; padding: 0 10px 0 0;">
                                        <select class="form-control input-sm" name="province">
                                            <option value="">{{ Lang::get('admin/users/index.all_province') }}</option>
                                            @foreach($provinces as $province)
                                            <option value="{{ $province->id }}">{{ $province->province }}</option>
                                            @endforeach
                                        </select>
                                    </span>

                                    <span class="input-group-btn" style="width: 10%; padding: 0 10px 0 0;">
                                        {{
                                            Form::select(
                                                'sex',
                                                array(
                                                    ''  => Lang::get('admin/users/index.sex'),
                                                    'M' => Lang::get('admin/users/index.male'),
                                                    'F' => Lang::get('admin/users/index.female')
                                                ),
                                                Input::get('sex'),
                                                array('class' => 'form-control input-sm')
                                            )
                                        }}
                                    </span>

                                    <span class="input-group-btn" style="width: 12%; padding: 0 10px 0 0;">
                                        {{
                                            Form::select(
                                                'is_verify',
                                                array(
                                                    ''  => Lang::get('admin/users/index.all_users'),
                                                    '1' => Lang::get('admin/users/index.verified_user'),
                                                    '0' => Lang::get('admin/users/index.common_user')
                                                ),
                                                Input::get('is_verify'),
                                                array('class' => 'form-control input-sm')
                                            )
                                        }}
                                    </span>

                                    <input type="text" class="form-control input-sm" name="like" placeholder="{{ Lang::get('admin/users/index.select_input') }}" value="{{ Input::get('like') }}">
                                    <span class="input-group-btn">
                                            <button class="btn btn-sm btn-default" type="submit" style="width:5em;">{{ Lang::get('admin/users/index.select') }}</button>
                                    </span>
                                </div>
                            {{ Form::close() }}

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="{{-- dataTables-example --}}">
                                    <thead>
                                        <tr>
                                            <th>ID {{ order_by('id', 'desc') }}</th>
                                            <th>{{ Lang::get('admin/users/index.identity') }} {{ order_by('is_admin') }}</th>
                                            <th style="text-align:center;">{{ Lang::get('admin/users/index.portrait') }}</th>
                                            <th>{{ Lang::get('admin/users/index.account') }}</th>
                                            <th>{{ Lang::get('admin/users/index.nickname') }} {{ order_by('nickname') }}</th>
                                            <th>{{ Lang::get('admin/users/index.created_at') }} {{ order_by('created_at') }}</th>
                                            <th>{{ Lang::get('admin/users/index.signin_at') }} {{ order_by('updated_at') }}</th>
                                            <th style="width:6em;text-align:center;">{{ Lang::get('admin/users/index.status') }} {{ order_by('block') }}</th>
                                            <th style="width:7.5em;text-align:center;">{{ Lang::get('admin/users/index.operating') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php $currentId = Auth::user()->id; ?>

                                        @foreach ($datas as $data)
                                        <tr class="odd gradeX">
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->is_admin ? Lang::get('system.moderator') : Lang::get('admin/users/index.common_user') }}</td>
                                            <td style="text-align:center;">
                                                <a href="{{ route('members.show', $data->id) }}" target="_blank">
                                                    @if($data->portrait)
                                                        @if(File::exists('portrait/' . $data->portrait) && File::size('portrait/' . $data->portrait) > 0)
                                                            {{ HTML::image('portrait/'.$data->portrait, '', array('width' => '20')) }}
                                                        @else
                                                            {{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('width' => '20')) }}
                                                        @endif
                                                    @else
                                                    {{ HTML::image('assets/images/preInfoEdit/peo.png', '', array('width' => '20')) }}
                                                    @endif
                                                </a>
                                            </td>
                                            @if($data->email)
                                            <td>邮箱：<a href="mailto:{{ $data->email }}">{{ $data->email }}<a></td>
                                            @elseif($data->phone)
                                            <td>手机：<a href="tel:{{ $data->phone }}" value="{{ $data->phone }}">{{ $data->phone }}</a></td>
                                            @else
                                            <td>WAP ID：<a href="{{ route('members.show', $data->id) }}" value="{{ $data->phone }}" target="_blank">{{ $data->w_id }}</a></td>
                                            @endif
                                            <td class="center">{{ $data->nickname }}</td>

                                            <td class="center">{{ $data->created_at }}</td>
                                            <td class="center">{{ $data->updated_at }}</td>

                                                @if($data->block)
                                                    @if($data->id!=$currentId)
                                                    <td class="center" style="text-align:center;">
                                                        <a href="{{ route('users.unlock', $data->id) }}" class="btn btn-xs btn-success">{{ Lang::get('admin/users/index.unlock') }}</a>
                                                    </td>
                                                    <td class="center" style="text-align:center;">
                                                        <a href="{{ route($resource.'.edit', $data->id) }}" class="btn btn-xs btn-info">{{ Lang::get('admin/users/index.edit') }}</a>
                                                        <a href="javascript:void(0);" class="btn btn-xs btn-danger" onclick="modal('{{ route($resource.'.destroy', $data->id) }}')">{{ Lang::get('admin/users/index.delete') }}</a>
                                                    </td>
                                                    @else
                                                    <td class="center" style="text-align:center;">
                                                        <a href="javascript:void(0);" class="btn btn-xs btn-success" disabled="true">{{ Lang::get('admin/users/index.unlock') }}</a>
                                                    </td>
                                                    <td class="center" style="text-align:center;">
                                                        <a href="javascript:void(0);" class="btn btn-xs btn-info" disabled="true">{{ Lang::get('admin/users/index.edit') }}</a>
                                                        <a href="javascript:void(0);" class="btn btn-xs btn-danger" disabled="true">{{ Lang::get('admin/users/index.delete') }}</a>
                                                    </td>
                                                    @endif
                                                @else
                                                    @if($data->id!=$currentId)
                                                    <td class="center" style="text-align:center;">
                                                        <a href="{{ route('users.block', $data->id) }}" class="btn btn-xs btn-warning">{{ Lang::get('admin/users/index.lock') }}</a>
                                                    </td>
                                                    <td class="center" style="text-align:center;">
                                                        <a href="{{ route($resource.'.edit', $data->id) }}" class="btn btn-xs btn-info">{{ Lang::get('admin/users/index.edit') }}</a>
                                                        <a href="javascript:void(0);" class="btn btn-xs btn-danger" onclick="modal('{{ route($resource.'.destroy', $data->id) }}')">{{ Lang::get('admin/users/index.delete') }}</a>
                                                    </td>
                                                    @else
                                                    <td class="center" style="text-align:center;">
                                                        <a href="javascript:void(0);" class="btn btn-xs btn-warning" disabled="true">{{ Lang::get('admin/users/index.lock') }}</a>
                                                    </td>
                                                    <td class="center" style="text-align:center;">
                                                        <a href="{{ route($resource.'.edit', $data->id) }}" class="btn btn-xs btn-info">{{ Lang::get('admin/users/index.edit') }}</a>
                                                        <a href="javascript:void(0);" class="btn btn-xs btn-danger" disabled="true">{{ Lang::get('admin/users/index.delete') }}</a>
                                                    </td>
                                                    @endif
                                                @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{ pagination($datas->appends(Input::except('page')), 'admin.paginator') }}

                            </div>
                            {{-- /.table-responsive --}}

                        </div>
                        {{-- /.panel-body --}}
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
            'message' => Lang::get('system.delete_confirm') . Lang::get('admin/users/index.user') . '?',
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