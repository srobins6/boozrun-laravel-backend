@extends("layouts.email")@section("headerImage",asset("branding/driver_apply_header.png"))
@section("emailText")
	<p align="left">Dear {{$driver->name}}</p>
	<br>
	<p align="left">Thank you for applying to become a BoozRun driver. Our Partnering retailer will review your
		application. If you are approved, you will receive a second email and a call from the retailer.
	</p>
	<br>
	<p align="left">Note: Our partnering retailer will be your employer if hired.</p>
	<br>
	<p align="left">Cheers,</p>
	<p align="left">-The BoozCrew</p>
@endsection


