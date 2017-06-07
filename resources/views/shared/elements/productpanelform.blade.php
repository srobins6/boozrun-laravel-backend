<form class="col-xs-12 col-sm-6 col-md-4 col-lg-3 product-panel product panel panel-default ajax-form margin-0"
	id="{{$productList->first()->name}}" method="POST" action="{{ url("/orderadd") }}">
	<div class="panel-heading product-heading">
		<div class="h4 vertical-center product-name">
			<div class="autofit">{{$productList->first()->name}}</div>
		</div>
		<div class="input-group">
			<label class="input-group-addon" for="{{$productList->first()->name}}-size">Size:</label>
			@if($productList->count() == 1)
				<input type="hidden" name="productId" value="{{$productList->first()->id}}">
			@endif
			<select class="form-control" id="{{$productList->first()->name}}-size"
				{{$productList->count()==1 ? "disabled" : "name=\"productId\""}}>
				@foreach($productList as $product)
					<option value="{{$product->id}}">{{$product->size}}</option>
				@endforeach
			</select>
			<label for="{{$productList->first()->name}}-quantity" class="input-group-addon input-group-addon-middle">
				Qty:
			</label>
			<input id="{{$productList->first()->name}}-quantity" class="form-control" type="number" pattern="[0-9]*"
				name="quantity" min=1 max={{$productList->first()->stock}} value=1>
		</div>
	</div>
	<div class="panel-body product-body">
		<img src="{{$productList->first()->image ? asset($productList->first()->image->full) : null}}"
			class="img-rounded img-responsive center-block margin-bottom-0">
		<div class="input-group">
			<div class="input-group-addon">
				Price:
			</div>
			<div class="form-control">
				$
				<span id="{{$productList->first()->name}}-price">
					{{$productList->first()->price}}
				</span>
			</div>
			<div class="input-group-btn">
				@if($productList->first()->stock > 0)
					<input class="btn btn-primary" type="submit" name="submit" value="Add to cart">
				@else
					<input class="btn btn-default" disabled type="submit" name="submit" value="Out of stock">
				@endif
			</div>
		</div>
	</div>
</form>