@extends("layouts.admin.store")
@section("title")
	{{$store->name}} Drivers
@endsection
@section("heading")
	{{$store->name}} Drivers
@endsection
@section("managecontent")
	@include("shared.storedrivers")
@endsection