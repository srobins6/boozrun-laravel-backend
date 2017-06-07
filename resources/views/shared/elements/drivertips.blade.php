<div class="panel-body margin-0">
	@foreach($driver->tips[$store->id] as $date => $tip)
		<div data-date="{{strtotime($date)}}"
			class="tip panel panel-default panel-heading col-xs-12 col-sm-6 col-md-4 col-lg-3 margin-0{{strtotime($date)<strtotime("-1 week")? " hidden":""}}">
			{{$date}}:
			<span class="pull-right">${{$tip}}</span>
		</div>
	@endforeach
</div>