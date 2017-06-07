@extends("layouts.admin.manage")
@section("title")
	Categories
@endsection
@section("heading")
	Categories
@endsection
@section("buttons")
	<div class="btn-group">
		<button id="show-subcategories-button" class="btn btn-default">
			<span class="">Subcategories</span>
		</button>
		<button type="button" id="categories-category-add-button" class="btn btn-success">
			<span class="">Add Category</span>
		</button>
		<input type="submit" id="categories-submit" class="btn btn-primary" form="categories-form" value="Update">
	</div>
@endsection
@section("managecontent")
	<form class="ajax-form panel-body" enctype="multipart/form-data" method="post"
		action="{{url ( "/admin/categories/update" )}}" id="categories-form">
		{!! csrf_field() !!}
		<div id="categories-div">
			@each("shared.elements.categorydiv",$categories,"category")
		</div>
	</form>
@endsection
