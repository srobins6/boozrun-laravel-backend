@extends("layouts.driver")
@section("title")
	Orders
@endsection
@section("content")
	<div class="vertical-center push-apart manage-heading panel-heading">
		<div class="h2 margin-0 vertical-center">
			Orders
		</div>
		<div class="form-inline vertical-center push-apart hidden-xs">
			<span>Date:</span>
			<select title="Date operator" id="order-date-operator" class="form-control">
				<option value=""></option>
				<option value="on">On</option>
				<option value="before">Before</option>
				<option value="after">After</option>
				<option value="between">Between</option>
			</select>
			<input title="Date" class="form-control order-date-filter" type="date" id="order-date-filter"
				value="{{date("Y-m-d")}}">
			<span class="hidden order-date-filter-between"> and</span>
			<input title="Date" class="hidden form-control order-date-filter-between order-date-filter" type="date"
				id="order-date-filter-end" value="{{date("Y-m-d")}}">
		</div>
	</div>
	@foreach($driver->stores as $store)
		<div class="panel-heading">
			<span class="h3">{{$store->name}}</span>
		</div>
		<div class="order-panel panel panel-default">
			<div class="panel-heading">
				<a class="h4" data-toggle="collapse" href="#packed{{$store->id}}">Packed</a>
			</div>
			<table id="packed{{$store->id}}"
				class="order-table table collapse{{$store->packed->count()>0 ? " in": ""}}">
				<thead class="{{$store->packed->count()==0 ? "hidden": ""}}">
					<tr class="small hidden-xs">
						<th>Date</th>
						<th>Name</th>
						<th>Address</th>
						<th>Phone</th>
						<th>Total</th>
						<th>Tip</th>
						<th>Times</th>
						<th>Items</th>
						<th>Notes</th>
					</tr>
				</thead>
				<tbody>
					@each("shared.elements.driverorderrow",$store->packed,"order")
				</tbody>
			</table>
		</div>
		<div class="order-panel panel panel-default">
			<div class="panel-heading">
				<a class="h4" data-toggle="collapse" href="#delivering{{$store->id}}">Out for Delivery</a>
			</div>
			<table id="delivering{{$store->id}}"
				class="order-table table collapse{{$store->delivering->count()>0 ? " in": ""}}">
				<thead class="{{$store->delivering->where("driver_id", $driver->id)->count()==0 ? "hidden": ""}}">
					<tr class="small hidden-xs">
						<th>Date</th>
						<th>Name</th>
						<th>Address</th>
						<th>Phone</th>
						<th>Total</th>
						<th>Tip</th>
						<th>Times</th>
						<th>Items</th>
						<th>Notes</th>
					</tr>
				</thead>
				<tbody>
					@each("shared.elements.driverorderrow",$store->delivering->where("driver_id", $driver->id),"order")
				</tbody>
			</table>
		</div>
	@endforeach
	<script>
		setInterval(function () {
			driverOrdersUpdate(window.driverId);
		}, 5000);
	</script>
@endsection