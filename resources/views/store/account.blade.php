@extends("layouts.store")
@section("title")
	Account
@endsection
@section("content")
	<div class="panel-heading">Account Info</div>
	<form class="panel-body " role="form" method="POST" action="{{ url("/store/account") }}">
		{!! csrf_field() !!}
		<div class="form-group">
			<label for="name-input" class="control-label">Store Name</label>
			<div>
				<input type="text" class="form-control" name="name" id="name-input" value="{{ $store->name }}">
			</div>
		</div>
		<div class="form-group">
			<label for="owner-name-input" class="control-label">Owner Name</label>
			<div>
				<input type="text" class="form-control" name="owner_name" id="owner-name-input"
					value="{{ $store->owner_name }}">
			</div>
		</div>
		<div class="form-group{{ $errors->has("email") ? " has-error" : "" }}">
			<label for="email-input" class="control-label">E-Mail Address</label>
			<div>
				<input type="email" class="form-control" name="email" id="email-input" value="{{$store->email}}">
				@if ($errors->has("email"))
					<span class="help-block">
						<strong>{{ $errors->first("email") }}</strong>
					</span>
				@endif
			</div>
		</div>
		<div class="form-group">
			<label for="phone-input" class="control-label">Phone Number</label>
			<div>
				<input type="tel" class="form-control" name="phone" id="phone-input" value="{{ $store->phone}}">
			</div>
		</div>
		<div class="form-group">
			<label for="address-input" class="control-label">Address</label>
			<div>
				<input type="text" class="form-control" name="address" id="address-input" value="{{ $store->address }}">
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
