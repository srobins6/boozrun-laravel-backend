@extends("layouts.customer") @section("title")
Signup
@endsection
@section("content")
	<div class="panel-heading">Signup</div>
	<form class="panel-body " role="form" method="POST" action="{{ url("/signup") }}">
		{!! csrf_field() !!}
		<div class="form-group">
			<label for="name-input" class="control-label">Name</label>
			<div >
				<input required type="text" class="form-control" name="name" id="name-input" value="{{ old("name") }}">
			</div>
		</div>
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
			<label for="phone-input" class="control-label">Phone Number</label>
			<div >
				<input required type="tel" class="form-control" name="phone" id="phone-input"
				       value="{{ old("phone") }}">
			</div>
		</div>
		<div class="form-group">
			<label for="birthday-input" class="control-label">Birthday</label>
			<div >
				<input required type="date" max="{{ $maxDate}}" class="form-control" name="birthday" id="birthday-input"
				       value="{{ old("birthday") }}">
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
			<div class="pull-right">
				<button type="submit" class="btn btn-primary">
					<i class="fa fa-btn fa-user"></i>
					Signup
				</button>
			</div>
		</div>
	</form>
@endsection