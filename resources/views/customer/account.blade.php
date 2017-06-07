@extends("layouts.customer")
@section("title")
	Account
@endsection
@section("content")
	<div class="panel-heading">Account Info</div>
	<form class="panel-body " role="form" method="POST" action="{{ url("/account") }}">
		{!! csrf_field() !!}
		<div class="form-group{{ $errors->has("email") ? " has-error" : "" }}">
			<label for="email-input" class="control-label">E-Mail Address</label>
			<div >
				<input type="email" class="form-control" name="email" id="email-input" value="{{ $customer->email }}">
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
				<input type="text" class="form-control" name="name" id="name-input" value="{{ $customer->name }}">
			</div>
		</div>
		<div class="form-group">
			<label for="phone-input" class="control-label">Phone</label>
			<div >
				<input type="tel" class="form-control" name="phone" id="phone-input" value="{{ $customer->phone }}">
			</div>
		</div>
		@include("shared.accountpasswordchange")
		
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