<!DOCTYPE html>
<html>
<head>
	{{-- The Meta --}}
	<title>聘爱 | 专注于大学生的恋爱平台</title>

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
	<div class="content">

		<section id="home" class="appear"></section>

		<div class="fullwidthbanner-container overlay-fix">
			<div class="top-overlay"></div>
		    <div class="fullwidthbanner" data-0="background-position:0px 0px;" data-end="background-position:0px 600px;">
				<div class="col-sm-12 header-area">
					<div class="row">
						<div class="col-sm-5 col-sm-offset-2 resp-center header animate animate_aft">
							{{ HTML::image('assets/images/main-header.png', '', array('class' => 'header-img')) }}
							<p class="header-txt">用心，寻找真爱<br/> —— 专注于大学生的恋爱平台
								<br />
							</p>
							<a href="{{ route('members.index') }}" class="top-download btn btn-default btn-lg">百度搜索《聘爱》<br />在电脑上也可以玩哦~</a>
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

				<h3>聘爱</h3>

				<div class="panel-group" id="accordion">

					<div class="panel panel-default">
						<div class="panel-heading">
							<h5 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="javascript:void(0);">
									这里不是拼脸的地方，寻找真爱请用
								</a>
							</h5>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h5 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="javascript:void(0);">
									这里是爱升华的地方，我们主张“心灵美”
								</a>
							</h5>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h5 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="javascript:void(0);">
								聘则为妻，妻者，夫之爱也。爱，生活也
								</a>
							</h5>
						</div>
					</div>

					<div class="panel panel-default">
						<div class="panel-heading">
							<h5 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="javascript:void(0);">
								这里不是商场，我们真正为你寻找靠谱的爱
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
							<h3>移动客户端下载</h3>
						</div>

							<div class="form-group">
								<div class="col-sm-6">
									<a href="javascript:void(0);" class="fixed form-control btn btn-default btn-sm"><i class="fa fa-apple"></i>&nbsp;AppStore 即将上线</a>
								</div>
								<div class="col-sm-6">
									<a href="http://fir.im/pinai" class="fixed form-control btn btn-default btn-sm" target="_blank"><i class="fa fa-android"></i>&nbsp;安卓下载</a>
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
			<p class="copy">Copyright &copy; 2013 - <?php echo date('Y'); ?> <a href="http://www.jinglingkj.com" target="_blank">哈尔滨精灵科技有限责任公司</a> All rights reserved. <a href="http://www.miitbeian.gov.cn/" target="_blank">黑ICP备14007294号</a></p>
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