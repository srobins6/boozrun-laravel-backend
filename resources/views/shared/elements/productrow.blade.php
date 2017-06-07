<tr class="product" data-model="product" data-modelid="{{$product->id}}" id="product{{$product->id}}"
	data-name="{{strtolower($product->name)}}" data-categories="{{$product->categories->pluck("id")}}"
	data-active="{{$product->active}}">
	<td>
		<input form="products-form" class="form-control" value="{{$product->name}}"
			name="products[{{$product->id}}][name]" type="text" required title="Product name">
	</td>
	<td>
		<input form="products-form" class="form-control" value="{{$product->stock}}"
			name="products[{{$product->id}}][stock]" type="number" required title="Product stock">
	</td>
	<td class="text-center">
		<input form="products-form" type="checkbox" data-storeid="{{$store->id}}" class="product-active-check"
			value="{{$product->id}}" name="products[{{$product->id}}][active]"
			{{$product->active ? "checked" : ""}} title="Product active">
	</td>
	<td>
		<input form="products-form" class="form-control" value="{{$product->size}}"
			name="products[{{$product->id}}][size]" type="text" required title="Product size">
	</td>
	<td>
		<input class="form-control" value="{{$product->price}}" form="products-form"
			name="products[{{$product->id}}][price]" step="0.01" type="number" required title="Product price">
	</td>
	<td>
		<div class="dropdown btn-group">
			<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
				Categories
				<span class="caret"></span>
			</a>
			<ul class="products-category-menu dropdown-menu scrollable-menu ">
				@foreach ($parentCategories as $category)
					<li>
						<label class="margin-left-5 checkbox-inline">
							<input name="products[{{$product->id}}][categories][]" form="products-form" type="checkbox"
								value="{{$category->id}}" class="products-category-check products-category-parent-check
								product{{$product->id}}-category"
								{{$product->categories->contains($category) ? "checked" :null }}>{{$category->name}}
						</label>
					</li>
				@endforeach
				@foreach($parentCategories as $parentCategory)
					<li class="{{$product->categories->contains($parentCategory) ? "" : "hidden "}}divider product{{$product->id}}-category"
						data-parentid="{{$parentCategory->id}}"></li>
					@foreach($parentCategory->children as $category)
						<li class="{{$product->categories->contains($parentCategory) ? "" : "hidden "}}product{{$product->id}}-category"
							data-parentid="{{$parentCategory->id}}">
							<label class="margin-left-5 checkbox-inline">
								<input form="products-form" data-parentid="{{$parentCategory->id}}"
									name="products[{{$product->id}}][categories][]" type="checkbox"
									value="{{$category->id}}"
									class="products-category-check product{{$product->id}}-category"
									{{$product->categories->contains($category) ? "checked" : null}}>
								{{$category->name}}
							</label>
						</li>
					@endforeach
				@endforeach
			</ul>
		</div>
	</td>
	<td>
		<input type="hidden" class="products-image-input" name="products[{{$product->id}}][image_id]"
			id="products-image-input{{$product->id}}" value="{{$product->image_id}}">
		<button id="products-image-button{{$product->id}}" class="clear-button products-image-button">
			<img src="{{$product->image ? asset($product->image->small) : null}}" id="products-image{{$product->id}}"
				class="image-input products-image-button-img img-thumbnail center-block">
		</button>
	</td>
	<td class="text-right">
		<button value="{{$store->id}}" type="button" class="btn btn-danger store-model-delete-button">Delete Product
		</button>
	</td>
</tr>