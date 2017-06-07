@extends("layouts.admin.manage")
@section("title")
	Add Store
@endsection
@section("heading")
	New Store Info
@endsection
@section("managecontent")
	<form class="panel-body " enctype="multipart/form-data" role="form" method="POST"
		action="{{ url("/admin/stores/add") }}">
		{!! csrf_field() !!}
		<div class="form-group">
			<label for="name-input" class="control-label">Store Name</label>
			<div >
				<input required type="text" class="form-control" name="store[name]" id="name-input"
					value="{{ old("name") }}">
			</div>
		</div>
		<div class="form-group">
			<label for="owner-name-input" class="control-label">Owner Name</label>
			<div >
				<input required type="text" class="form-control" name="store[owner_name]" id="owner-name-input"
					value="{{ old("owner_name") }}">
			</div>
		</div>
		<div class="form-group{{ $errors->has("email") ? " has-error" : "" }}">
			<label for="email-input" class="control-label">E-Mail Address</label>
			<div >
				<input required type="email" class="form-control" name="store[email]" id="email-input"
					value="{{old("email")}}">
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
				<input required type="tel" class="form-control" name="store[phone]" id="phone-input"
					value="{{ old("phone")}}">
			</div>
		</div>
		<div class="form-group">
			<label for="address-input" class="control-label">Address</label>
			<div >
				<input required type="text" class="form-control" name="store[address]" id="address-input"
					value="{{ old("address") }}">
			</div>
		</div>
		<div class="form-group">
			<label for="taxrate-input" class="control-label">Tax Rate (%)</label>
			<div >
				<input required step="0.01" type="number" name="store[taxrate]" class="form-control" id="taxrate-input"
					value="{{ old("taxrate") }}">
			</div>
		</div>
		<div class="form-group">
			<label for="delivery-input" class="control-label">Delivery Fee</label>
			<div >
				<input required step="0.01" type="number" name="store[delivery]" class="form-control"
					id="delivery-input" value="{{ old("delivery") }}">
			</div>
		</div>
		<div class="form-group">
			<label for="fixed-fee-input" class="control-label">Fixed Fee</label>
			<div >
				<input step="0.01" type="number" class="form-control" name="store[fixed_fee]" id="fixed-fee-input"
					value="{{ old("fixed_fee") or 0 }}">
			</div>
		</div>
		<div class="form-group">
			<label for="percent-fee-input" class="control-label">Percent Fee (%)</label>
			<div >
				<input step="0.01" type="number" class="form-control" name="store[percent_fee]" id="percent-fee-input"
					value="{{ old("percent_fee") or 0 }}">
			</div>
		</div>
		<div class="form-group">
			<label for="contract-input" class="control-label">Contract File</label>
			<div >
				<span id="contract-button" class="btn btn-default btn-file">
					<span id="contract-button-text">Browse...</span>
					<input class="contract-file" type="file" name="store[contract]" id="contract-input">
				</span>
			</div>
		</div>
		<div class="form-group">
			<div class="pull-right">
				<button type="submit" class="btn btn-primary">
					<i class="fa fa-btn fa-user"></i>
					Create Store
				</button>
			</div>
		</div>
	</form>
@endsection