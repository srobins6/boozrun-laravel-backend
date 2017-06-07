@extends("layouts.admin")
@section("content")
	<div class="vertical-center push-apart manage-heading panel-heading">
		<div class="h3 margin-0 vertical-center">
			@yield("heading")
			@yield("menu")
		</div>
		<div class="form-inline vertical-center push-apart">@yield("buttons")</div>
	</div>
	@yield("managecontent")
@endsection