@extends("layouts.email")@section("headerImage",asset("branding/welcome_header.png"))
@section("emailText")
	<p align="left">Your password is: {{$password}}</p>
	<br>
	<p align="left">Login
		<a href="{{ url("/store/login") }}">here</a>
		and click Account to change your password.
	</p>
	<br>
	<p align="left">Cheers,</p>
	<p align="left">-The BoozCrew</p>
@endsection



