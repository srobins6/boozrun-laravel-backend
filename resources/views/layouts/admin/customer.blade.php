@extends("layouts.admin.manage")
@section("menu")
	<div class="btn-group margin-left-10">
		@if($control)
			<div class="dropdown btn-group">
				<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
					Menu <span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="{{url("/admin/customers/$customer->id/info")}}">Info</a>
					</li>
					<li>
						<a href="{{url("/admin/customers/$customer->id/orders")}}">Orders</a>
					</li>
					<li class="divider"></li>
					<li>
						<a data-modelid="{{$customer->id}}" data-model="customer" href="#"
						   class="model-delete-button-redirect">Delete</a>
					</li>
				</ul>
			</div>
		@endif
	</div>
@endsection