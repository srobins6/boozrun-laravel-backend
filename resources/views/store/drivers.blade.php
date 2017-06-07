@extends("layouts.store.manage")
@section("title")
	{{$store->name}} Drivers
@endsection
@section("heading")
	{{$store->name}} Drivers
@endsection
@section("buttons")
	<span>Date:</span>
	<select title="Date operator" id="tip-date-operator" class="form-control">
		<option value=""></option>
		<option value="on">On</option>
		<option value="before">Before</option>
		<option value="after">After</option>
		<option value="between" selected>Between</option>
	</select>
	<input title="Date" class="form-control tip-date-filter" type="date" id="tip-date-filter"
		value="{{date("Y-m-d",strtotime("-1 week"))}}">
	<span class="tip-date-filter-between"> and</span>
	<input title="Date" class="form-control tip-date-filter-between tip-date-filter" type="date"
		id="tip-date-filter-end" value="{{date("Y-m-d")}}">
@endsection
@section("managecontent")
	@foreach($store->drivers as $driver)
		<div class="panel panel-default margin-0">
			<div class="panel-heading">{{$driver->name}}
				<span
					class="pull-right">{{preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $driver->phone)}}</span>
			</div>
			@include("shared.elements.drivertips")
		</div>
	@endforeach

@endsection