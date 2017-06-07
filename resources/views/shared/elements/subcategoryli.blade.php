<li class="list-group-item" data-model="category" data-modelid="{{$subcategory->id}}" id="category{{$subcategory->id}}">
	<div class="input-group">
		<input title="Subcategory name" required class="form-control" type="text" value="{{$subcategory->name}}"
			name="categories[{{$subcategory->id}}>][name]">
		<div class="input-group-btn">
			<button class="btn btn-danger model-delete-button" type="button"> Delete
			</button>
		</div>
	</div>
</li>