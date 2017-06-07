@extends("layouts.customer")
@section("title")
	Find Store
@endsection
@section("content")
	<h1 class="text-center margin-bottom-20">Web App version coming soon!</h1>
	
	{{--<div class="panel-heading">Find a local store</div>--}}
	{{--<form class="panel-body " role="form" method="POST" action="{{ url("/findstore") }}">--}}
		{{--{!! csrf_field() !!}--}}
		{{--<div class="form-group{{ $errors->has("address") ? " has-error" : "" }}">--}}
			{{--<label class="control-label" for="address">Address:</label>--}}
			{{--<input class="form-control" name="address" id="address-input" type="text" placeholder="Enter address"--}}
				{{--value="{{$address or ""}}" autocomplete="off">--}}
			{{--@if ($errors->has("address"))--}}
				{{--<span class="help-block">--}}
					{{--<strong>{{ $errors->first("address") }}</strong>--}}
				{{--</span>--}}
			{{--@endif--}}
		{{--</div>--}}
		{{--<input class="form-control btn btn-primary" type="submit" value="Find Store">--}}
	{{--</form>--}}
	{{--<p class="panel-body">--}}
		{{--By entering the store, you accept our--}}
		{{--<a href="{{asset("branding/termsandconditions.pdf")}}">terms & conditions</a>--}}
		{{--and are 21+ years of age--}}
	{{--</p>--}}
@endsection