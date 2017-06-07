@extends("layouts.store.manage")
@section("title")
	{{$store->name}} Products
@endsection
@section("heading")
	{{$store->name}} Products
@endsection
@section("managecontent")
	@include("shared.storeproducts")
@endsection