@extends("layouts.customer")
@section("title")
	{{$title}}
@endsection
@section("content")
	<div class="vertical-center panel-heading">
		<div class="h3 margin-0 vertical-center">
			{{$title}}
		</div>
	</div>
	<div>
		@each("shared.elements.productpanelform", $products, "productList")
	</div>
@endsection