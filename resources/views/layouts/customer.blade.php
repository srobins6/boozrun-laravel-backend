@extends("layouts.master")
@section("layout")
	@if(isset($productNames))
		<datalist id="product-names">
			@foreach($productNames as $productName)
				<option value="{{$productName}}">{{$productName}}</option>
			@endforeach
		</datalist>
	@endif
	<div id="content" class="panel panel-default">@yield("content")</div>
@endsection
@section("home", url("/") )
@section("navbar-main")
	@if(isset($currentStore))
		@foreach(\App\Category::all()->where("parent_id", 0)->filter(function($category) use ($currentStore){
				return !$currentStore->products->filter(function ($item) use ($category) {
					return $item->categories->contains($category);
			})->isEmpty();
		}) as $category)
			<li class='dropdown'>
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
					{{$category->name}}
					<span class="caret"></span>
				</a>
				<ul class='dropdown-menu'>
					<li>
						<a href='{{url("?category=$category->id")}}'>All</a>
					</li>
					@foreach($category->children->filter(function($subcategory) use ($currentStore){
						return !$currentStore->products->filter(function ($item) use ($subcategory) {
							return $item->categories->contains($subcategory);
								})->isEmpty();
						}) as $subcategory)
						<li>
							<a href='{{url("?category=$subcategory->id")}}'>{{$subcategory->name}}</a>
						</li>
					@endforeach
				</ul>
			</li>
		@endforeach
	@endif
@endsection
@if(isset($currentStore))
@section("navbar-search")
	<form class="input-group" action="{{url("/")}}">
		<input type="text" list="product-names" class="form-control" placeholder="Search" name="search"
			autocomplete="off">
		<div class="input-group-btn">
			<button class="btn btn-default" type="submit">
				&zwnj;
				<i class="glyphicon glyphicon-search"></i>
			</button>
		</div>
	</form>
@endsection
@endif
@section("navbar-account")
	{{--<li>--}}
	{{--<a href="{{ url("/findstore") }}">Find Store</a>--}}
	{{--</li>--}}
	@if(isset($currentStore))
		<li>
		<a href="{{ url("/cart") }}">Cart</a>
		</li>
	@endif
	@if (Auth::guest())
		<li>
			<a href="{{ url("/login") }}">Login</a>
		</li>
		<li>
			<a href="{{ url("/signup") }}">Signup</a>
		</li>
	@else
		<li>
			<a href="{{ url("/logout") }}">Logout</a>
		</li>
		<li>
			<a href="{{ url("/account") }}">Account</a>
		</li>
	@endif
@endsection
@section("navbar")
	@include("shared.navbar")
@endsection
