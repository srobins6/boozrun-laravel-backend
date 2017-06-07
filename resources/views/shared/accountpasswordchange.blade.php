<div class="form-group{{ $errors->has("currentPassword") ? " has-error" : "" }}">
	<label for="current-password-input" class="control-label">Current Password</label>
	<div>
		<input required type="password" class="form-control" name="currentPassword" id="current-password-input"
			autocomplete="off">
		@if ($errors->has("currentPassword"))
			<span class="help-block">
				<strong>{{ $errors->first("currentPassword") }}</strong>
			</span>
		@endif
	</div>
</div>
<div class="form-group{{ $errors->has("password") ? " has-error" : "" }}">
	<label for="password-input" class="control-label">New Password</label>
	<div>
		<input type="password" class="form-control" name="password" id="password-input" autocomplete="off">
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
		<input type="password" class="form-control" name="password_confirmation"
			id="password-confirmation-input" autocomplete="off">
		@if ($errors->has("password_confirmation"))
			<span class="help-block">
				<strong>{{ $errors->first("password_confirmation") }}</strong>
			</span>
		@endif
	</div>
</div>