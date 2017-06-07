@extends("layouts.store")
@section("title")
	Stripe Verification
@endsection
@section("content")

	<div class="panel-heading">Verify Stripe Account Info</div>
	<form class="panel-body " role="form" method="POST" action="{{ url("/store/verify") }}">
		{!! csrf_field() !!}
		<div class="form-group{{ $errors->has("account_number") ? " has-error" : "" }}">
			<label for="account_number-input" class="control-label">Account Number (account must be in the United
				States)
			</label>
			<div>
				<input required type="text" class="form-control" name="account_number" id="account_number-input"
					value="{{ old("account_number") }}">
				@if ($errors->has("account_number"))
					<span class="help-block">
						<strong>{{ $errors->first("account_number") }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="form-group{{ $errors->has("routing_number") ? " has-error" : "" }}">
			<label for="routing_number-input" class="control-label">Routing Number</label>
			<div>
				<input required type="text" class="form-control" name="routing_number" id="routing_number-input"
					value="{{ old("routing_number") }}">
				@if ($errors->has("routing_number"))
					<span class="help-block">
						<strong>{{ $errors->first("routing_number") }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="form-group{{ $errors->has("name") ? " has-error" : "" }}">
			<label for="name-input" class="control-label">Account Holder Name</label>
			<div>
				<input required type="text" class="form-control" name="name" id="name-input" value="{{ old("name") }}">
				@if ($errors->has("name"))
					<span class="help-block">
						<strong>{{ $errors->first("name") }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="form-group{{ $errors->has("birthday") ? " has-error" : "" }}">
			<label for="birthday-input" class="control-label">Account Holder Birthdate</label>
			<div>
				<input required type="date" class="form-control" name="birthday" id="birthday-input"
					value="{{ old("birthday") }}">
				@if ($errors->has("birthday"))
					<span class="help-block">
						<strong>{{ $errors->first("birthday") }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">
				<a href="https://stripe.com/connect/account-terms">Stripe Managed Account Terms Of Service</a>
			</label>
			<div>
				<label class="checkbox-inline">
					<input type="checkbox" required>
					I Agree
				</label>
			</div>
		</div>
		<div class="form-group">
			<div class="pull-right">
				<button type="submit" class="btn btn-primary">
					<i class="fa fa-btn fa-user"></i>
					Verify
				</button>
			</div>
		</div>
	</form>
@endsection
