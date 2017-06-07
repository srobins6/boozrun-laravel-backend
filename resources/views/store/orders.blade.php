@extends("layouts.store.manage")
@section("title")
	{{$store->name}} Orders
@endsection
@section("heading")
	{{$store->name}} Orders
@endsection
@section("managecontent")
	@include("shared.storeorders")
	<script>
		$(window).load(function () {
			setInterval(function () {
				storeOrdersUpdate({{$store->id}});
			}, 5000);
		});
	</script>
@endsection