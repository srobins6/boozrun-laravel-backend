@extends("layouts.email")@section("headerImage",asset("branding/driver_confirm_header.png"))
@section("emailText")
	<p align="left">Dear {{$driver->name}}</p>
	<br>
	<p align="left">This email is to certify that we have examined your application and believe you to be perfect for
		the driver position.
		<br>
	<p align="left">We can confirm that we are happy to grant you employment upon completion of a background check which
		can be expected within the next 3-4 business days.
		<br>
	<p align="left">Thank you for your interest and we look forward to your services. You will be receiving a phone call
		from the retailer regarding this offer. If you are interested in accepting this offer, we will speak to you
		about the training process and your start date.
		<br>
	<p align="left">Click
		<a href="{{url("/driver/login")}}">here</a>
		to login.
		<br>
	<p align="left">Note: Our partnered retailer will be your employer.
		<br>
	<p align="left">Cheers,</p>
	<p align="left">-The BoozCrew</p>
@endsection



