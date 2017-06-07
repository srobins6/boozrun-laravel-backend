<tr class="order small" data-model="order" data-modelid="{{$order->id}}" id="order{{$order->id}}"
	data-name="{{strtolower($order->name)}}" data-date="{{strtotime($order->submitted_at->toDateString())}}">
	<td>{{$order->submitted_at->toFormattedDateString()}}</td>
	<td>{{$order->name}}</td>
	<td>{{$order->address}}</td>
	<td>{{$order->phone}}</td>
	<td>{{$order->total}}</td>
	<td>{{$order->tip}}</td>
	<td>
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
	<td class="order-driver{{$order->driver || $order->status=="cancelled" ? "" : " hidden"}}">{{$order->driver ? $order->driver->name : "N/A"}}</td>
	<td>
		<ul class="list-group">
			@foreach($order->items as $item)
				<li class="list-group-item">{{$item->quantity}} x {{$item->size}} {{$item->name}}</li>
			@endforeach
		</ul>
	</td>
	<td>
		{{$order->notes}}
	</td>
	<td class="text-right order-buttons">
		<div class="submitted-buttons btn-group-flex btn-group {{$order->status=="submitted" ? "": " hidden"}}">
			<button value="{{$order->store->id}}" type="button" class="btn btn-success order-packed-button">Packed
			</button>
			<button value="{{$order->store->id}}" type="button" class="btn btn-danger order-cancel-button">
				Cancel
			</button>
		</div>
		<div class="packed-buttons {{$order->status=="packed" ? "": " hidden"}}">
			<select title="Order driver select" class="form-control driver-select">
				@foreach($order->store->drivers->where("active",1) as $driver)
					<option value="{{$driver->id}}">{{$driver->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="packed-buttons btn-group-flex btn-group{{$order->status=="packed" ? "": " hidden"}}">
			<button value="{{$order->store->id}}" type="button" class="btn btn-success order-delivering-button">
				Picked up
			</button>
			<button value="{{$order->store->id}}" type="button" class="btn btn-danger order-cancel-button">
				Cancel
			</button>
		</div>
		<div class="delivering-buttons btn-group-flex btn-group {{$order->status=="delivering" ? "": " hidden"}}">
			<button value="{{$order->store->id}}" type="button" class="btn btn-success order-delivered-button">Delivered
			</button>
			<button value="{{$order->store->id}}" type="button" class="btn btn-danger order-cancel-button">
				Cancel
			</button>
		</div>
		<div class="delivered-buttons btn-group-flex btn-group {{$order->status=="delivered" ? "": " hidden"}}">
			<button value="{{$order->store->id}}" type="button" class="btn btn-danger order-cancel-button">
				Cancel
			</button>
		</div>
	</td>
</tr>