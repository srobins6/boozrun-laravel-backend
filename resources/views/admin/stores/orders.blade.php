@extends("layouts.admin.store")
@section("title")
	{{$store->name}} Orders
@endsection
@section("heading")
	{{$store->name}} Orders
@endsection
@section("managecontent")
	@include("shared.storeorders")
@endsection