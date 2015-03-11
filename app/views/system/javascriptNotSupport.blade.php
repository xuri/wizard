{{ HTML::style('assets/css/404.css') }}
	<header class="primary-header">
		<div class="row">
			<div class="twelve columns">
				<div class="column-inner clearfix">
					<a href="{{ route('home') }}" class="primary-header-logo fl">
						{{ HTML::image('assets/images/404_logo.png', '', array('width' => '103', 'id' => 'primry-header-logo-kiss', 'height' => '31')) }}
					</a>
					<a href="{{ route('signin') }}" class="primary-header-signin fr typeface-bold underlined-link">{{ Lang::get('navigation.signin') }}</a>
				</div>
			</div>
		</div>
		<hr class="rainbow">
	</header>

	<div class="row">
		<div class="twelve columns">
			<div class="column-inner"></div>
		</div>
	</div>
	<div class="row">
		<div class="eight columns centered">
			<div class="column-inner">
				<div class="panel">
					<div class="panel-body">
						<div class="ibc" style="margin: 20px 0">
							<div class="ibc-content">
								{{ HTML::image('assets/images/error.png', '', array('width' => '200', 'height' => '200')) }}
							</div>
						</div>
						<div class="ibc" style="margin: 20px 0">
							<div class="ibc-content">
								<h1 class="bold">浏览器不支持 Javascript</h1>
							</div>
						</div>
						<p style="margin: 10px 44px; text-align: left; font-size: 14px;">欢迎来到“聘爱”，请开启 Javascript 脚本支持或使用 <a href="{{ route('browser_not_support') }}">我们推荐的浏览器</a>。</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-8 col-sm-offset-2">
		<p class="copy" style="text-align: center">Copyright &copy; 2013 - <?php echo date('Y'); ?> <a href="http://www.jinglingkj.com" target="_blank">{{ Lang::get('footer.company') }}</a> All rights reserved. {{ Lang::get('footer.icp_license') }} <a href="http://www.miitbeian.gov.cn/" target="_blank">黑ICP备14007294号</a></p>
	</div>
</body>
</html>