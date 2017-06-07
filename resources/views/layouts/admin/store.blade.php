@extends("layouts.admin.manage")
@section("menu")
	<div class="btn-group margin-left-10">
		@if($control)
			<div class="dropdown btn-group">
				<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
					Menu
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="{{url("/admin/stores/$store->id/info")}}">Info</a>
					</li>
					<li>
						<a href="{{url("/admin/stores/$store->id/orders")}}">Orders</a>
					</li>
					<li>
						<a href="{{url("/admin/stores/$store->id/drivers")}}">Drivers</a>
					</li>
					<li>
						<a href="{{url("/admin/stores/$store->id/products")}}">Products</a>
					</li>
					<li>
						<a href="{{url("/admin/stores/$store->id/hours")}}">Hours</a>
					</li>
					<li class="divider"></li>
					<li class="{{$store->demo? "disabled" : ""}}">
						<a {{$store->id==1? "disabled" : ""}}  type="button" data-model="store"
							data-modelid="{{$store->id}}" class="model-delete-button">Delete
						</a>
					</li>
				</ul>
			</div>
		@endif
	</div>
@endsection