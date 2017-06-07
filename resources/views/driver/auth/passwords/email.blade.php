@extends("layouts.driver")
@section("title")
Reset Password
@endsection
@section("content")
	<div class="panel-heading">Reset Password</div>
	<form class="panel-body " role="form" method="POST" action="{{ url("/driver/password/email") }}">
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
			<div class="pull-right">
				<button type="submit" class="btn btn-primary">
					<i class="fa fa-btn fa-envelope"></i>
					Send Password Reset Link
				</button>
			</div>
		</div>
	</form>
@endsection