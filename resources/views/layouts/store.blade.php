@extends("layouts.master")
@section("layout")
	<div id="content" class="panel panel-default">@yield("content")</div>
@endsection
@section("home", url("/store") )
@section("navbar-main")
	<li>
		<a href="{{ url("/store/orders") }}">Orders</a>
	</li>
	<li>
		<a href="{{ url("/store/drivers") }}">Drivers</a>
	</li>
	<li>
		<a href="{{ url("/store/products") }}">Products</a>
	</li>
	<li>
		<a href="{{ url("/store/hours") }}">Hours</a>
	</li>

@endsection
@section("navbar-account")
	@if (auth("store")->guest())
		<li>
			<a href="{{ url("/store/login") }}">Login</a>
		</li>
	@else
		<li>
			<a href="{{ url("/store/logout") }}">Logout</a>
		</li>
		<li>
			<a href="{{ url("/store/account") }}">Account</a>
		</li>
	@endif
@endsection
@section("navbar")
	@include("shared.navbar")
@endsection