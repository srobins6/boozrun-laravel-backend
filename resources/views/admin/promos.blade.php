@extends("layouts.admin.manage")
@section("title")
	Promos
@endsection
@section("heading")
	Promos
@endsection
@section("buttons")
	<label class="checkbox-inline margin-left-10">
		<input type="checkbox" class="promo-active-filter" value="1" checked>
		Active
	</label>
	<label class="checkbox-inline margin-right-10">
		<input type="checkbox" class="promo-active-filter" value="0" checked>
		Expired
	</label>
	<div class="btn-group">
		<button type="button" id="promo-add-button" class="btn btn-success">Add Promo
		</button>
	</div>
@endsection
@section("managecontent")

	<div class="table-responsive">
		<table id="promo-table" class="panel-body table store-table">
			<thead>
				<tr>
					<th>Code</th>
					<th>Type</th>
					<th>Amount</th>
					<th>Expiration Date</th>
					<th>Stores</th>
				</tr>
			</thead>
			<tbody>
				@each("shared.elements.promorow",$promos,"promo")
			</tbody>
		</table>
	</div>
@endsection
@section("modaltitle")
	New Promo
@endsection
@section("modalcontent")
	<form class="modal-body panel-body" method="post" id="add-promo-form" action="{{url("admin/promos/add")}}">
		<div class="form-group">
			<label for="code-input">Code</label>
			<input required type="text" class="form-control" name="code" id="code-input">
		</div>
		<div class="form-group">
			<label class="checkbox-inline">
				<input type="checkbox" name="reusable" value="1">
				Reusable
			</label>
		</div>
		<div class="form-group">
			<label for="type-select">Type</label>
			<select required class="form-control" name="type" id="type-select">
				<option value="fixed">Fixed</option>
				<option value="percent">Percent</option>
			</select>
		</div>
		<div class="form-group">
			<label for="amount-input">Amount</label>
			<input required type="number" class="form-control" name="amount" id="amount-input">
		</div>
		<div class="form-group">
			<label for="expdate-input">Expiration Date</label>
			<input required type="date" class="form-control" name="expiration_date" id="expdate-input"
				value="{{\Carbon\Carbon::now()->addMonth(1)->format("Y-m-d")}}">
		</div>
		<div class="form-group">
			<label for="stores-select">Stores</label>
			<select id="stores-select" size="{{$stores->count()<20? $stores->count():20}}" multiple class="form-control"
				name="stores[]">
				@foreach($stores as $store)
					<option selected data-name="{{strtolower($store->name)}}" value="{{$store->id}}"
						id="store{{$store->id}}">
						{{$store->name}}
					</option>
				@endforeach
			</select>
		</div>
		{!! csrf_field() !!}
	</form>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<input type="submit" class="btn btn-success" value="Save promo" form="add-promo-form">
	</div>
@endsection
@section("modal")
	@include("shared.elements.modaldiv")
@endsection