@include('home.header')
@yield('content')

	<div id="wrapper">
		@if(Agent::isMobile())
		@else
			@include('home.navigation')
			@yield('content')
		@endif


		@if(Agent::isMobile())
		@else
			<div class="row row-offcanvas row-offcanvas-right" style="margin-top: 8%">
		@endif
		        <div class="col-md-8 col-md-offset-2">

		            <div class="row">

		                <div class="col-12 col-sm-12 col-lg-12 panel">
		                    <h2>{{ $article->title }}</h2>
		                    <hr />
		                    <p>
		                        <i class="glyphicon glyphicon-calendar"></i><span> {{ $article->created_at }}</span>
		                    </p>
		                    <p>{{ $article->content }}</p>
		                    <a name="comments"></a>

		                </div><!--/span-->



		            </div><!--/row-->
		        </div><!--/span-->



		    </div><!--/row-->
		</div>
		<!-- /#page-wrapper -->

		@include('layout.copyright')
		@yield('content')
		<style>
			div.footer {
				text-align: center;
				color: #FFF;
				width: 100%;
				height: 50px;
				padding: 15px;
				margin: 0;
				background: #ef698a;
				font-size: 12px;
				position:fixed; bottom:0;

			}

			div.footer a {
				color: #FFF;
				font-size: 12px;
				margin: 0 auto;
			}
		</style>

	{{-- jQuery Version 1.11.0 --}}
	{{ HTML::script('assets/js/jquery-1.11.1/jquery.min.js') }}

	{{-- Bootstrap Core JavaScript --}}
	{{ HTML::script('assets/bootstrap-3.3.0/js/bootstrap.min.js') }}


</body>

</html>