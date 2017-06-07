@extends("layouts.admin.manage")
@section("title")
	Admins
@endsection
@section("heading")
	Admins
@endsection
@section("buttons")
	<input type="text" class="form-control" list="email-list" placeholder="Email filter" id="admin-email-filter">
	<input type="text" class="form-control" id="admin-name-filter" list="name-list" placeholder="Name filter">
	<a href="{{url("/admin/admins/add")}}" class="btn btn-success">Add</a>
@endsection
@section("managecontent")
	<div class="table-responsive">
		<table class="panel-body table" id="admin-table">
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th class="text-center">Full Control</th>
			</tr>
			@foreach ($admins as $admin)
				<tr class="admin" data-model="admin" id="admin{{$admin->id}}" data-name="{{strtolower($admin->name)}}"
					data-email="{{strtolower($admin->email)}}" data-modelid="{{$admin->id}}">
					<td>{{$admin->name}}</td>
					<td>{{$admin->email}}</td>
					<td class="text-center">
						<input
							{{$admin->id <= 2 || $admin->control && !$superAdmin ? "disabled":""}} title="Full control check"
							id="admin-full-control-check{{$admin->id}}" type="checkbox"
							class="admin-full-control-check" {{ $admin->control ? "checked" : ""}} >
					</td>
					<td align="right">
						<button
							{{$admin->id > 2 && ($admin == $currentAdmin || $superAdmin)?"":"disabled"}} type="button"
							class="btn btn-danger model-delete-button">Delete
						</button>
					</td>
				</tr>
			@endforeach
		</table>
	</div>
	<datalist id="email-list">
		@foreach ($admins as $admin)
			<option data-modelid="{{$admin->id}}" value="{{$admin->email}}">
		@endforeach
	</datalist>
	<datalist id="name-list">
		@foreach ($admins as $admin)
			<option data-modelid="{{$admin->id}}" value="{{$admin->name}}">
		@endforeach
	</datalist>
@endsection