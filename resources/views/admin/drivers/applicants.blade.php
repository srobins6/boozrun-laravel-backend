@extends("layouts.admin.manage")
@section("title")
	Applicants
@endsection
@section("heading")
	Driver Applicants
@endsection
@section("buttons")
	<input type="text" id="driver-city-filter" class="form-control" list="city-list" placeholder="City filter">
	<input type="text" id="driver-name-filter" class="form-control" list="driver-list" placeholder="Driver name filter">
@endsection
@section("managecontent")
	<div class="table-responsive"><table class="panel-body table" id="driver-table">
		<tr>
			<th>Name</th>
			<th>Email</th>
			<th>Phone</th>
			<th>City</th>

		</tr>
		@foreach ($drivers as $driver)
			<tr data-name="{{strtolower($driver->name)}}" data-model="driver" data-modelid="{{$driver->id}}"
				class="driver" id="driver{{$driver->id}}" data-city="{{strtolower($driver->city)}}"
				data-active="{{$driver->active}}">
				<td>{{$driver->name}}</td>
				<td>{{$driver->email}}</td>
				<td>{{$driver->phone}}</td>
				<td>
					{{$driver->city}}
				</td>
				<td align="right">
					<div class="btn-group">
						<a href="{{url("/admin/drivers/$driver->id/info")}}" class="btn btn-primary">Info</a>
						<button type="button" class="btn btn-success driver-applicant-confirm-button">
							Confirm
						</button>
						<button type="button" class="btn btn-danger model-delete-button">
							Reject
						</button>
					</div>
				</td>
			</tr>
		@endforeach
	</table></div>
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