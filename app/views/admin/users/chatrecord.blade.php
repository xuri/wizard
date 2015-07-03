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
                            以下是用户：{{ $data->nickname }}在{{ $date }}的聊天记录内容， <a href="{{ route('users.edit', $data->id) }}">点此返回用户编辑。</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>时间</th>
                                        <th>对方ID</th>
                                        <th>对方昵称</th>
                                        <th>消息内容</th>
                                        <th>消息类型</th>
                                    </tr>
                                </thead>
                                    @foreach($chatrecord as $messages)
                                    <?php
                                        // Retrieve user
                                        $friend     = User::find($messages->to);

                                        // Determine messages type
                                        $msg_type   = $messages->payload->bodies[0]->type;
                                    ?>
                                    <tr>
                                        <td>{{ date('Y-m-d H:i:s', substr($messages->timestamp, 0, -3)) }}</td>
                                        @if($friend)
                                        <td><a href="{{ route('users.edit', $messages->to) }}" target="_blank">{{ $messages->to }}</a></td>
                                        @else
                                        <td>{{ $messages->to }}</td>
                                        @endif

                                        @if($friend)
                                        <td><a href="{{ route('users.edit', $messages->to) }}" target="_blank">{{ $friend->nickname }}</a></td>
                                        @else
                                        <td>此用户现不存在</td>
                                        @endif

                                        @if($msg_type == 'txt')
                                        <td>{{ $messages->payload->bodies[0]->msg }}</td>
                                        @elseif($msg_type == 'img')
                                        <td>图片：<a href="{{ $messages->payload->bodies[0]->thumb }}" alt="{{ $messages->payload->bodies[0]->filename }}" title="{{ $messages->payload->bodies[0]->filename }}"><img src="{{ $messages->payload->bodies[0]->thumb }}" width="20" height="20"></a></td>
                                        @elseif($msg_type == 'cmd')
                                        <td>{{ $messages->payload->ext->content }}</td>
                                        @else
                                        <td>其他消息</td>
                                        @endif

                                        @if($msg_type == 'txt')
                                        <td>文本消息</td>
                                        @elseif($msg_type == 'img')
                                        <td>图片消息</td>
                                        @elseif($msg_type == 'cmd')
                                        <td>透传消息</td>
                                        @else
                                        <td>{{ $msg_type }}</td>
                                        @endif

                                    </tr>
                                    @endforeach
                                <tbody>
                                </tbody>
                            </table>
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