{{-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens --}}
<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        <li class="active">
            <a href="{{ route('admin') }}"><i class="fa fa-fw fa-dashboard"></i> 控制面板</a>
        </li>
        <li>
            <a href="#"><i class="fa fa-fw fa-table"></i> 用户管理</a>
        </li>

        <li>
            <a href="#"><i class="fa fa-fw fa-picture-o"></i> 头像审核</a>
        </li>
        <li>
            <a href="#"><i class="fa fa-fw fa-exclamation-triangle"></i> 举报管理</a>
        </li>
        <li>
            <a href="#"><i class="fa fa-fw fa-wrench"></i> 系统设置</a>
        </li>
        <li>
            <a href="#"><i class="fa fa-fw fa-bar-chart-o"></i> 网站状态</a>
        </li>
        <li>
            <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-desktop"></i> 前台选项 <i class="fa fa-fw fa-caret-down"></i></a>
            <ul id="demo" class="collapse">
               <li>
                    <a href="{{ route('home') }}"><i class="fa fa-fw fa-home"></i> 站点首页</a>
                </li>
                <li>
                    <a href="{{ route('members.index') }}"><i class="fa fa-fw fa-users"></i> 去招聘</a>
                </li>
                <li>
                    <a href="{{ route('home') }}"><i class="fa fa-fw fa-heart"></i> 晒幸福</a>
                </li>
                <li>
                    <a href="{{ route('home') }}"><i class="fa fa-fw fa-download"></i> App下载</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('signout') }}"><i class="fa fa-fw fa-sign-out"></i> 退出登陆</a>
        </li>
    </ul>
</div>
{{-- /.navbar-collapse --}}