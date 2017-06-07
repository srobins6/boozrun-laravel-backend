<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>@yield("title")</title>
		<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel="stylesheet" type="text/css">
		<link href="{{ asset("css/app.css") }}" rel="stylesheet">
		<link href="{{ asset("css/boozrun.css") }}" rel="stylesheet">
		<script src="{{ asset("js/jquery.js") }}"></script>
		<script src="{{ asset("js/jquery.geocomplete.js") }}"></script>
		<script src="{{ asset("js/jquery.easy-autocomplete.min.js") }}"></script>
		<script src="{{ asset("js/bootstrap.min.js") }}"></script>
		<script src="{{ asset("js/boozrun.js") }}"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key={{env("GOOGLE_KEY")}}&libraries=places"></script>
	</head>
	<body id="app-layout">
		<script>
			$(window).load(function () {
				autoFit();
				endWindowResize();
			});
			$.ajaxSetup({
				headers: {"X-CSRF-TOKEN": "{{ csrf_token() }}"},
				error  : function (XMLHttpRequest, textStatus, errorThrown) {
					if (XMLHttpRequest.getAllResponseHeaders()) { // checks if user cancelled request by navigating away
						errorResponse(XMLHttpRequest);
						console.log(textStatus);
						console.log(errorThrown);
					}
				}
			});
			//noinspection JSUnresolvedVariable
			window.scrollBarWidth = getScrollBarWidth();
			var bootstrapStartEvents = "hide.bs.dropdown show.bs.dropdown hide.bs.modal show.bs.modal " +
			                           "hide.bs.tab show.bs.tab hide.bs.collapse show.bs.collapse";
			var bootstrapEndEvents = "hidden.bs.dropdown shown.bs.dropdown hidden.bs.modal shown.bs.modal " +
			                         "hidden.bs.tab shown.bs.tab hidden.bs.collapse shown.bs.collapse";
			$(document)
			.ready(function () {
				$("#address-input").geocomplete();
			})
			.on(bootstrapStartEvents, "*", startWindowResize)
			.on(bootstrapEndEvents, "*", endWindowResize)
			.on("change", ".admin-full-control-check", adminFullControlCheckChange)
			.on("change", ".categories-default-image-input", categoriesDefaultFileInputChange)
			.on("change", ".contract-file", contractInputChange)
			.on("change", ".model-active-check", modelActiveCheckChange)
			.on("change", ".products-category-filter", storeProductsCategoryFilterChange)
			.on("change", ".products-category-parent-check", storeProductsCategoryCheckChange)
			.on("change", ".store-active-hours", storeHoursActiveSet)
			.on("change", "#upload-input", uploadInputChange)
			.on("change", "driver-option", driverOptionChange)
			.on("change", "input[class*='filter'][type='checkbox']", filterCheckChange)
			.on("change", "#order-date-operator", storeOrderDateOperatorChange)
			.on("change", "#tip-date-operator", tipDateOperatorChange)
			.on("click", ".alert-close", alertClose)
			.on("click", ".categories-subcategory-add-button", categoriesSubcategoryAddButtonClick)
			.on("click", ".driver-applicant-confirm-button", driverConfirmButtonClick)
			.on("click", ".driver-stores-button", driversStoresButtonClick)
			.on("click", ".hours-add-button", storeHoursAddButtonClick)
			.on("click", ".images-select-button", imagesSelectButtonClick)
			.on("click", ".model-delete-button-redirect", {redirect: true}, modelDeleteButtonClick)
			.on("click", ".model-delete-button", {redirect: false}, modelDeleteButtonClick)
			.on("click", ".order-cancel-button", storeOrderCancelButtonClick)
			.on("click", ".order-delivered-button", storeOrderDeliveredButtonClick)
			.on("click", ".order-delivering-button", storeOrderDeliveringButtonClick)
			.on("click", ".order-packed-button", storeOrderPackedButtonClick)
			//			.on("click", ".order-cancel-button-customer", customerOrderCancelButtonClick)
			//			.on("click", ".order-delivered-button-customer", customerOrderDeliveredButtonClick)
			//			.on("click", ".order-delivering-button-customer", customerOrderDeliveringButtonClick)
			//			.on("click", ".order-packed-button-customer", customerOrderPackedButtonClick)
			.on("click", ".order-delivered-button-driver", driverOrderDeliveredButtonClick)
			.on("click", ".order-delivering-button-driver", driverOrderDeliveringButtonClick)
			.on("click", ".order-cancel-button-driver", driverOrderCancelButtonClick)
			.on("click", ".products-add-button", storeProductsAddButtonClick)
			.on("click", ".products-category-menu", stopEventPropagation)
			.on("click", ".products-delete-all-button", storeProductsDeleteAllButtonClick)
			.on("click", ".products-image-button", storeProductsImageButtonClick)
			.on("click", ".store-model-delete-button", storeModelDeleteButtonClick)
			.on("click", ".upload-input-button", uploadInputButtonClick)
			.on("click", "#categories-category-add-button", categoriesCategoryAddButtonClick)
			.on("click", "#promo-add-button", promoAddButtonClick)
			.on("click", "#show-subcategories-button", showAllSubcategories)
			.on("input change click", "*", windowResize)
			.on("input", ".order-date-filter", storeOrderDateFilterInput)
			.on("input", ".tip-date-filter", tipDateFilterInput)
			.on("input", "input[id$='filter'][type='text'],input[id$='filter'][type='number']", filterInput)
			.on("show.bs.modal", setNavbarMargin)
			.on("shown.bs.modal", ".modal", driversStoresModalShow)
			.on("submit", ".ajax-form", ajaxFormSubmit)
			.on("submit", "#stores-form", storesFormSubmit)
			.on("submit", "#add-promo-form", addPromoFormSubmit);
			$(window).resize(windowResize);
			startWindowResize();
		</script>
		@yield("headscripts")
		@yield("modal")
		@yield("navbar")
		<div id="alert-container">
			@if(session("alert"))
				<div id="alert" class="alert alert-{{session("alertType")}} alert-dismissible row" role="alert">
					<div class="col-xs-10" id="alert-content">{!!session("alert")!!}</div>
					<div class="col-xs-2 text-right">
						<button type="button" class="close" id="alert-close-button">
							<span aria-hidden="true">&times;
							</span>
						</button>
					</div>
				</div>
			@endif
		</div>
		<div id="content-container" class="container-scrollfix container-fluid">
			<div id="layout" class="{{colsClass($numCols)}}">
				@yield("layout")
			</div>
		</div>
		@yield("scripts")
	</body>
</html>
