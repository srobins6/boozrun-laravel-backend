@section("buttons")
	<div class="btn-group">
		<button type="button" class="btn btn-success hours-add-button" value="{{$store->id}}">Add
		</button>
		<input type="submit" id="hours-submit" class="btn btn-primary" form="hours-form" value="Update">
	</div>
@endsection
<form class="ajax-form hidden" method="post" action="{{$hoursUrl. "/update"}}" id="hours-form">
	{!! csrf_field() !!}
</form>
<div class="table-responsive"><table id="hours-table" class="panel-body store-table table">
	<thead>
		<tr>
			<th>Name</th>
			<th >Active</th>
			<th ></th>
			<th >Monday</th>
			<th >Tuesday</th>
			<th >Wednesday</th>
			<th >Thursday</th>
			<th >Friday</th>
			<th >Saturday</th>
			<th >Sunday</th>
		</tr>
	</thead>
	<tbody>
		@foreach($store->hours as $hours)
			@include("shared.elements.hoursrow",["store"=>$store])
		@endforeach
	</tbody>
</table></div>