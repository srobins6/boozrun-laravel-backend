@section("buttons")
	<input type="text" id="driver-city-filter" class="form-control" list="city-list" placeholder="City filter">
	<input type="text" id="driver-name-filter" class="form-control" list="driver-list" placeholder="Driver name filter">
	<input class="btn btn-primary" type="submit" value="Update" form="drivers-form">
@endsection
<form class="panel-body" id="drivers-form" method="post" action="{{$driversUrl. "/update"}}">
	{!! csrf_field() !!}
	<select title="Drivers" id="drivers-select" size="{{$drivers->count()>20? $drivers->count():20}}" multiple
		class="form-control" name="drivers[]">
		@foreach($drivers as $driver)
			<option class="driver" data-name="{{strtolower($driver->name)}}" data-model="driver"
				data-modelid="{{$driver->id}}" id="driver{{$driver->id}}" data-city="{{strtolower($driver->city)}}"
				value="{{$driver->id}}" {{$driver->stores->contains($store) ? "selected" : ""}}>
				{{$driver->name}}
			</option>
		@endforeach
	</select>
</form>
<datalist id="driver-list">
	@foreach ($drivers as $driver)
		<option data-modelid="{{$driver->id}}" value="{{$driver->name}}">
	@endforeach
</datalist>
<datalist id="city-list">
	@foreach($cities as $city)
		<option class="city-option" value="{{$city}}">{{$city}}</option>
	@endforeach
</datalist>{{--todo: add orders/tips views and move selection to modal--}}