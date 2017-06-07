@extends("layouts.store.manage")
@section("title")
	{{$store->name}} Hours
@endsection
@section("heading")
	{{$store->name}} Hours
@endsection
@section("managecontent")
	@include("shared.storehours")
@endsection