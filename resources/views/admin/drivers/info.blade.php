@extends("layouts.admin.manage")
@section("title")
	{{$driver->name}} Info
@endsection
@section("heading")
	{{$driver->name}} Info
@endsection
@section("buttons")
	<button class="btn btn-danger model-delete-button-redirect" data-model="driver" data-modelid="{{$driver->id}}">
		Delete
	</button>
@endsection
@section("managecontent")
	<form class="panel-body " role="form" method="POST"
		action="{{ url("/admin/drivers/$driver->id/update") }}">
		{!! csrf_field() !!}
		<div class="form-group{{ $errors->has("email") ? " has-error" : "" }}">
			<label for="email-input" class="control-label">E-Mail Address</label>
			<div >
				<input type="email" class="form-control" name="driver[email]" id="email-input"
					value="{{ $driver->email }}">
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
				<input type="text" class="form-control" name="driver[name]" id="name-input" value="{{ $driver->name }}">
			</div>
		</div>
		<div class="form-group">
			<label for="phone-input" class="control-label">Phone</label>
			<div >
				<input type="tel" class="form-control" name="driver[phone]" id="phone-input"
					value="{{ $driver->phone }}">
			</div>
		</div>
		<div class="form-group">
			<label for="city-select" class="control-label">City</label>
			<div >
				<select class="form-control" name="driver[city]" id="city-select">
					@foreach($cities as $city)
						<option value="{{$city}}" {{$driver->city == $city ? "selected":""}}>{{$city}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="stores-select" class="control-label">Stores</label>
			<div >
				<select multiple size="{{$stores->count()<21? $stores->count():20}}" class="form-control"
					name="driver[stores][]" id="stores-select">
					@foreach($stores as $store)
						<option
							value="{{$store->id}}" {{$driver->stores->contains($store) ? "selected":""}}>{{$store->name}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">Have you ever been convicted of a crime?</label>
			<div >
				<label class="radio-inline">
					<input type="radio" disabled name="driver[crime]" value=0
						{{$driver->crime ? "" : "checked"}}>
					No
				</label>
				<label class="radio-inline">
					<input type="radio" disabled name="driver[crime]" value=1
						{{$driver->crime ? "checked" : ""}}>
					Yes
				</label>
			</div>
		</div>
		<div id="crime-details" class="{{$driver->crime ? "" : "hidden "}}form-group">
			<label for="crime-details-input" class="control-label">Explain</label>
			<div >
				<textarea disabled rows=3 class="application-details form-control" name="driver[crime_details]"
					id="crime-details-input">{{ $driver->crime_details }}</textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">Have you had any accidents in the past year?</label>
			<div >
				<label class="radio-inline">
					<input type="radio" disabled name="driver[accidents]" value=0
						{{$driver->accidents ? "" : "checked"}}>
					No
				</label>
				<label class="radio-inline">
					<input type="radio" disabled name="driver[accidents]"
						value=1 {{$driver->accidents ? "checked" : ""}}>
					Yes
				</label>
			</div>
		</div>
		<div id="accidents-details" class="{{$driver->accidents ? "" : "hidden "}}form-group">
			<label for="accidents-details-input" class="control-label">How many?</label>
			<div >
				<input disabled type="number" min="1" name="driver[accidents_details]" id="accidents-details-input"
					class="application-details form-control" value="{{ $driver->accidents_details or "1" }}">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">Have you had any traffic violations in the past year?</label>
			<div >
				<label class="radio-inline">
					<input type="radio" disabled name="driver[violations]"
						value=0 {{$driver->violations ? "" : "checked"}}>
					No
				</label>
				<label class="radio-inline">
					<input type="radio" disabled name="driver[violations]"
						value=1 {{$driver->violations ? "checked" : ""}}>
					Yes
				</label>
			</div>
		</div>
		<div id="violations-details" class="{{$driver->violations ? "" : "hidden "}}form-group">
			<label for="violations-details-input" class="control-label">How many?</label>
			<div >
				<input disabled type="number" min="1" name="driver[violations_details]" id="violations-details-input"
					class="application-details form-control" value="{{ $driver->violations_details or "1" }}">
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