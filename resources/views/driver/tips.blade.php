@extends("layouts.driver")
@section("title")
	Tips
@endsection
@section("content")
	<div class="vertical-center push-apart manage-heading panel-heading">
		<div class="h2 margin-0 vertical-center">
			Tips
		</div>
		<div class="form-inline vertical-center push-apart hidden-xs">
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
		</div>
	</div>
	@foreach($driver->stores as $store)
		<div class="panel panel-default margin-0">
			<div class="panel-heading">{{$store->name}}</div>
			@include("shared.elements.drivertips")
		</div>
	@endforeach
@endsection


