<div id="navbar" class="navbar-fixed-top">
	<nav id="navbar-nav" class="container-scrollfix container-fluid navbar navbar-default" style="margin-bottom: 5px;">
		<div id="navbar-container" class="container">
			<div class="navbar-header vertical-center" style="display:flex">
				<a class="navbar-brand visible-lg-block" href="@yield("home")">
					<img class="image-brand" alt="Brand" src="{{asset("/branding/boozrun_logo.png")}}">
				</a>
				<a class="navbar-brand hidden-lg" href="@yield("home")">
					<img class="image-brand" alt="Brand" src="{{asset("/branding/boozrun_logo_small.png")}}">
				</a>
				<div class="form-inline visible-xs-inline-block">
					@yield("navbar-search")
				</div>
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
					data-target="#app-navbar-collapse">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="collapse navbar-collapse" id="app-navbar-collapse">
				<ul class="nav navbar-nav hidden-sm">
					@yield("navbar-main")
				</ul>
				<ul class="nav navbar-nav hidden-sm navbar-right">
					{{--<li class="nav-divider"></li>--}}
					@yield("navbar-account")
				</ul>
				<ul class="nav navbar-nav visible-sm-block navbar-right">
					@yield("navbar-main")
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
							aria-expanded="false">
							<i class="fa fa-user"></i>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu dropdown-menu-right">
							@yield("navbar-account")
						</ul>
					</li>
				</ul>
				<div class="navbar-form navbar-right hidden-xs">
					@yield("navbar-search")
				</div>
			</div>
		</div>
	</nav>
</div>
