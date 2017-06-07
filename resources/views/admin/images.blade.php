@extends("layouts.admin.manage")
@section("title")
	Images
@endsection
@section("heading")
	Images
@endsection
@section("buttons")
	<input type="text" id="image-name-filter" class="form-control" list="images-list" placeholder="Name filter">
	<div class="btn-group">
		<button class="upload-input-button btn btn-success">
			Upload
		</button>
		<input type="submit" id="images-submit" class="btn btn-primary" form="images-form" value="Update">
	</div>
@endsection
@section("managecontent")
	<form method="post" class="hidden" id="upload-form" action="{{url ( "/admin/images/upload" )}}"
		enctype="multipart/form-data">
		<input onchange="" multiple id="upload-input" name="imageFiles[]" type="file">
		{!! csrf_field() !!}
	</form>
	<form class="panel-body padding-0 ajax-form" enctype="multipart/form-data" method="post"
		action="{{url ( "/admin/images/update" )}}" id="images-form">
		{!! csrf_field() !!}
		<div>
			@foreach($images as $image)
				<div class="image col-lg-2 col-md-3 col-sm-4 col-xs-6 grid-panel panel panel-default"
					data-name="{{strtolower($image->name)}}" id="image{{$image->id}}" data-model="image"
					data-modelid="{{$image->id}}">
					<div class="image-heading panel-heading">
						@if($image->default)
							<input title="Image name" readonly type="text" class="form-control"
								name="images[{{$image->id}}][name]" value="{{$image->name}}">
						@else
							<div class="input-group">
								<input title="Image name" type="text" class="form-control"
									name="images[{{$image->id}}][name]" value="{{$image->name}}">
								<span class="input-group-btn">
									<button class="btn btn-danger model-delete-button" type="button">Delete
									</button>
								</span>
							</div>
						@endif
					</div>
					<div class="panel-body">
						<img src="{{asset($image->full)}}"
							class="width-100-percent image-input img-rounded center-block">
					</div>
				</div>
			@endforeach
		</div>
	</form>
	<datalist id="images-list">
		@each("shared.elements.imageoption",$images,"image")
	</datalist>
@endsection