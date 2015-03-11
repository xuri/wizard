<!DOCTYPE html>
<html>
<head>
	{{-- The Meta --}}
	<title>{{ Lang::get('navigation.pinai') }} | {{ Lang::get('index.title') }}</title>

	@include('layout.meta')
	@yield('content')

	<!--[if lte IE 9]>
		<script type=text/javascript>window.location.href="{{ route('browser_not_support') }}";  </script>
	<![endif]-->

	{{-- The Stylesheets --}}

	{{ HTML::style('assets/font-awesome-4.2.0/css/font-awesome.min.css') }}

	{{ Minify::stylesheet(array(
		'/assets/css/bootstrap.css',
		'/assets/css/bootstrap-theme.css',
		'/assets/css/main.css',
		'/assets/css/animation.css'
	)) }}
</head>
	<body>
	<noscript>
		@include('system.javascriptNotSupport')
		@yield('content')
		<style type="text/css">
			.content, footer {
				display: none;
			}
		</style>
	</noscript>
	@if(Auth::guest())

	{{-- jQuery --}}
	{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

	<script type="text/javascript">
		// for(i=0;i<1;i++){
		// 	Vartest=window.prompt("请输入内测码:", '');
		// 	if(Vartest=='pinai'){
		// 		break;
		// 	} else {
		// 		alert("获取内测码 请加QQ: 523591643");
		// 		window.open('','_self','');
		// 		window.close();
		// 		$("body").css('display', 'none');
		// 	}
		// }
	</script>
	@else
	@endif
	<div class="content">

		<section id="home" class="appear"></section>
		<div class="navbar navbar-fixed-top" data-0="line-height:160px; height:160px; background-color:rgba(0,0,0,0);" data-300="line-height:60px; height:60px; background-color:rgba(29,33,37,1);">
			 <div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="fa fa-reorder icon resp-menu"></span>
					</button>
					<a class="navbar-brand" href="javascript:void(0);" data-0="line-height:130px;" data-300="line-height:56px;">
						{{ HTML::image('assets/images/logo.png', '', array('data-300' => 'width:50px;', 'data-0' => 'width:72px;', 'width' => '72')) }}
					</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav" data-0="margin-top:40px;" data-300="margin-top:1px;">
						<li class="colored active"><a href="{{ route('home') }}">{{ Lang::get('navigation.index') }}</a><div class="hover colored-bg"></div></li>
						<li class="colored"><a href="{{ route('members.index') }}">{{ Lang::get('navigation.discover') }}</a><div class="hover colored-bg"></div></li>
						<li class="colored"><a href="{{ route('forum.index') }}">{{ Lang::get('navigation.forum') }}</a><div class="hover colored-bg"></div></li>
						@if(Auth::guest()){{-- Guest --}}
						<li class="colored"><a href="{{ route('signin') }}">{{ Lang::get('navigation.signin') }}</a><div class="hover colored-bg"></div></li>
						<li class="colored"><a href="{{ route('signup') }}">{{ Lang::get('navigation.signup') }}</a><div class="hover colored-bg"></div></li>
						@elseif(! Auth::user()->is_admin){{-- User --}}
						<li class="colored"><a href="{{ route('account') }}">{{ Lang::get('navigation.profile') }}</a><div class="hover colored-bg"></div></li>
						<li class="colored"><a href="{{ route('signout') }}">{{ Lang::get('navigation.signout') }}</a><div class="hover colored-bg"></div></li>
						@elseif(Auth::user()->is_admin) {{-- Admin --}}
						<li class="colored"><a href="{{ route('admin') }}">{{ Lang::get('navigation.admin') }}</a><div class="hover colored-bg"></div></li>
						<li class="colored"><a href="{{ route('signout') }}">{{ Lang::get('navigation.signout') }}</a><div class="hover colored-bg"></div></li>
						@endif
						<li class="colored"><a href="{{ route('home') }}/article/about.html">{{ Lang::get('navigation.about') }}</a><div class="hover colored-bg"></div></li>
					</ul>
				</div>
				{{--/.navbar-collapse --}}
			</div>
		</div>

		<div class="fullwidthbanner-container overlay-fix">
			<div class="top-overlay"></div>
			<div class="fullwidthbanner" data-0="background-position:0px 0px;" data-end="background-position:0px 600px;">
				<div class="col-sm-12 header-area">
					<div class="row">
						<div class="col-sm-5 col-sm-offset-2 resp-center header animate animate_aft">
							{{ HTML::image('assets/images/main-header.png', '', array('class' => 'header-img')) }}
							<p class="header-txt">{{ Lang::get('index.slogan') }}<br/> —— {{ Lang::get('index.title') }}
								<br />
							</p>
							<a href="{{ route('members.index') }}" class="top-download btn btn-default btn-lg">{{ Lang::get('index.get_start') }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div> {{-- /content --}}

	{{-- FOOTER --}}
	<footer class="row footer colored-bg">
		<div class="col-sm-8 col-sm-offset-2">

			<div class="faq col-lg-6 col-sm-12 col-xs-12">

				<h3>{{ Lang::get('navigation.pinai') }}</h3>

				<div class="panel-group" id="accordion">

					<div class="panel panel-default">
						<div class="panel-heading">
							<h5 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="javascript:void(0);">
									{{ Lang::get('index.title_1') }}
								</a>
							</h5>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h5 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="javascript:void(0);">
									{{ Lang::get('index.title_2') }}
								</a>
							</h5>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h5 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="javascript:void(0);">
								{{ Lang::get('index.title_3') }}
								</a>
							</h5>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h5 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="javascript:void(0);">
								{{ Lang::get('index.title_4') }}
								</a>
							</h5>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-6 col-sm-12 col-xs-12 inverted resp-center">
				<div class="row no-offset">
					<div class="col-sm-12">
						<div class="col-sm-12">
							<h3>{{ Lang::get('index.download_app') }}</h3>
						</div>

							<div class="form-group">
								<div class="col-sm-6">
									<a href="javascript:void(0);" class="fixed form-control btn btn-default btn-sm ios-app-btn">&nbsp;App Store</a>
								</div>
								<div class="col-sm-6">
									<a href="http://fir.im/pinai" class="fixed form-control btn btn-default btn-sm" target="_blank"><i class="fa fa-android"></i>&nbsp;{{ Lang::get('index.android_app') }}</a>
								</div>
							</div>
							<div class="form-group resp-center">
								<center>
									{{ HTML::image('assets/images/qr.png', '', array('class' => 'qr', 'width' => '120')) }}
								</center>

							</div>
						<div id="message"></div>

					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-8 col-sm-offset-2">
			<p class="copy">Copyright &copy; 2013 - <?php echo date('Y'); ?> <a href="http://www.jinglingkj.com" target="_blank">{{ Lang::get('footer.company') }}</a> All rights reserved. {{ Lang::get('footer.icp_license') }} <a href="http://www.miitbeian.gov.cn/" target="_blank">黑ICP备14007294号</a></p>
		</div>
	</footer>

	{{-- jQuery --}}
	{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

	{{-- Bootstrap Core JavaScript --}}
	{{ HTML::script('assets/bootstrap-3.3.0/js/bootstrap.min.js') }}

	{{ Minify::javascript(array(
		'/assets/js/modernizr-2.6.2-respond-1.1.0.min.js',
		'/assets/js/skrollr/skrollr.min.js',
		'/assets/js/scrollTo/jquery.scrollTo-1.4.3.1-min.js',
		'/assets/js/scrollTo/jquery.localscroll-1.2.7-min.js',
		'/assets/js/appear/jquery.appear.js',
		'/assets/js/mainv2.js'
	)) }}

	{{-- Analytics Code --}}
	@include('layout.analytics')
	@yield('content')
	</body>
</html>