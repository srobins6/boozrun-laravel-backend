<tr class="promo" data-model="promo" data-modelid="{{$promo->id}}" id="promo{{$promo->id}}"
	data-active="{{$promo->expiration_date > \Carbon\Carbon::now()}}">
	<td>{{$promo->code}}</td>
	<td>{{$promo->reusable ? "Reusable " : ""}}{{ucwords($promo->type)}}</td>
	<td>{{$promo->amount}}</td>
	<td>{{$promo->expiration_date->toFormattedDateString()}}</td>
	<td>
		<ul class="list-group">
			@foreach($promo->stores as $store)
				<li class="list-group-item">{{$store->name}}</li>
			@endforeach
		</ul>
	</td>
	<td class="text-right">
		<button class="btn btn-danger model-delete-button" type="button">
			Delete
		</button>
	</td>
</tr>
