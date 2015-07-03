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
                {{-- /.col-lg-12 --}}

                <div class="col-lg-12">
                    @include('layout.notification')
                </div>
            </div>
            {{-- /.row --}}


            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-general" data-toggle="tab">主要内容</a></li>
                <li><a href="#tab-meta-data" data-toggle="tab">SEO</a></li>
            </ul>

            {{ Form::open(array(
                    'files'     => true,
                    'url'       => route($resource.'.update', $data->id),
                    'method'    => 'put',
                    'style'     => 'background:#f8f8f8;padding:1em;border:1px solid #ddd;border-top:0;',
                    'class'     => 'form-horizontal'
                 ))
            }}
                {{-- Tabs Content --}}
                <div class="tab-content">

                    {{-- General tab --}}
                    <div class="tab-pane active" id="tab-general" style="margin:0 1em;">

                        <div class="form-group">
                            <label for="category">分类</label>
                            {{ $errors->first('category', '<span style="color:#c7254e;margin:0 1em;">:message</span>') }}
                            {{ Form::select('category', $categoryLists, $data->category->id, array('class' => 'form-control')) }}
                        </div>

                        <div class="form-group">
                            <label for="title">标题</label>
                            {{ $errors->first('title', '<span style="color:#c7254e;margin:0 1em;">:message</span>') }}
                            <input class="form-control" type="text" name="title" id="title" value="{{ Input::old('title', $data->title) }}" />
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            {{ $errors->first('slug', '<span style="color:#c7254e;margin:0 1em;">:message</span>') }}
                            <div class="input-group">
                                <span class="input-group-addon" >
                                    {{ str_finish(URL::to('/article'), '/') }}
                                </span>
                                <input class="form-control" type="text" name="slug" id="slug" value="{{ Input::old('slug', $data->slug) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="slug">创建时间</label>
                            {{ $errors->first('created_at', '<span style="color:#c7254e;margin:0 1em;">:message</span>') }}
                            <input class="form-control" type="text" name="created_at" id="created_at" value="{{ Input::old('created_at', $data->created_at) }}">
                        </div>

                        <div class="form-group">
                            <label for="content">设定移动端 App 轮播图（可选）</label>
                            @if($data->thumbnails)
                                @if(File::exists('upload/thumbnails/' . $data->thumbnails) && File::size('upload/thumbnails/' . $data->thumbnails) > 0)
                                <p>
                                    {{ HTML::image('upload/thumbnails/' . $data->thumbnails, '', array('width' => '640')) }}
                                </p>
                                @else
                                <p>
                                    {{ HTML::image('assets/images/thumbnails-default-640x280@2x.png', '', array('width' => '640')) }}
                                </p>
                                @endif
                            @else
                            @endif
                            {{ Form::file('thumbnails') }}
                        </div>

                        <div class="form-group">
                            <label for="content">内容</label>
                            {{ $errors->first('content', '<span style="color:#c7254e;margin:0 1em;">:message</span>') }}

                            {{ Umeditor::css() }}
                            {{ Umeditor::content(Input::old('content'), ['id'=>'create_comment_editor', 'class'=>'g-r-value', 'name' => 'content', 'height' => '220']) }}
                            {{ Umeditor::js() }}

                        </div>

                    </div>

                    <!-- Meta Data tab -->
                    <div class="tab-pane" id="tab-meta-data" style="margin:0 1em;">

                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input class="form-control" type="text" name="meta_title" id="meta_title" value="{{ Input::old('meta_title', $data->meta_title) }}" />
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <input class="form-control" type="text" name="meta_description" id="meta_description" value="{{ Input::old('meta_description', $data->meta_description) }}" />
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input class="form-control" type="text" name="meta_keywords" id="meta_keywords" value="{{ Input::old('meta_keywords', $data->meta_keywords) }}" />
                        </div>

                    </div>

                    <!-- Info tab -->
                    <div class="tab-pane" id="tab-info" style="margin:0 1em 2em 1em;">

                        <div class="form-group">
                            <label>作者</label>
                            <p class="form-control-static">{{ $data->user ? $data->user->email : '作者信息丢失' }}</p>
                        </div>

                        <div class="form-group">
                            <label>创建时间</label>
                            <p class="form-control-static">{{ $data->created_at }}（{{ $data->friendly_created_at }}）</p>
                        </div>

                        <div class="form-group">
                            <label>最后修改时间</label>
                            <p class="form-control-static">{{ $data->updated_at }}（{{ $data->friendly_updated_at }}）</p>
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

    // Instantiate editor
    var um = UM.getEditor('create_comment_editor',{onready:function(){this.setContent('{{ $data->content }}');}});
    $('div.edui-container').removeAttr("style");
    $('div#create_comment_editor').removeAttr("style").height(200);
    </script>
</body>

</html>