@extends("layouts.admin.manage")
@section("title")
	Customers
@endsection
@section("heading")
	Customers
@endsection
@section("buttons")
	<input type="text" id="customer-email-filter" class="form-control" list="email-list" placeholder="Email filter">
	<input type="text" id="customer-name-filter" class="form-control" list="name-list" placeholder="Name filter">
@endsection
@section("managecontent")
	<div class="table-responsive"><table class="panel-body table" id="customer-table">
		<tr>
			<th>Name</th>
			<th>Email</th>

		</tr>
		@foreach ($customers as $customer)
			<tr class="customer" data-model="customer" id="customer{{$customer->id}}"
				data-name="{{strtolower($customer->name)}}" data-email="{{strtolower($customer->email)}}"
				data-modelid="{{$customer->id}}">
				<td>{{$customer->name}}</td>
				<td>{{$customer->email}}</td>
				<td align="right">
					<div class="btn-group">
						<a href="{{url("/admin/customers/$customer->id/info")}}" class="btn btn-primary">Info</a>
						<a href="{{url("/admin/customers/$customer->id/orders")}}" class="btn btn-primary">Orders</a>
						<button type="button" class="btn btn-danger model-delete-button">Delete</button>
					</div>
				</td>
			</tr>
		@endforeach
	</table></div>
	<datalist id="email-list">
		@foreach ($customers as $customer)
			<option data-modelid="{{$customer->id}}" value="{{$customer->email}}">
		@endforeach
	</datalist>
	<datalist id="name-list">
		@foreach ($customers as $customer)
			<option data-modelid="{{$customer->id}}" value="{{$customer->name}}">
		@endforeach
	</datalist>
@endsection