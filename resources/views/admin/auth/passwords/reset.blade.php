@extends("layouts.admin")
@section("title")
Reset Password
@endsection
@section("content")
	<div class="panel-heading">Reset Password</div>
	<form class="panel-body " role="form" method="POST" action="{{ url("/admin/password/reset") }}">
		{!! csrf_field() !!}
		<input title=Token" type="hidden" name="token" value="{{ $token }}">
		<div class="form-group{{ $errors->has("email") ? " has-error" : "" }}">
			<label for="email-input" class="control-label">E-Mail Address</label>
			<div >
				<input type="email" required class="form-control" name="email" id="email-input"
				       value="{{ $email or old("email") }}">
				@if ($errors->has("email"))
					<span class="help-block">
						<strong>{{ $errors->first("email") }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="form-group{{ $errors->has("password") ? " has-error" : "" }}">
			<label for="password-input" class="control-label">Password</label>
			<div >
				<input type="password" required class="form-control" name="password" id="password-input">
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
				<input type="password" required class="form-control" name="password_confirmation"
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
					<i class="fa fa-btn fa-refresh"></i>
					Reset Password
				</button>
			</div>
		</div>
	</form>
@endsection
