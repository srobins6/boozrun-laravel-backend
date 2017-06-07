@extends("layouts.admin.manage")
@section("title")
	Stores
@endsection
@section("heading")
	Stores
@endsection
@section("buttons")
	<label class="checkbox-inline">
		<input type="checkbox" value="1" class="store-active-filter" checked>
		Active
	</label>
	<label class="checkbox-inline margin-right-10">
		<input type="checkbox" value="0" class="store-active-filter" checked>
		Inactive
	</label>
	<input type="text" id="store-city-filter" class="form-control" list="city-list" placeholder="City filter">
	<input type="text" id="store-name-filter" class="form-control" list="store-list" placeholder="Store name filter">
	@if($control)
		<a href="{{url("/admin/stores/add")}}" class="btn btn-success">Add</a>
	@endif
@endsection
@section("managecontent")
	<div class="table-responsive">
		<table class="panel-body table" id="store-table">
			<tr>
				<th>Store Name</th>
				<th class="text-center">Active</th>
				<th>City</th>
			</tr>
			@foreach ($stores as $store)
				<tr data-name="{{strtolower($store->name)}}" class="store" id="store{{$store->id}}"
					data-city="{{strtolower($store->city)}}" data-model="store" data-modelid="{{$store->id}}"
					data-active="{{$store->active}}">
					<td>{{$store->name}}</td>
					<td class="text-center">
						<input title="Store active check" type="checkbox"
							class="model-active-check" {{ $store->active ? "checked" : ""}}>
					</td>
					<td>
						{{$store->city}}
					</td>
					<td align="right">
						<div class="input-group input-group-inline">
							@if($control)
								<div class="input-group-btn">
									<a href="{{url("/admin/stores/$store->id/info")}}" class="btn btn-primary">Info</a>
									<a href="{{url("/admin/stores/$store->id/orders")}}" class="btn btn-primary">
										Orders
									</a>
									<a href="{{url("/admin/stores/$store->id/drivers")}}" class="btn btn-primary">
										Drivers
									</a>
									<a href="{{url("/admin/stores/$store->id/products")}}" class="btn btn-primary">
										Products
									</a>
									<a href="{{url("/admin/stores/$store->id/hours")}}" class="btn btn-primary">Hours
									</a>
								</div>
								<select title="Store active hours" class="form-control store-active-hours">
									@foreach($store->hours as $hours)
										<option value="{{$hours->id}}" {{ $hours->active ? "selected" : ""}}>
											{{ucwords($hours->name) . " hours"}}
										</option>
									@endforeach
								</select>
								<div class="input-group-btn">
									<button {{$store->id==1? "disabled" : ""}}  type="button" class="btn btn-danger
									model-delete-button">Delete
									</button>
								</div>
							@else
								<a href="{{url("/admin/stores/$store->id/products")}}" class="btn btn-primary">
									Products
								</a>
							@endif
						</div>
					</td>
				</tr>
			@endforeach
		</table>
	</div>
	<datalist id="store-list">
		@foreach ($stores as $store)
			<option id="store-option{{$store->id}}" value="{{$store->name}}">
		@endforeach
	</datalist>
	<datalist id="city-list">
		@foreach($cities as $city)
			<option class="city-option" value="{{$city}}">{{$city}}</option>
		@endforeach
	</datalist>
@endsection
