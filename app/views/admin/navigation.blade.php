<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">导航菜单</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="{{ route('admin') }}">聘爱 · 管理中心</a>
	</div>
	<!-- /.navbar-header -->

	<ul class="nav navbar-top-links navbar-right">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
			</a>
			<ul class="dropdown-menu dropdown-user">
				<li><a href="{{ route('account') }}"><i class="fa fa-user fa-fw"></i> 个人信息</a>
				</li>
				<li><a href="{{ route('admin') }}"><i class="fa fa-gear fa-fw"></i> 控制面板</a>
				</li>
				<li class="divider"></li>
				<li><a href="{{ route('signout') }}"><i class="fa fa-sign-out fa-fw"></i> 退出登陆</a>
				</li>
			</ul>
			<!-- /.dropdown-user -->
		</li>
		<!-- /.dropdown -->
	</ul>
	<!-- /.navbar-top-links -->

	<div class="navbar-default sidebar" role="navigation">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav" id="side-menu">
				<li class="sidebar-search">
					<div class="input-group custom-search-form">
						<input type="text" class="form-control" placeholder="搜索...">
						<span class="input-group-btn">
						<button class="btn btn-default" type="button">
							<i class="fa fa-search"></i>
						</button>
					</span>
					</div>
					<!-- /input-group -->
				</li>
				<li>
					<a class="active" href="{{ route('admin') }}"><i class="fa fa-dashboard fa-fw"></i> 控制面板</a>
				</li>
				<li>
					<a href="{{ route('users.index') }}"><i class="fa fa-users fa-fw"></i> 用户管理</a>

				</li>
				<li>
					<a href="javascript:void();"><i class="fa fa-tags"></i> 论坛管理<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="{{ route('admin.forum.index') }}">帖子管理</a>
						</li>
					</ul>
					{{-- /.nav-second-level --}}
				</li>
				<li>
					<a href="{{ route('admin.university.index') }}"><i class="fa fa-bank fa-fw"></i> 高校管理</a>

				</li>
				<li>
					<a href="javascript:void();"><i class="fa  fa-table fa-fw"></i> 统计报表<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="{{ route('analytics.userform') }}">用户详细报表</a>
						</li>
						<li>
							<a href="{{ route('analytics.forumform') }}">论坛活动报表</a>
						</li>
						<li>
							<a href="{{ route('analytics.likeform') }}">用户互动报表</a>
						</li>
					</ul>
					{{-- /.nav-second-level --}}
				</li>
				<li>
					<a href="javascript:void();"><i class="fa fa-line-chart"></i> 趋势统计<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="{{ route('analytics.usercharts') }}">用户详细趋势图</a>
						</li>
						<li>
							<a href="{{ route('analytics.forumcharts') }}">论坛活动趋势图</a>
						</li>
						<li>
							<a href="{{ route('analytics.likecharts') }}">用户互动趋势图</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:void();"><i class="fa fa-newspaper-o"></i> 新闻管理<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="{{ route('categories.index') }}">分类目录</a>
						</li>
						<li>
							<a href="{{ route('admin.articles.index') }}">文章管理</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="{{ route('admin.support.index') }}"><i class="fa fa-support fa-fw"></i> 反馈管理</a>
				</li>
				<li>
					<a href="#"><i class="fa fa-list fa-fw"></i> 网站菜单<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="{{ route('home') }}">访问首页</a>
						</li>
						<li>
							<a href="{{ route('account') }}">个人信息</a>
						</li>
						<li>
							<a href="{{ route('members.index') }}">缘来在这</a>
						</li>
						<li>
							<a href="{{ route('account.notifications') }}">我的来信</a>
						</li>
						<li>
							<a href="{{ route('forum.index') }}">单身公寓</a>
						</li>
						<li>
							<a href="{{ route('account.posts') }}">我的帖子</a>
						</li>
						<li>
							<a href="{{ route('support.index') }}">联系客服</a>
						</li>
						<li>
							<a href="{{ route('home') }}">关于我们</a>
						</li>
						<li>
							<a href="{{ route('signout') }}">退出登录</a>
						</li>
					</ul>
					{{-- /.nav-second-level --}}
				</li>
			</ul>
		</div>
		{{-- /.sidebar-collapse --}}
	</div>
	{{-- /.navbar-static-side --}}
</nav>