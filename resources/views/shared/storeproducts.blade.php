@section("buttons")
	<input type="text" id="product-name-filter" class="form-control" list="products-list"
		placeholder="Product name filter">
	<label class="checkbox-inline margin-left-10">
		<input type="checkbox" class="product-active-filter" value="1" checked>
		Active
	</label>
	<label class="checkbox-inline margin-right-10">
		<input type="checkbox" class="product-active-filter" value="0" checked>
		Inactive
	</label>
	<div class="btn-group">
		<div class="btn-group dropdown">
			<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
				Categories
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu products-category-menu scrollable-menu">
				@foreach($parentCategories as $category)
					<li>
						<label class="margin-left-5 checkbox-inline">
							<input class="products-category-filter" value="{{$category->id}}" type="checkbox" checked>
							{{$category->name}}
						</label>
					</li>
				@endforeach
				@foreach($parentCategories as $parentCategory)
					<li class="divider"></li>
					@foreach($parentCategory->children as $category)
						<li>
							<label class="margin-left-5 checkbox-inline">
								<input class="products-category-filter" value="{{$category->id}}" type="checkbox"
									checked>
								{{$category->name}}
							</label>
						</li>
					@endforeach
				@endforeach
			</ul>
		</div>
		<button value="{{$store->id}}" type="button" class="btn	btn-success products-add-button">Add</button>
		<button class="upload-input-button btn btn-success">Upload</button>
		<input type="submit" id="products-submit" class="btn btn-primary" form="products-form" value="Update">
		<button value="{{$store->id}}" type="button" class="products-delete-all-button btn btn-danger">
			Delete All
		</button>
	</div>
@endsection
<form method="post" action="{{$productUrl."/update"}}" id="products-form" class="hidden">
	{!! csrf_field() !!}
</form>
<form method="post" action="{{$productUrl."/update"}}" id="image-form" class="ajax-form hidden">
	{!! csrf_field() !!}
</form>
<form method="post" id="upload-form" action="{{$productUrl . "/upload"}}" class="hidden" enctype="multipart/form-data">
	<input id="upload-input" name="productsFile" type="file">
	{!! csrf_field() !!}
</form>
<div class="table-responsive">
	<table id="products-table" class="panel-body table store-table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Stock</th>
				<th class="text-center">Active</th>
				<th>Size</th>
				<th>Price</th>
				<th>Categories</th>
				<th>Image</th>

			</tr>
		</thead>
		<tbody>
			@foreach($products as $product)
				@include("shared.elements.productrow",["parentCategories"=>$parentCategories,"childCategories"=>$childCategories,"store"=>$store])
			@endforeach
		</tbody>
	</table>
</div>
<datalist id="products-list">
	@each("shared.elements.productoption",$products,"product")
</datalist>
<datalist id="images-list">
	@each("shared.elements.imageoption",$images,"image")
</datalist>
@section("modaltitle")
	Select Image
@endsection
@section("modalbuttons")
	<input type="text" id="image-name-filter" class="form-control" list="images-list" placeholder="Name filter">
@endsection
@section("modalcontent")
	<div class="modal-body inline-block">
		@foreach($images as $image)
			<div class="pointer image images-select-button col-xs-3 grid-panel panel panel-default" data-model="image"
				data-modelid="{{$image->id}}" data-src="{{asset($image->small)}}"
				data-name="{{strtolower($image->name)}}" id="image{{$image->id}}>">
				<div class="block panel-heading">
					<span class="input-group">
						{{$image->name}}
					</span>
				</div>
				<div class="panel-body">
					<img src="{{asset($image->full)}}" class="width-100-percent image-input img-thumbnail center-block">
				</div>
			</div>
		@endforeach
	</div>
@endsection
@section("modal")
	@include("shared.elements.modaldiv")
@endsection