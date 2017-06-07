<div data-model="category" data-modelid="{{$category->id}}" id="category{{$category->id}}">
	<div>
		<div class="list-group-item">
			<div class="input-group">
				<div class="input-group-btn">
					<a id="category{{$category->id}}-collapse-button" data-toggle="collapse"
						class="btn btn-default" href="#category{{$category->id}}-subcategories-ul">
						<span class="">Subcategories</span>
					</a>
				</div>
				<input required class="form-control" type="text" value="{{$category->name}}"
					name="categories[{{$category->id}}][name]" title="">
				<div class="input-group-btn">
					<span id="categories-default-image-button{{$category->id}}" class="btn btn-default btn-file">
						<span id="categories-default-image-text{{$category->id}}">Default Image</span>
						<input class="categories-default-image-input"
							id="categories-default-image-input{{$category->id}}" name="file{{$category->id}}"
							type="file">
					</span>
					<button class="btn btn-success categories-subcategory-add-button" type="button">
						Add subcategory
					</button>
					<button class="btn btn-danger model-delete-button" type="button">
						Delete
					</button>
				</div>
			</div>
		</div>
	</div>
	<ul class="collapse subcategory-list" id="category{{$category->id}}-subcategories-ul">
		@each("shared.elements.subcategoryli",$category->children,"subcategory")
	</ul>
</div>