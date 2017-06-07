@extends("layouts.admin.manage")
@section("title")
	Add Admin
@endsection
@section("heading")
	New Admin Info
@endsection
@section("managecontent")
	<form class="panel-body " role="form" method="POST" action="{{ url("/admin/admins/add") }}">
		{!! csrf_field() !!}
		<div class="form-group{{ $errors->has("email") ? " has-error" : "" }}">
			<label for="email-input" class="control-label">E-Mail Address</label>
			<div >
				<input required type="email" class="form-control" name="admin[email]" id="email-input"
					value="{{old("email")}}">
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
				<input required type="text" class="form-control" name="admin[name]" id="name-input"
					value="{{ old("name") }}">
			</div>
		</div>
		<div class="form-group{{ $errors->has("password") ? " has-error" : "" }}">
			<label for="password-input" class="control-label">Password</label>
			<div >
				<input required type="password" class="form-control" name="admin[password]" id="password-input">
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
				<input required type="password" class="form-control" name="admin[password_confirmation]"
					id="password-confirmation-input">
				@if ($errors->has("password_confirmation"))
					<span class="help-block">
						<strong>{{ $errors->first("password_confirmation") }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">Full Control</label>
			<div >
				<label class="radio-inline">
					<input type="radio" name="admin[control]" value=0>
					No
				</label>
				<label class="radio-inline">
					<input type="radio" name="admin[control]" value=1 checked>
					Yes
				</label>
			</div>
		</div>
		<div class="form-group">
			<div class="pull-right">
				<button type="submit" class="btn btn-primary">
					<i class="fa fa-btn fa-user"></i>
					Create Admin
				</button>
			</div>
		</div>
	</form>
@endsection