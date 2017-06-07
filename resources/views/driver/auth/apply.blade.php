@extends("layouts.driver")
@section("title")
	Apply
@endsection
@section("content")
	<div class="panel-heading">Apply</div>
	<form class="panel-body " role="form" method="POST" action="{{ url("/driver/apply") }}">
		{!! csrf_field() !!}
		<div class="form-group{{ $errors->has("email") ? " has-error" : "" }}">
			<label for="email-input" class="control-label">E-Mail Address</label>
			<div >
				<input required type="email" class="form-control" name="email" id="email-input"
					value="{{ old("email") }}">
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
				<input required type="text" class="form-control" name="name" id="name-input" value="{{ old("name") }}">
			</div>
		</div>
		<div class="form-group">
			<label for="phone-input" class="control-label">Phone</label>
			<div >
				<input required type="tel" class="form-control" name="phone" id="phone-input"
					value="{{ old("phone") }}">
			</div>
		</div>
		<div class="form-group">
			<label for="city-select" class="control-label">City</label>
			<div >
				<select required class="form-control" name="city" id="city-select">
					@foreach($cities as $city)
						<option value="{{$city}}" {{old("city") == $city ? "selected":""}}>{{$city}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group{{ $errors->has("password") ? " has-error" : "" }}">
			<label for="password-input" class="control-label">Password</label>
			<div >
				<input required type="password" class="form-control" name="password" id="password-input">
				@if ($errors->has("password"))
					<span class="help-block">
						<strong>{{ $errors->first("password") }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="form-group{{ $errors->has("password_confirmation") ? " has-error" : "" }}">
			<label for="password-confirmation-input" class="control-label">Confirm Password</label>
			<div >
				<input required type="password" class="form-control" name="password_confirmation"
					id="password-confirmation-input">
				@if ($errors->has("password_confirmation"))
					<span class="help-block">
						<strong>{{ $errors->first("password_confirmation") }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">Have you ever been convicted of a crime?</label>
			<div >
				<label class="radio-inline">
					<input type="radio" class="driver-option" name="crime" value=0 checked>
					No
				</label>
				<label class="radio-inline">
					<input type="radio" class="driver-option" name="crime" value=1>
					Yes
				</label>
			</div>
		</div>
		<div id="crime-details" class="hidden form-group">
			<label for="crime-details-input" class="control-label">Explain</label>
			<div >
				<textarea disabled rows=3 class="application-details form-control" name="crime_details"
					id="crime-details-input">{{ old("crime_details") }}</textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">Have you had any accidents in the past year?</label>
			<div >
				<label class="radio-inline">
					<input type="radio" class="driver-option" name="accidents" value=0 checked>
					No
				</label>
				<label class="radio-inline">
					<input type="radio" class="driver-option" name="accidents" value=1>
					Yes
				</label>
			</div>
		</div>
		<div id="accidents-details" class="hidden form-group">
			<label for="accidents-details-input" class="control-label">How many?</label>
			<div >
				<input disabled type="number" min="1" name="accidents_details" id="accidents-details-input"
					class="application-details form-control" value="{{ old("accidents_details") or "1" }}">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">Have you had any traffic violations in the past year?</label>
			<div >
				<label class="radio-inline">
					<input type="radio" class="driver-option" name="violations" value=0 checked>
					No
				</label>
				<label class="radio-inline">
					<input type="radio" class="driver-option" name="violations" value=1>
					Yes
				</label>
			</div>
		</div>
		<div id="violations-details" class="hidden form-group">
			<label for="violations-details-input" class="control-label">How many?</label>
			<div >
				<input disabled type="number" min="1" name="violations_details" id="violations-details-input"
					class="application-details form-control" value="{{ old("violations_details") or "1" }}">
			</div>
		</div>
		<div class="form-group">
			<div class="pull-right">
				<button type="submit" class="btn btn-primary">
					<i class="fa fa-btn fa-user"></i>
					Apply
				</button>
			</div>
		</div>
	</form>
@endsection