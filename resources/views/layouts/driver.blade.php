@extends("layouts.master")
@section("layout")
	<div id="content" class="panel panel-default">@yield("content")</div>
@endsection
@section("home", url("/driver") )

@section("navbar-main")
	<li>
		<a href="{{ url("/driver/orders") }}">Orders</a>
	</li>
	<li>
		<a href="{{ url("/driver/orders/history") }}">Order History</a>
	</li>
	<li>
		<a href="{{ url("/driver/tips") }}">Tips</a>
	</li>
@endsection
@section("navbar-account")
	@if (auth("driver")->guest())
		<li>
			<a href="{{ url("/driver/login") }}">Login</a>
		</li>
		<li>
			<a href="{{ url("/driver/apply") }}">Apply</a>
		</li>
	@else
		<li>
			<a href="{{ url("/driver/logout") }}">Logout</a>
		</li>
		<li>
			<a href="{{ url("/driver/account") }}">Account</a>
		</li>
	@endif
@endsection
@section("navbar")
	@include("shared.navbar")
@endsection