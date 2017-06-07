<tr class="order small" data-model="order" data-modelid="{{$order->id}}" id="order{{$order->id}}"
	data-name="{{strtolower($order->name)}}" data-date="{{strtotime($order->submitted_at->toDateString())}}">
	<td class="hidden-xs">{{$order->submitted_at->toFormattedDateString()}}</td>
	<td class="hidden-xs">{{$order->name}}</td>
	<td class="hidden-xs">
		<a href="{{"http://maps.google.com/maps?" . http_build_query(["q"=>$order->address])}}">{{$order->address}}</a>
	</td>
	<td class="hidden-xs">
		<a href="tel:{{$order->phone}}">{{$order->phone}}</a>
	</td>
	<td class="hidden-xs">{{$order->total}}</td>
	<td class="hidden-xs">{{$order->tip}}</td>
	<td class="hidden-xs">
		<ul class="list-group">
			<li class="list-group-item submitted-li">Submitted:
				<span class="submitted-at">{{$order->submitted_at->format("g:i a")}}</span>
			</li>
			<li class="list-group-item packed-li{{$order->packed_at ? "" : " hidden"}}">Packed:
				<span class="packed-at">{{$order->packed_at ? $order->packed_at->format("g:i a") : ""}}</span>
			</li>
			<li class="list-group-item delivering-li{{$order->delivering_at ? "" : " hidden"}}">Out for delivery:
				<span
					class="delivering-at">{{$order->delivering_at ? $order->delivering_at->format("g:i a") : ""}}</span>
			</li>
			<li class="list-group-item delivered-li{{$order->delivered_at ? "" : " hidden"}}">Delivered:
				<span class="delivered-at">{{$order->delivered_at ? $order->delivered_at->format("g:i a") : ""}}</span>
			</li>
			<li class="list-group-item cancelled-li{{$order->cancelled_at ? "" : " hidden"}}">Cancelled:
				<span class="cancelled-at">{{$order->cancelled_at ? ($order->cancelled_at->day != $order->submitted_at->day ?
						$order->cancelled_at->format("M j, Y g:i a") : $order->cancelled_at->format("g:i a")) : ""}}</span>
			</li>
		</ul>
	</td>
	<td class="hidden-xs">
		<ul class="list-group">
			@foreach($order->items as $item)
				<li class="list-group-item">{{$item->quantity}} x {{$item->size}} {{$item->name}}</li>
			@endforeach
		</ul>
	</td>
	<td class="hidden-xs">
		{{$order->notes}}
	</td>
	<td class="order-buttons">
		<div class="visible-xs-block">
			<div>{{$order->name}}</div>
			<div>
				<a href="{{"http://maps.google.com/maps?" . http_build_query(["q"=>$order->address])}}">{{$order->address}}</a>
			</div>
			<div>
				<a href="tel:{{$order->phone}}">{{$order->phone}}</a>
			</div>
			<div>Tip: ${{$order->tip}}</div>
			@if(strlen($order->notes)>0)
				<div>Delivery Notes: {{$order->notes}}</div>
			@endif
			<ul class="list-group">
				@foreach($order->items as $item)
					<li class="list-group-item">{{$item->quantity}} x {{$item->size}} {{$item->name}}</li>
				@endforeach
			</ul>
		</div>
		<div class="packed-buttons btn-group-flex btn-group{{$order->status=="packed" ? "": " hidden"}} btn-group-flex">
			<button value="{{$order->store->id}}" type="button" class="btn btn-success order-delivering-button-driver">
				Picked up
			</button>
			<button value="{{$order->store->id}}" type="button" class="btn btn-danger order-cancel-button-driver">
				Cancel
			</button>
		</div>
		<div class="delivering-buttons btn-group-flex btn-group {{$order->status=="delivering" ? "": " hidden"}}">
			<button value="{{$order->store->id}}" type="button" class="btn btn-success order-delivered-button-driver">
				Delivered
			</button>
			<button value="{{$order->store->id}}" type="button" class="btn btn-danger order-cancel-button-driver">
				Cancel
			</button>
		</div>
	</td>
</tr>