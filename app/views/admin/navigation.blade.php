<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">{{ Lang::get('navigation.navigation') }}</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="{{ route('admin') }}">{{ Lang::get('navigation.pinai') }} · {{ Lang::get('navigation.dashboard') }}</a>
	</div>
	{{-- /.navbar-header --}}

	<ul class="nav navbar-top-links navbar-right">
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
			</a>
			<ul class="dropdown-menu dropdown-user">
				<li>
					<a href="{{ route('home') }}"><i class="fa fa-home fa-fw"></i> {{ Lang::get('navigation.go_home') }}</a>
				</li>
				<li>
					<a href="{{ route('account') }}"><i class="fa fa-user fa-fw"></i> {{ Lang::get('navigation.profile') }}</a>
				</li>
				<li>
					<a href="{{ route('admin') }}"><i class="fa fa-gear fa-fw"></i> {{ Lang::get('navigation.dashboard') }}</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="{{ route('signout') }}"><i class="fa fa-sign-out fa-fw"></i> {{ Lang::get('navigation.signout') }}</a>
				</li>
			</ul>
			{{-- /.dropdown-user --}}
		</li>
		{{-- /.dropdown --}}
	</ul>
	{{-- /.navbar-top-links --}}

	<div class="navbar-default sidebar" role="navigation">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav" id="side-menu">
				<li class="sidebar-search">
					<div class="input-group custom-search-form">
						<input type="text" class="form-control" placeholder="{{ Lang::get('system.search') }}...">
						<span class="input-group-btn">
						<button class="btn btn-default" type="button">
							<i class="fa fa-search"></i>
						</button>
					</span>
					</div>
					{{-- /input-group --}}
				</li>
				<li>
					<a href="{{ route('admin') }}"><i class="fa fa-dashboard fa-fw"></i> {{ Lang::get('navigation.dashboard') }}</a>
				</li>
				<li>
					<a href="{{ route('admin.server') }}"><i class="fa fa-sliders fa-fw"></i> {{ Lang::get('navigation.admin_server') }}</a>
				</li>
				<li>
					<a href="javascript:void();"><i class="fa fa-users fa-fw"></i> {{ Lang::get('navigation.admin_user_management') }}<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="{{ route('users.index') }}">{{ Lang::get('navigation.admin_user_all') }}</a>
						</li>
						<li>
							<a href="{{ route('users.noactive') }}">{{ Lang::get('navigation.admin_user_noactive') }}</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:void();"><i class="fa fa-tags"></i> {{ Lang::get('navigation.admin_forum_management') }}<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="{{ route('admin.forum.index') }}">{{ Lang::get('navigation.admin_forum_post') }}</a>
						</li>
					</ul>
					{{-- /.nav-second-level --}}
				</li>
				<li>
					<a href="{{ route('admin.university.index') }}"><i class="fa fa-bank fa-fw"></i> {{ Lang::get('navigation.admin_school_management') }}</a>

				</li>
				<li>
					<a href="javascript:void();"><i class="fa  fa-table fa-fw"></i> {{ Lang::get('navigation.admin_analytics_table') }}<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="{{ route('analytics.userform') }}">{{ Lang::get('navigation.admin_analytics_users_table') }}</a>
						</li>
						<li>
							<a href="{{ route('analytics.forumform') }}">{{ Lang::get('navigation.admin_analytics_forum_table') }}</a>
						</li>
						<li>
							<a href="{{ route('analytics.likeform') }}">{{ Lang::get('navigation.admin_analytics_active_table') }}</a>
						</li>
					</ul>
					{{-- /.nav-second-level --}}
				</li>
				<li>
					<a href="javascript:void();"><i class="fa fa-line-chart"></i> {{ Lang::get('navigation.admin_analytics_charts') }}<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="{{ route('analytics.usercharts') }}">{{ Lang::get('navigation.admin_analytics_users_charts') }}</a>
						</li>
						<li>
							<a href="{{ route('analytics.forumcharts') }}">{{ Lang::get('navigation.admin_analytics_forum_charts') }}</a>
						</li>
						<li>
							<a href="{{ route('analytics.likecharts') }}">{{ Lang::get('navigation.admin_analytics_active_charts') }}</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="javascript:void();"><i class="fa fa-newspaper-o"></i> {{ Lang::get('navigation.admin_news_management') }}<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="{{ route('categories.index') }}">{{ Lang::get('navigation.admin_news_category') }}</a>
						</li>
						<li>
							<a href="{{ route('admin.articles.index') }}">{{ Lang::get('navigation.admin_news_post') }}</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="{{ route('admin.support.index') }}"><i class="fa fa-support fa-fw"></i> {{ Lang::get('navigation.admin_support_management') }}</a>
				</li>
				<li>
					<a href="#"><i class="fa fa-list fa-fw"></i> {{ Lang::get('navigation.navigation') }}<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="{{ route('home') }}">{{ Lang::get('navigation.index') }}</a>
						</li>
						<li>
							<a href="{{ route('account') }}">{{ Lang::get('navigation.profile') }}</a>
						</li>
						<li>
							<a href="{{ route('members.index') }}">{{ Lang::get('navigation.discover') }}</a>
						</li>
						<li>
							<a href="{{ route('account.notifications') }}">{{ Lang::get('navigation.inbox') }}</a>
						</li>
						<li>
							<a href="{{ route('forum.index') }}">{{ Lang::get('navigation.forum') }}</a>
						</li>
						<li>
							<a href="{{ route('account.posts') }}">{{ Lang::get('navigation.my_posts') }}</a>
						</li>
						<li>
							<a href="{{ route('support.index') }}">{{ Lang::get('navigation.support') }}</a>
						</li>
						<li>
							<a href="{{ route('home') }}">{{ Lang::get('navigation.about') }}</a>
						</li>
						<li>
							<a href="{{ route('signout') }}">{{ Lang::get('navigation.signout') }}</a>
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