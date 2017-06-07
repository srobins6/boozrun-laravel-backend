@extends("layouts.driver")
@section("title")
	Account
@endsection
@section("content")
	<div class="panel-heading">Account Info</div>
	<form class="panel-body " role="form" method="POST" action="{{ url("/driver/account") }}">
		{!! csrf_field() !!}
		<div class="form-group{{ $errors->has("email") ? " has-error" : "" }}">
			<label for="email-input" class="control-label">E-Mail Address</label>
			<div >
				<input type="email" class="form-control" name="email" id="email-input" value="{{ $driver->email }}">
				@if ($errors->has("email"))
					<span class="help-block">
						<strong>{{ $errors->first("email") }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="form-group">
			<label for="name-input" class="control-label">Name</label>
			<div >
				<input type="text" class="form-control" name="name" id="name-input" value="{{ $driver->name }}">
			</div>
		</div>
		<div class="form-group">
			<label for="phone-input" class="control-label">Phone</label>
			<div >
				<input type="tel" class="form-control" name="phone" id="phone-input" value="{{ $driver->phone }}">
			</div>
		</div>
		<div class="form-group">
			<label for="city-select" class="control-label">City</label>
			<div >
				<select class="form-control" name="city" id="city-select">
					@foreach($cities as $city)
						<option value="{{$city}}" {{$driver->city == $city ? "selected":""}}>{{$city}}</option>
					@endforeach
				</select>
			</div>
		</div>
		@include("shared.accountpasswordchange")
		
		<div class="form-group">
			<label class="control-label">Have you ever been convicted of a crime?</label>
			<div >
				<label class="radio-inline">
					<input type="radio" class="driver-option" name="crime" value=0
						{{$driver->crime ? "" : "checked"}}>
					No
				</label>
				<label class="radio-inline">
					<input type="radio" class="driver-option" name="crime" value=1
						{{$driver->crime ? "checked" : ""}}>
					Yes
				</label>
			</div>
		</div>
		<div id="crime-details" class="{{$driver->crime ? "" : "hidden "}}form-group">
			<label for="crime-details-input" class="control-label">Explain</label>
			<div >
				<textarea {{$driver->crime ? "required" : "disabled"}} rows=3 class="application-details form-control"
					name="crime_details" id="crime-details-input">{{ $driver->crime_details }}</textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">Have you had any accidents in the past year?</label>
			<div >
				<label class="radio-inline">
					<input type="radio" class="driver-option" name="accidents" value=0
						{{$driver->accidents ? "" : "checked"}}>
					No
				</label>
				<label class="radio-inline">
					<input type="radio" class="driver-option" name="accidents"
						value=1 {{$driver->accidents ? "checked" : ""}}>
					Yes
				</label>
			</div>
		</div>
		<div id="accidents-details" class="{{$driver->accidents ? "" : "hidden "}}form-group">
			<label for="accidents-details-input" class="control-label">How many?</label>
			<div >
				<input {{$driver->accidents ? "required" : "disabled"}} type="number" min="1" name="accidents_details"
					id="accidents-details-input" class="application-details form-control"
					value="{{ $driver->accidents_details or "1" }}">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">Have you had any traffic violations in the past year?</label>
			<div >
				<label class="radio-inline">
					<input type="radio" class="driver-option" name="violations"
						value=0 {{$driver->violations ? "" : "checked"}}>
					No
				</label>
				<label class="radio-inline">
					<input type="radio" class="driver-option" name="violations"
						value=1 {{$driver->violations ? "checked" : ""}}>
					Yes
				</label>
			</div>
		</div>
		<div id="violations-details" class="{{$driver->violations ? "" : "hidden "}}form-group">
			<label for="violations-details-input" class="control-label">How many?</label>
			<div >
				<input {{$driver->violations ? "required" : "disabled"}} type="number" min="1" name="violations_details"
					id="violations-details-input" class="application-details form-control"
					value="{{ $driver->violations_details or "1" }}">
			</div>
		</div>
		<div class="form-group">
			<div class="pull-right">
				<button type="submit" class="btn btn-primary">
					<i class="fa fa-btn fa-user"></i>
					Save
				</button>
			</div>
		</div>
	</form>
@endsection