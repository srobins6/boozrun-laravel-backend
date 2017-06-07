@extends("layouts.driver")
@section("title")
	Order History
@endsection
@section("content")
	<div class="vertical-center push-apart manage-heading panel-heading">
		<div class="h2 margin-0 vertical-center">
			Order History
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
				<a class="h4" data-toggle="collapse" href="#delivered{{$store->id}}">Delivered</a>
			</div>
			<table id="delivered{{$store->id}}" class="order-table table collapse in">
				<thead class="{{$store->delivered->where("driver_id", $driver->id)->count()==0 ? "hidden": ""}}">
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
					@each("shared.elements.driverorderrow",$store->delivered->where("driver_id", $driver->id),"order")
				</tbody>
			</table>
		</div>
		<div class="order-panel panel panel-default">
			<div class="panel-heading">
				<a class="h4" data-toggle="collapse" href="#cancelled{{$store->id}}">Cancelled</a>
			</div>
			<table id="cancelled{{$store->id}}" class="order-table table collapse in">
				<thead class="{{$store->cancelled->where("driver_id", $driver->id)->count()==0 ? "hidden": ""}}">
					<tr class="small hidden-xs">
						<th>Date</th>
						<th>Name</th>
						<th>Address</th>
						<th>Phone</th>
						<th>Total</th>
						<th>Tip</th>
						<th>Dates</th>
						<th>Items</th>
						<th>Delivery Notes</th>
					</tr>
				</thead>
				<tbody>
					@each("shared.elements.driverorderrow",$store->cancelled->where("driver_id", $driver->id),"order")
				</tbody>
			</table>
		</div>
	@endforeach
@endsection