<tr class="hours hours{{$hours->id}}" data-model="hours" data-modelid="{{$hours->id}}">
	<td rowspan="3">
		@if($hours->name != "Default" and $hours->name != "Break")
			<input class="form-control" value="{{$hours->name}}" name="hours[{{$hours->id}}][name]" type="text"
				title="">
			<button type="button" class="btn btn-danger form-control store-model-delete-button" value="{{$store->id}}">
				Delete
			</button>
		@else{{$hours->name}}
		@endif
	</td>
	<td rowspan="3" >
		<input form="hours-form" value="{{$hours->id}}" data-model="store" data-modelid="{{$store->id}}"
			class="store-active-hours" type="radio" name="activeHours" {{$hours->active ? "checked" : null}} title="">
	</td>
	<td class="text-right hours-cell">Start</td>
	@foreach ($hours->days as $dayName => $day)
		<td class="{{-- text-center --}} hours-cell">
			<input form="hours-form" type="time" class="form-control col-md-12"
				name="hours[{{$hours->id}}][days][{{$dayName}}][start]" value="{{$day["start"]}}" required title="">
		</td>
	@endforeach
</tr>
<tr class="hours hours{{$hours->id}}" data-model="hours" data-modelid="{{$hours->id}}">
	<td class="text-right hours-cell">End</td>
	@foreach ($hours->days as $dayName => $day)
		<td class="{{-- text-center --}} hours-cell">
			<input form="hours-form" type="time" class="form-control col-md-12"
				name="hours[{{$hours->id}}][days][{{$dayName}}][end]" value="{{$day["end"]}}" required title="">
		</td>
	@endforeach
</tr>
<tr class="hours hours{{$hours->id}}" data-model="hours" data-modelid="{{$hours->id}}">
	<td class="text-right hours-cell">Open</td>
	@foreach($hours->days as $dayName => $day)
		<td class="{{-- text-center --}} hours-cell">
			<input form="hours-form" {{$day["open"] ? "checked" : ""}} type="checkbox" class="day-open-check"
				name="hours[{{$hours->id}}][days][{{$dayName}}][open]" title="">
		</td>
	@endforeach
</tr>