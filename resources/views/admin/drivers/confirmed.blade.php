@extends("layouts.admin.manage")
@section("title")
	Drivers
@endsection
@section("heading")
	Drivers
@endsection
@section("buttons")
	<label class="checkbox-inline">
		<input type="checkbox" value="1" class="driver-active-filter" checked>
		Active
	</label>
	<label class="checkbox-inline margin-right-10">
		<input type="checkbox" value="0" class="turtle driver-active-filter" checked>
		Inactive
	</label>
	<input type="text" id="driver-city-filter" class="form-control" list="city-list" placeholder="City filter">
	<input type="text" id="driver-name-filter" class="form-control" list="driver-list" placeholder="Driver name filter">
@endsection
@section("managecontent")
	<div class="table-responsive">
		<table class="panel-body table" id="driver-table">
			<tr>
				<th>Name</th>
				<th class="text-center">Active</th>
				<th>Email</th>
				<th>Phone</th>
				<th>City</th>
				<th>Stores</th>
			</tr>
			@foreach ($drivers as $driver)
				<tr data-name="{{strtolower($driver->name)}}" data-model="driver" data-modelid="{{$driver->id}}"
					class="driver" id="driver{{$driver->id}}" data-city="{{strtolower($driver->city)}}"
					data-active="{{$driver->active}}" data-stores="{{$driver->stores->pluck("id")}}">
					<td>{{$driver->name}}</td>
					<td class="text-center">
						<input title="active" type="checkbox" class="model-active-check"
							{{$driver->active ? "checked" : ""}}>
					</td>
					<td>{{$driver->email}}</td>
					<td>{{$driver->phone}}</td>
					<td>
						{{$driver->city}}
					</td>
					<td>
						<button type="button" class="btn btn-primary driver-stores-button">Stores</button>
					</td>
					<td align="right">
						<div class="btn-group">
							<a href="{{url("/admin/drivers/$driver->id/info")}}" class="btn btn-primary">Info</a>
							<a href="{{url("/admin/drivers/$driver->id/tips")}}" class="btn btn-primary">Tips</a>
							<button type="button" class="btn btn-danger model-delete-button">Delete</button>
						</div>
					</td>
				</tr>
			@endforeach
		</table>
	</div>
	<datalist id="driver-list">
		@foreach ($drivers as $driver)
			<option data-modelid="{{$driver->id}}" value="{{$driver->name}}">
		@endforeach
	</datalist>
	<datalist id="city-list">
		@foreach($cities as $city)
			<option class="city-option" value="{{$city}}">{{$city}}</option>
		@endforeach
	</datalist>
@endsection
@section("modaltitle")
	Select Stores
@endsection
@section("modalcontent")
	<form class="modal-body panel-body" method="post" id="stores-form">
		{!! csrf_field() !!}
		<select title="stores" id="stores-select" size="{{$stores->count()<21? $stores->count():20}}" multiple
			class="form-control" name="stores[]">
			@foreach($stores as $store)
				<option data-name="{{strtolower($store->name)}}" value="{{$store->id}}" id="store{{$store->id}}">
					{{$store->name}}
				</option>
			@endforeach
		</select>
	</form>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<input type="submit" class="btn btn-success" value="Save stores" form="stores-form">
	</div>
@endsection
@section("modal")
	@include("shared.elements.modaldiv")
@endsection