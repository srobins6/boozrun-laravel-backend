@extends("layouts.admin")
@section("title")
	Login
@endsection
@section("content")
	<div class="panel-heading">Login</div>
	<form class="panel-body " role="form" method="POST" action="{{ url("/admin/login") }}">
		{!! csrf_field() !!}
		<div class="form-group{{ $errors->has("email") ? " has-error" : "" }}">
			<label for="email-input" class="control-label">E-Mail Address</label>
			<div>
				<input type="email" required class="form-control" name="email" id="email-input"
					value="{{ old("email") }}">
				@if ($errors->has("email"))
					<span class="help-block">
						<strong>{{ $errors->first("email") }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="form-group{{ $errors->has("password") ? " has-error" : "" }}">
			<label for="password-input" class="control-label">Password</label>
			<div>
				<input type="password" required class="form-control" name="password" id="password-input">
				@if ($errors->has("password"))
					<span class="help-block">
						<strong>{{ $errors->first("password") }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="form-group">
			<div class="pull-right text-right">
				<button type="submit" class="btn btn-primary">
					<i class="fa fa-btn fa-sign-in"></i>
					Login
				</button>
				<div class="checkbox">
					<label>
						<input type="checkbox" name="remember">
						Remember Me
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="pull-left">
				<a class="btn btn-link" href="{{ url("/admin/password/reset") }}">Forgot Your Password?</a>
			</div>
		</div>
	</form>
@endsection
