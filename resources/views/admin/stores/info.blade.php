@extends("layouts.admin.store")
@section("title")
	{{$store->name}} Info
@endsection
@section("heading")
	{{$store->name}} Info
@endsection
@section("managecontent")
	<form class="panel-body " enctype="multipart/form-data" role="form" method="POST"
		action="{{ url("/admin/stores/$store->id/update") }}">
		{!! csrf_field() !!}
		<div class="form-group">
			<label for="name-input" class="control-label">Store Name</label>
			<div>
				<input type="text" class="form-control" name="store[name]" id="name-input" value="{{ $store->name }}">
			</div>
		</div>
		<div class="form-group">
			<label for="owner-name-input" class="control-label">Owner Name</label>
			<div>
				<input type="text" class="form-control" name="store[owner_name]" id="owner-name-input"
					value="{{ $store->owner_name }}">
			</div>
		</div>
		<div class="form-group{{ $errors->has("email") ? " has-error" : "" }}">
			<label for="email-input" class="control-label">E-Mail Address</label>
			<div>
				<input type="email" class="form-control" name="store[email]" id="email-input" value="{{$store->email}}">
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
				<input type="tel" class="form-control" name="store[phone]" id="phone-input" value="{{ $store->phone}}">
			</div>
		</div>
		<div class="form-group">
			<label for="address-input" class="control-label">Address</label>
			<div>
				<input type="text" class="form-control" name="store[address]" id="address-input"
					value="{{ $store->address }}">
			</div>
		</div>
		<div class="form-group">
			<label for="taxrate-input" class="control-label">Tax Rate (%)</label>
			<div>
				<input step="0.01" type="number" class="form-control" name="store[taxrate]" id="taxrate-input"
					value="{{ $store->taxrate }}">
			</div>
		</div>
		<div class="form-group">
			<label for="delivery-input" class="control-label">Delivery Fee</label>
			<div>
				<input step="0.01" type="number" class="form-control" name="store[delivery]" id="delivery-input"
					value="{{ $store->delivery }}">
			</div>
		</div>
		<div class="form-group">
			<label for="fixed-fee-input" class="control-label">Fixed Fee</label>
			<div>
				<input step="0.01" type="number" class="form-control" name="store[fixed_fee]" id="fixed-fee-input"
					value="{{ $store->fixed_fee }}">
			</div>
		</div>
		<div class="form-group">
			<label for="percent-fee-input" class="control-label">Percent Fee (%)</label>
			<div>
				<input step="0.01" type="number" class="form-control" name="store[percent_fee]" id="percent-fee-input"
					value="{{ $store->percent_fee }}">
			</div>
		</div>
		<div class="form-group">
			<label for="contract-input" class="control-label">Contract File</label>
			<div>
				@if(Storage::exists("store_contracts/$store->id"))
					<a download href="{{url("/admin/stores/$store->id/contract")}}">Current Contract</a>
				@endif
				<span id="contract-button" class="margin-left-5 btn btn-default btn-file">
					<span id="contract-button-text">Browse...</span>
					<input class="contract-file" type="file" name="store[contract]" id="contract-input">
				</span>
			</div>
		</div>
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
@section("buttons")
	<button {{$store->id==1 ? "disabled" : ""}} class="btn btn-danger model-delete-button-redirect" data-model="store"
		data-modelid="{{$store->id}}">Delete
	</button>
@endsection