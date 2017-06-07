@extends("layouts.email")@section("headerImage",asset("branding/forgot_password_header.png"))
@section("emailText")
	<p align="left">Click
		<a href="{{ $link = url("driver/password/reset", $token)."?email=".urlencode($user->getEmailForPasswordReset()) }}">
			here
		</a>
		to reset your password.
	</p>
@endsection


