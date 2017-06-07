@section("buttons")
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
@endsection
<div class="order-panel panel panel-default">
	<div class="panel-heading">
		<a class="h4" data-toggle="collapse" href="#submitted">Submitted</a>
	</div>
	<div class="table-responsive">
		<table id="submitted" class="order-table table collapse{{$store->submitted->count()>0 ? " in": ""}}">
			<thead class="{{$store->submitted->count()==0 ? "hidden": ""}}">
				<tr>
					<th>Date</th>
					<th>Name</th>
					<th>Address</th>
					<th>Phone</th>
					<th>Total</th>
					<th>Tip</th>
					<th>Times</th>
					<th>Items</th>
					<th>Delivery Notes</th>
				</tr>
			</thead>
			<tbody>
				@each("shared.elements.storeorderrow",$store->submitted,"order")
			</tbody>
		</table>
	</div>
</div>
<div class="order-panel panel panel-default">
	<div class="panel-heading">
		<a class="h4" data-toggle="collapse" href="#packed">Packed</a>
	</div>
	<div class="table-responsive">
		<table id="packed" class="order-table table collapse{{$store->packed->count()>0 ? " in": ""}}">
			<thead class="{{$store->packed->count()==0 ? "hidden": ""}}">
				<tr class="small">
					<th>Date</th>
					<th>Name</th>
					<th>Address</th>
					<th>Phone</th>
					<th>Total</th>
					<th>Tip</th>
					<th>Times</th>
					<th>Items</th>
					<th>Delivery Notes</th>
				</tr>
			</thead>
			<tbody>
				@each("shared.elements.storeorderrow",$store->packed,"order")
			</tbody>
		</table>
	</div>
</div>
<div class="order-panel panel panel-default">
	<div class="panel-heading">
		<a class="h4" data-toggle="collapse" href="#delivering">Out for Delivery</a>
	</div>
	<div class="table-responsive">
		<table id="delivering" class="order-table table collapse{{$store->delivering->count()>0 ? " in": ""}}">
			<thead class="{{$store->delivering->count()==0 ? "hidden": ""}}">
				<tr class="small">
					<th>Date</th>
					<th>Name</th>
					<th>Address</th>
					<th>Phone</th>
					<th>Total</th>
					<th>Tip</th>
					<th>Times</th>
					<th>Driver</th>
					<th>Items</th>
					<th>Delivery Notes</th>
				</tr>
			</thead>
			<tbody>
				@each("shared.elements.storeorderrow",$store->delivering,"order")
			</tbody>
		</table>
	</div>
</div>
<div class="order-panel panel panel-default">
	<div class="panel-heading">
		<a class="h4" data-toggle="collapse" href="#delivered">Delivered</a>
	</div>
	<div class="table-responsive">
		<table id="delivered" class="order-table table collapse">
			<thead class="{{$store->delivered->count()==0 ? "hidden": ""}}">
				<tr class="small">
					<th>Date</th>
					<th>Name</th>
					<th>Address</th>
					<th>Phone</th>
					<th>Total</th>
					<th>Tip</th>
					<th>Times</th>
					<th>Driver</th>
					<th>Items</th>
					<th>Delivery Notes</th>
				</tr>
			</thead>
			<tbody>
				@each("shared.elements.storeorderrow",$store->delivered,"order")
			</tbody>
		</table>
	</div>
</div>
<div class="order-panel panel panel-default">
	<div class="panel-heading">
		<a class="h4" data-toggle="collapse" href="#cancelled">Cancelled</a>
	</div>
	<div class="table-responsive">
		<table id="cancelled" class="order-table table collapse">
			<thead class="{{$store->cancelled->count()==0 ? "hidden": ""}}">
				<tr class="small">
					<th>Date</th>
					<th>Name</th>
					<th>Address</th>
					<th>Phone</th>
					<th>Total</th>
					<th>Tip</th>
					<th>Dates</th>
					<th>Driver</th>
					<th>Items</th>
					<th>Delivery Notes</th>
				</tr>
			</thead>
			<tbody>
				@each("shared.elements.storeorderrow",$store->cancelled,"order")
			</tbody>
		</table>
	</div>
</div>