@extends("layouts.master")
@section("layout")
	<div id="content" class="panel panel-default">@yield("content")</div>
@endsection
@section("home", url("/admin") )

@section("navbar-main")
	<li>
		<a href="{{ url("/admin/stores") }}">Stores</a>
	</li>
	@if(auth("admin")->guest() || $control == true)
		<li>
			<a href="{{ url("/admin/categories") }}">Categories</a>
		</li>
		<li>
			<a href="{{ url("/admin/promos") }}">Promos</a>
		</li>
		<li>
			<a href="{{ url("/admin/images") }}">Images</a>
		</li>
		<li>
			<a href="{{ url("/admin/customers") }}">Customers</a>
		</li>
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
				aria-expanded="false">Drivers
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li>
					<a href="{{ url("/admin/drivers/confirmed") }}">Confirmed</a>
				</li>
				<li>
					<a href="{{ url("/admin/drivers/applicants") }}">Applicants</a>
				</li>
			</ul>
		</li>
		<li>
			<a href="{{ url("/admin/admins") }}">Admins</a>
		</li>
	@endif
@endsection
@section("navbar-account")
	@if (auth("admin")->guest())
		<li>
			<a href="{{ url("/admin/login") }}">Login</a>
		</li>
	@else
		<li>
			<a href="{{ url("/admin/logout") }}">Logout</a>
		</li>
		<li>
			<a href="{{ url("/admin/account") }}">Account</a>
		</li>
	@endif
@endsection
@section("navbar")
	@include("shared.navbar")
@endsection