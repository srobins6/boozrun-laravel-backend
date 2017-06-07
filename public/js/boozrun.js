var superAdmin;
var urls;
(function ($) {
	$.fn.modelParent = function () {
		return $(this).parents("[data-model]");
	};
	$.fn.model = function () {
		var model = $(this).data("model");
		if (!model) {
			var $model = $(this).modelParent();
			model = $model.data("model");
		}
		return model;
	};
	$.fn.modelId = function () {
		var id = $(this).data("modelid");
		if (!id) {
			var $model = $(this).modelParent();
			id = $model.data("modelid");
		}
		return id;
	};
	$.fn.lineHeight = function () {
		var lineHeight = parseInt($(this).css("line-height"));
		if (isNaN(lineHeight)) {
			var fontSize = $(this).css('font-size');
			lineHeight = Math.floor(parseInt(fontSize.replace('px', '')) * 1.5);
		}
		return lineHeight;
	};
	$.fn.autoFit = function () {
		$(this).each(function () {
			var lines = this.offsetHeight / $(this).lineHeight();
			if (lines > 1) {
				var fontSize = parseInt($(this).css('font-size'));
				while (lines > 1 && fontSize > 1) {
					fontSize -= 1;
					$(this).css('font-size', fontSize + "px");
					lines = this.offsetHeight / $(this).lineHeight();
				}
			}
		});
	}
})(jQuery);
function uploadInputChange() {
	$(" #upload-form").submit();
	this.value = "";
}
function uploadInputButtonClick() {
	$("#upload-input").click();
}
function ajaxFormSubmit(event) {
	$(this).ajaxSubmit({
		success: successResponse, error: errorResponse
	});
	event.preventDefault();
}
function autoFit() {
	$(".autofit").autoFit();
}
/**
 * Function for clicking on a model delete button.
 * @param event
 */
function modelDeleteButtonClick(event) {
	var model = $(this).model();
	var id = $(this).modelId();
	var data = event.data;
	addConfirmAlert("Are you sure?", "danger", function () {
		$.post(urls[model] + id + "/delete", data).done(function (response) {
			$("[data-modelid=" + id + "]").detach();
			successResponse(response);
		});//.fail(errorResponse)
	});
}
function successResponse(response) {
	if (window.undo) {
		delete window.undo;
	}
	/** @namespace response.alertType */
	if (response.message && response.alertType) {
		addAlert(response.message, response.alertType, response.data);
	}
	console.log(response);
}
function errorResponse(response) {
	console.log(response);
	if (window.undo) {
		window.undo();
		delete window.undo;
	}
	/** @namespace response.responseJSON */
	if (response.responseJSON) {
		response = response.responseJSON;
		addAlert(response.message, response.alertType, response.data);
	} else if (response.responseText) {
		var win = window.open("");
		win.document.body.innerHTML = response.responseText;
	}
}
function test() {
	$.post("/boozrun/public/test").done(successResponse);//.fail(errorResponse)
}
function tipDateOperatorChange() {
	if (this.value == "between") {
		$(".tip-date-filter-between").removeClass("hidden");
	} else {
		$(".tip-date-filter-between").addClass("hidden");
	}
	$("#tip-date-filter").trigger("input");
}
function tipDateFilterInput() {
	var $tips = $(".tip");
	var $good;
	var dateValue = $("#tip-date-filter").val();
	var operator = $("#tip-date-operator").val();
	if (dateValue.length == 0 || operator == "") {
		$tips.removeClass("hidden");
	} else {
		if (parseInt(dateValue.slice(0, 4)) < 2015) {
			return;
		}
		dateValue += " 00:00:00 GMT-0500";
		var date = new Date(dateValue);
		var epochDate = date.getTime() / 1000;
		var dateCompareFunction;
		if (operator == "on") {
			dateCompareFunction = function () {
				return $(this).data("date") == epochDate;
			}
		} else if (operator == "before") {
			dateCompareFunction = function () {
				return $(this).data("date") <= epochDate;
			}
		} else if (operator == "after") {
			dateCompareFunction = function () {
				return $(this).data("date") >= epochDate;
			}
		} else if (operator == "between") {
			var endDateValue = $("#tip-date-filter-end").val() + " 00:00:00 GMT-0500";
			var endDate = new Date(endDateValue);
			var endEpochDate = endDate.getTime() / 1000;
			dateCompareFunction = function () {
				return $(this).data("date") >= epochDate && $(this).data("date") <= endEpochDate;
			}
		}
		$good = $tips.filter(dateCompareFunction);
		$good.removeClass("hidden");
		$tips.not($good).addClass("hidden");
	}
}
function storeOrderDateOperatorChange() {
	if (this.value == "between") {
		$(".order-date-filter-between").removeClass("hidden");
	} else {
		$(".order-date-filter-between").addClass("hidden");
	}
	$("#order-date-filter").trigger("input");
}
function storeOrderDateFilterInput() {
	var $orders = $(".order");
	var $good;
	var dateValue = $("#order-date-filter").val();
	var operator = $("#order-date-operator").val();
	if (dateValue.length == 0 || operator == "") {
		$orders.removeClass("hidden");
	} else {
		if (parseInt(dateValue.slice(0, 4)) < 2015) {
			return;
		}
		dateValue += " 00:00:00 GMT-0500";
		var date = new Date(dateValue);
		var epochDate = date.getTime() / 1000;
		var dateCompareFunction;
		if (operator == "on") {
			dateCompareFunction = function () {
				return $(this).data("date") == epochDate;
			}
		} else if (operator == "before") {
			dateCompareFunction = function () {
				return $(this).data("date") <= epochDate;
			}
		} else if (operator == "after") {
			dateCompareFunction = function () {
				return $(this).data("date") >= epochDate;
			}
		} else if (operator == "between") {
			var endDateValue = $("#order-date-filter-end").val() + " 00:00:00 GMT-0500";
			var endDate = new Date(endDateValue);
			var endEpochDate = endDate.getTime() / 1000;
			dateCompareFunction = function () {
				return $(this).data("date") >= epochDate && $(this).data("date") <= endEpochDate;
			}
		}
		$good = $orders.filter(dateCompareFunction);
		$good.removeClass("hidden");
		// $good.parents(".collapse:not(.in)").collapse("show");
		$orders.not($good).addClass("hidden");
	}
	$(".order-table").each(function () {
		var $headerRow = $(this).find("tr:not(.order)");
		if ($(this).find("tr.order:not(.hidden)").length > 0) {
			$headerRow.removeClass("hidden");
		} else {
			$headerRow.addClass("hidden");
		}
	});
}
/**
 * Function for changing the active hours for a store.
 */

function storeHoursActiveSet() {
	var model = $(this).model();
	var id = $(this).modelId();
	$.post(urls[model] + id + "/hours/" + this.value + "/active")
	.done(successResponse);//.fail(errorResponse)
}
/**
 *  Function for clicking on a model active check.
 */
function modelActiveCheckChange() {
	var active;
	if (this.checked) {
		active = "1";
	} else {
		active = "0";
	}
	var model = $(this).model();
	var id = $(this).modelId();
	$.post(urls[model] + id + "/active", {
		active: this.checked
	}).done(function (response) {
		$("#" + model + +id).attr("data-active", active);
		$("." + model + "-active-filter").change();
		successResponse(response);
	});//.fail(errorResponse)
}
/**
 *  Function for clicking on the driver confirm button.
 */
function driverConfirmButtonClick() {
	var model = $(this).model();
	var id = $(this).modelId();
	addConfirmAlert("Are you sure?", "success", function () {
		$.post(urls[model] + id + "/confirm").done(function (response) {
			$("[data-modelid=" + id + "]").detach();
			successResponse(response);
		});//.fail(errorResponse)
	});
}
/**
 * Function for input on a filter.
 */
function filterInput() {
	console.log(1);
	var selectorFilter = this.id.split("-");
	var selector = "." + selectorFilter[0];
	var filterName = selectorFilter[1];
	var $good;
	if (this.value.length > 0) {
		$good = $(selector + "[data-" + filterName + "*=" +
		          this.value.replace(/([\\ !"#$%&'()*+,.\/:;<=>?@[\]^`{|}~])/g, "\\$1")
		          .toLowerCase() + "]");
		$(selector).not($good).addClass("hidden");
	} else {
		$good = $(selector);
	}
	$good.removeClass("hidden");
}
/**
 * Function for changing a filter checkbox.
 */
function filterCheckChange() {
	var classList = this.classList;
	var className;
	classList.forEach(function (cname) {
		if (cname.match(/filter/)) {
			className = cname;
		}
	});
	if (className) {
		var selectorFilter = className.split("-");
		var selector = "." + selectorFilter[0];
		var filterName = selectorFilter[1];
		var rows = $(selector + "[data-" + filterName + "=" +
		             this.value.replace(/([\\ !"#$%&'()*+,.\/:;<=>?@[\]^`{|}~])/g, "\\$1")
		             .toLowerCase() + "]");
		if (this.checked) {
			rows.removeClass("hidden");
		} else {
			rows.addClass("hidden");
		}
	}
}
/**
 * Function for changing whether or not an admin has full control powers.
 */
function adminFullControlCheckChange() {
	var model = $(this).model();
	var id = $(this).modelId();
	var checked = this.checked;
	addConfirmAlert("Are you sure?", "success", function () {
		$.post(urls[model] + id + "/fullcontrol", {
			control: checked
		}).done(function (response) {
			if (!superAdmin && checked) {
				$("#admin-full-control-check" + id).prop("disabled", true);
			}
			successResponse(response);
		});//.fail(errorResponse)
	});
	if (checked) {
		window.undo = function () {
			$("#admin-full-control-check" + id).removeAttr("checked");
		}
	} else {
		window.undo = function () {
			$("#admin-full-control-check" + id).prop("checked", true);
		}
	}
}
function storeModelDeleteButtonClick() {
	var storeId = this.value;
	var model = $(this).model();
	var id = $(this).modelId();
	addConfirmAlert("Are you sure?", "danger", function () {
		$.post(urls["store"] + storeId + urls["store" + model] + id + "/delete")
		.done(function (response) {
			$("[data-modelid=" + id + "]").detach();
			successResponse(response);
		});//.fail(errorResponse)
	});
}
function storeOrdersUpdate(storeId) {
	$.get(urls["store"] + storeId + "/orders/update").done(function (response) {
		/** @namespace response.data.orders */
		var orders = response.data.orders;
		for (var status in orders) {
			var $table = $("#" + status);
			var oldRows = $table.find("tbody").find("tr").length;
			$table.find("tbody").html(orders[status]);
			var newRows = $table.find("tbody").find("tr").length;
			if (newRows != oldRows) {
				if ($table.find("tbody").find("tr").length == 0) {
					$table.find("thead").addClass("hidden");
					$table.collapse("hide");
				} else {
					$table.find("thead").removeClass("hidden");
					$table.collapse("show");
				}
			}
		}
	});//.fail(errorResponse)
}
function storeOrderPackedButtonClick() {
	var storeId = this.value;
	var model = $(this).model();
	var id = $(this).modelId();
	addConfirmAlert("Are you sure?", "success", function () {
		$.post(urls["store"] + storeId + urls["store" + model] + id + "/packed")
		.done(function (response) {
			var $order = $("#order" + id);
			$order.find(".submitted-buttons").detach();
			$order.find(".packed-buttons").removeClass("hidden");
			$order.find(".packed-at").html(response.data.time);
			$order.find(".packed-li").removeClass("hidden");
			var $packed = $("#packed");
			$packed.find("tbody").append($order);
			$packed.find("thead").removeClass("hidden");
			$packed.collapse("show");
			var $submitted = $("#submitted");
			if ($submitted.find("tbody").find("tr").length == 0) {
				$submitted.find("thead").addClass("hidden");
				$submitted.collapse("hide");
			}
			successResponse(response);
		});//.fail(errorResponse)
	});
}
function storeOrderCancelButtonClick() {
	var storeId = this.value;
	var model = $(this).model();
	var id = $(this).modelId();
	addConfirmAlert("Are you sure?", "danger", function () {
		$.post(urls["store"] + storeId + urls["store" + model] + id + "/cancel")
		.done(function (response) {
			var $order = $("#order" + id);
			$order.find(".cancelled-at").html(response.data.time);
			$order.find(".cancelled-li").removeClass("hidden");
			$order.find(".order-buttons").detach();
			var $orderDriver = $order.find(".order-driver");
			$orderDriver.removeClass("hidden");
			var $cancelled = $("#cancelled");
			$cancelled.find("tbody").append($order);
			$cancelled.find("thead").removeClass("hidden");
			$cancelled.collapse("show");
			$(".order-panel").each(function () {
				if ($(this).find("tbody").find("tr").length == 0) {
					$(this).find("thead").addClass("hidden");
					$(this).collapse("hide");
				}
			});
			successResponse(response);
		});//.fail(errorResponse)
	});
}
function storeOrderDeliveringButtonClick() {
	var storeId = this.value;
	var model = $(this).model();
	var id = $(this).modelId();
	var driverId = $("#order" + id).find(".driver-select").val();
	addConfirmAlert("Are you sure?", "success", function () {
		$.post(urls["store"] + storeId + urls["store" + model] + id + "/delivering",
			{driverId: driverId})
		.done(function (response) {
			var $order = $("#order" + id);
			$order.find(".submitted-buttons").detach();
			$order.find(".packed-buttons").detach();
			$order.find(".delivering-buttons").removeClass("hidden");
			$order.find(".delivering-at").html(response.data.time);
			$order.find(".delivering-li").removeClass("hidden");
			var $orderDriver = $order.find(".order-driver");
			/** @namespace response.data.driverName */
			$orderDriver.html(response.data.driverName);
			$orderDriver.removeClass("hidden");
			var $delivering = $("#delivering");
			$delivering.find("tbody").append($order);
			$delivering.find("thead").removeClass("hidden");
			$delivering.collapse("show");
			var $packed = $("#packed");
			if ($packed.find("tbody").find("tr").length == 0) {
				$packed.find("thead").addClass("hidden");
				$packed.collapse("hide");
			}
			successResponse(response);
		});//.fail(errorResponse)
	});
}
function storeOrderDeliveredButtonClick() {
	var storeId = this.value;
	var model = $(this).model();
	var id = $(this).modelId();
	addConfirmAlert("Are you sure?", "success", function () {
		$.post(urls["store"] + storeId + urls["store" + model] + id + "/delivered")
		.done(function (response) {
			var $order = $("#order" + id);
			$order.find(".submitted-buttons").detach();
			$order.find(".packed-buttons").detach();
			$order.find(".delivering-buttons").detach();
			$order.find(".delivered-buttons").removeClass("hidden");
			$order.find(".delivered-at").html(response.data.time);
			$order.find(".delivered-li").removeClass("hidden");
			var $delivered = $("#delivered");
			$delivered.find("tbody").append($order);
			$delivered.find("thead").removeClass("hidden");
			$delivered.collapse("show");
			var $delivering = $("#delivering");
			if ($delivering.find("tbody").find("tr").length == 0) {
				$delivering.find("thead").addClass("hidden");
				$delivering.collapse("hide");
			}
			successResponse(response);
		});//.fail(errorResponse)
	});
}
function driverOrderDeliveringButtonClick() {
	var storeId = this.value;
	var model = $(this).model();
	var id = $(this).modelId();
	var driverId = window.driverId;
	addConfirmAlert("Are you sure?", "success", function () {
		$.post(urls["driver"] + storeId + urls[model] + id + "/delivering", {driverId: driverId})
		.done(function (response) {
			var $order = $("#order" + id);
			$order.find(".packed-buttons").detach();
			$order.find(".delivering-buttons").removeClass("hidden");
			$order.find(".delivering-at").html(response.data.time);
			$order.find(".delivering-li").removeClass("hidden");
			var $orderDriver = $order.find(".order-driver");
			/** @namespace response.data.driverName */
			$orderDriver.html(response.data.driverName);
			$orderDriver.removeClass("hidden");
			var $delivering = $("#delivering" + storeId);
			$delivering.find("tbody").append($order);
			$delivering.find("thead").removeClass("hidden");
			$delivering.collapse("show");
			var $packed = $("#packed" + storeId);
			if ($packed.find("tbody").find("tr").length == 0) {
				$packed.find("thead").addClass("hidden");
				$packed.collapse("hide");
			}
			successResponse(response);
		});//.fail(errorResponse)
	});
}
function driverOrdersUpdate(driverId) {
	$.post(urls["driver"] + driverId + "/orders/update").done(function (response) {
		/** @namespace response.data.orders */
		for (var storeId in response.data.orders) {
			var orders = response.data.orders[storeId];
			for (var status in orders) {
				var $table = $("#" + status + storeId);
				var oldRows = $table.find("tbody").find("tr").length;
				$table.find("tbody").html(orders[status]);
				var newRows = $table.find("tbody").find("tr").length;
				if (newRows != oldRows) {
					if ($table.find("tbody").find("tr").length == 0) {
						$table.find("thead").addClass("hidden");
						$table.collapse("hide");
					} else {
						$table.find("thead").removeClass("hidden");
						$table.collapse("show");
					}
				}
			}
		}
	});//.fail(errorResponse)
}
function driverOrderCancelButtonClick() {
	var storeId = this.value;
	var model = $(this).model();
	var id = $(this).modelId();
	addConfirmAlert("Are you sure?", "danger", function () {
		$.post(urls["driver"] + storeId + urls[model] + id + "/cancel")
		.done(function (response) {
			var $order = $("#order" + id);
			$order.detach();
			var $delivering = $("#delivering" + storeId);
			if ($delivering.find("tbody").find("tr").length == 0) {
				$delivering.find("thead").addClass("hidden");
				$delivering.collapse("hide");
			}
			successResponse(response);
		});//.fail(errorResponse)
	});
}
function driverOrderDeliveredButtonClick() {
	var storeId = this.value;
	var model = $(this).model();
	var id = $(this).modelId();
	addConfirmAlert("Are you sure?", "success", function () {
		$.post(urls["driver"] + storeId + urls[model] + id + "/delivered")
		.done(function (response) {
			var $order = $("#order" + id);
			$order.detach();
			var $delivering = $("#delivering" + storeId);
			if ($delivering.find("tbody").find("tr").length == 0) {
				$delivering.find("thead").addClass("hidden");
				$delivering.collapse("hide");
			}
			successResponse(response);
		});//.fail(errorResponse)
	});
}
/**
 * Hide/show the details for driver application form fields.
 */
function driverOptionChange() {
	var field = this.name;
	var answer = this.value;
	var $details = $("#" + field + "-details");
	var $detailsInput = $("#" + field + "-details-input");
	if (answer == true) {
		$details.removeClass("hidden");
		$detailsInput.prop("required", true);
		$detailsInput.removeAttr("disabled");
	} else {
		$details.addClass("hidden");
		$detailsInput.prop("disabled", true);
		$detailsInput.removeAttr("required");
	}
}
/**
 * Function for clicking the delete all products button.
 */
function storeProductsDeleteAllButtonClick() {
	var storeId = this.value;
	addConfirmAlert("Are you sure?", "danger", function () {
		$.post(urls["store"] + storeId + "/products/deleteall")
		.done(function (response) {
			$(".product").detach();
			$(".product-option").detach();
			successResponse(response);
		});//.fail(errorResponse)
	});
}
/**
 * Function for changing categories on a product.
 */
function storeProductsCategoryCheckChange() {
	var productId = $(this).modelId();
	var categoryId = this.value;
	var checked = this.checked;
	var children = $(".product" + productId + "-category[data-parentid=" + categoryId + "]");
	if (!checked) {
		children.prop("checked", false);
		children.addClass("hidden");
	} else {
		children.removeClass("hidden");
	}
	windowResize();
}
/**
 * Function for changing the product category filter.
 */
function storeProductsCategoryFilterChange() {
	var rows = $(".product[data-categories*=" +
	             this.value.replace(/([\\ !"#$%&'()*+,.\/:;<=>?@[\]^`{|}~])/g, "\\$1")
	             .toLowerCase() + "]");
	if (this.checked) {
		rows.removeClass("hidden");
	} else {
		rows.addClass("hidden");
	}
}
/**
 * Function for clicking on a store products image button.
 */
function storeProductsImageButtonClick() {
	var productId = $(this).modelId();
	$(".modal").modal("show");
	windowResize();
	$(".images-select-button").data("productid", productId);
}
/**
 * Function for clicking on a store products image button.
 */
function promoAddButtonClick() {
	$(".modal").modal("show");
	windowResize();
}
function showAllSubcategories() {
	$(".subcategory-list").collapse("show");
}
function stopEventPropagation(event) {
	event.stopPropagation();
}
function storesFormSubmit(event) {
	event.preventDefault();
	$(this).ajaxSubmit({
		success : function (response) {
			//noinspection JSUnresolvedVariable
			$("#driver" + response.data.driverId).data("stores", response.data.stores);
			successResponse(response)
		}, error: errorResponse
	});
	$(".modal").modal("hide");
}
function addPromoFormSubmit(event) {
	event.preventDefault();
	$(this).ajaxSubmit({
		success : function (response) {
			//noinspection JSUnresolvedVariable
			var promoId = response.data.promoId;
			var newRow = response.data.newRow;
			var $promo = $("#promo" + promoId);
			if ($promo.length > 0) {
				$promo[0].outerHTML = newRow;
			} else {
				$("#promo-table").find("tbody").prepend(newRow);
			}
			successResponse(response);
		}, error: errorResponse
	});
	$(".modal").modal("hide");
}
/**
 * Function for clicking on the stores button for a driver.
 */
function driversStoresButtonClick() {
	var model = $(this).model();
	var id = $(this).modelId();
	var stores = $(this).modelParent().data("stores");
	$("#stores-form").attr("action", urls[model] + id + "/stores");
	$(".modal").modal("show");
	$("#stores-select").find("option").each(function () {
		if (stores.indexOf(parseInt(this.value)) >= 0) {
			$(this).prop("selected", true);
		}
	});
}
/**
 *  Function for clicking on the add category button.
 */
function categoriesCategoryAddButtonClick() {
	$.post(urls["category"] + "addcategory").done(function (response) {
		/** @namespace response.data.newDiv */
		$("#categories-div").append(response.data.newDiv);
		successResponse(response);
	});//.fail(errorResponse)
}
/**
 * Function for clicking on an add subcategory button.
 */
function categoriesSubcategoryAddButtonClick() {
	var id = $(this).modelId();
	var model = $(this).model();
	var $subcategoriesUl = $("#category" + id + "-subcategories-ul");
	if (!$subcategoriesUl.hasClass("in")) {
		$subcategoriesUl.collapse("show");
	}
	$.post(urls[model] + id + "/addsubcategory").done(function (response) {
		/** @namespace response.data.newLi */
		$("#category" + id + "-subcategories-ul").append(response.data.newLi);
		successResponse(response);
	});//.fail(errorResponse)
}
/**
 * Handler for when the stores modal shows for a driver.
 */
function driversStoresModalShow() {
	var $storesSelect = $("#stores-select");
	if (!$storesSelect.is(":focus")) {
		$storesSelect.focus();
	}
}
/**
 * Function for when an image is selected for a product.
 */
function imagesSelectButtonClick() {
	var imageId = $(this).modelId();
	var src = $(this).data("src");
	var productId = $(this).data("productid");
	var $imageInput = $("#products-image-input" + productId);
	$imageInput.val(imageId);
	$imageInput.attr("form", "image-form");
	$("#products-image" + productId).attr("src", src);
	$("#images-modal").modal("hide");
	$("#image-form").submit();
	$imageInput.removeAttr("form");
}
/**
 * Function for clicking on the add store hours button.
 */
function storeHoursAddButtonClick() {
	var storeId = this.value;
	$.post(urls["store"] + storeId + "/hours/add").done(function (response) {
		$("#hours-table").find("tbody").append(response.data.newRow);
		successResponse(response);
	});//.fail(errorResponse)
}
/**
 * Function for clicking on the add product button.
 */
function storeProductsAddButtonClick() {
	var storeId = this.value;
	$.post(urls["store"] + storeId + "/products/add").done(function (response) {
		/** @namespace response.data.newRow */
		$("#products-table").find("tbody").prepend(response.data.newRow);
		/** @namespace response.data.newOption */
		$("#products-list").append(response.data.newOption);
		successResponse(response);
	});//.fail(errorResponse)
}
/**
 *  Function for changing the contract file.
 */
function contractInputChange() {
	var fileName = this.value;
	var text = fileName.replace(/\\/g, "/").replace(/.*\//, "");
	$("#contract-button-text").html(text);
	var $contractButton = $("#contract-button");
	$contractButton.removeClass("btn-default");
	$contractButton.addClass("btn-primary");
}
/**
 *  Function for changing the category default file.
 */
function categoriesDefaultFileInputChange() {
	var fileName = this.value;
	var categoryId = $(this).modelId();
	var text = fileName.replace(/\\/g, "/").replace(/.*\//, "");
	$("#categories-default-image-text" + categoryId).html(text);
	var $categoriesDefaultImageButton = $("#categories-default-image-button" + categoryId);
	$categoriesDefaultImageButton.removeClass("btn-default");
	$categoriesDefaultImageButton.addClass("btn-primary");
}
/**
 * Add an alert to the page.
 *
 * @param message
 * @param alertType
 * @param data
 */
function addAlert(message, alertType, data) {
	if (window.undo) {
		window.undo();
		delete window.undo;
	}
	if (message.length == 0) {
		return;
	}
	if (data) {
		/** @namespace data.redirectUrl */
		if (data.redirectUrl) {
			location.assign(data.redirectUrl);
			return;
		} else {
			$(".help-block").detach();
			$(".has-error").removeClass("has-error");
			/** @namespace data.validatorErrors */
			if (data.validatorErrors) {
				for (var name in data.validatorErrors) {
					var element = $("[name=" + name + "]");
					var parent = element.parent();
					var formGroupParent = element.parents(".form-group");
					formGroupParent.addClass("has-error");
					$(parent)
					.prepend("<span class='help-block'><strong>" + data.validatorErrors[name][0] +
					         "</strong></span>");
				}
			}
		}
	}
	var $layout = $("#layout");
	var $alertContainer = $("#alert-container");
	var alertButton = "<button type='button' onclick='alertClose();' class='close alert-close'>" +
	                  "<i class='fa fa-times'></i>";
	var alert = "<div id='alert' class='alert alert-" + alertType +
	            " alert-dismissible row' role='alert'>" +
	            "<div class='col-xs-10' id='alert-content'>" + message + "</div>" +
	            "<div class='col-xs-2 text-right'>" + alertButton + "</div></div>";
	window.originalOffset = $alertContainer.outerHeight();
	$alertContainer.html(alert);
	var $alert = $("#alert");
	if ($alert.outerWidth() != $layout.width()) {
		$alert.outerWidth($layout.width())
	}
	window.newOffset = $alertContainer.outerHeight();
	//noinspection JSValidateTypes
	if (newOffset != originalOffset && (newOffset - originalOffset) < $(window).scrollTop()) {
		//noinspection JSValidateTypes
		$(window).scrollTop($(window).scrollTop() + (newOffset - originalOffset))
	}
	$alert.alert().on("close.bs.alert", function () {
		window.originalOffset = $("#alert-container").outerHeight();
	})
	.on("closed.bs.alert", function () {
		window.newOffset = $("#alert-container").outerHeight();
		//noinspection JSValidateTypes
		if (newOffset != originalOffset && (newOffset - originalOffset) < $(window).scrollTop()) {
			//noinspection JSValidateTypes
			$(window).scrollTop($(window).scrollTop() + (newOffset - originalOffset))
		}
		windowResize();
	});
	windowResize();
}
/**
 * Add an alert that requires confirmation to the page.
 *
 * @param message
 * @param alertType
 * @param confirmFunction
 */
function addConfirmAlert(message, alertType, confirmFunction) {
	if (window.undo) {
		window.undo();
		delete window.undo;
	}
	var $layout = $("#layout");
	var confirmButton = "<button id='alert-confirm-button' type='button' class='btn btn-sm btn-" +
	                    alertType + "'>Yes</button>";
	var $alertContainer = $("#alert-container");
	var alertButton = confirmButton +
	                  "<button type='button' class='btn btn-sm btn-default alert-close'>" +
	                  "No</button>";
	var alert = "<div id='alert' class='alert alert-" + alertType +
	            " alert-dismissible row' role='alert'>" +
	            "<div class='col-xs-10' id='alert-content'>" + message + "</div>" +
	            "<div class='col-xs-2 text-right'>" + alertButton + "</div></div>";
	window.originalOffset = $alertContainer.outerHeight();
	$alertContainer.html(alert);
	var $alert = $("#alert");
	$("#alert-confirm-button").click(confirmFunction);
	if ($alert.outerWidth() != $layout.width()) {
		$alert.outerWidth($layout.width())
	}
	window.newOffset = $alertContainer.outerHeight();
	//noinspection JSValidateTypes
	if (newOffset != originalOffset && (newOffset - originalOffset) < $(window).scrollTop()) {
		//noinspection JSValidateTypes
		$(window).scrollTop($(window).scrollTop() + (newOffset - originalOffset))
	}
	$alert.alert().on("close.bs.alert", function () {
		window.originalOffset = $("#alert-container").outerHeight();
	})
	.on("closed.bs.alert", function () {
		window.newOffset = $("#alert-container").outerHeight();
		//noinspection JSValidateTypes
		if (newOffset != originalOffset && (newOffset - originalOffset) < $(window).scrollTop()) {
			//noinspection JSValidateTypes
			$(window).scrollTop($(window).scrollTop() + (newOffset - originalOffset))
		}
		windowResize();
	});
	windowResize();
}
function alertClose() {
	$("#alert").alert("close");
	if (window.undo) {
		window.undo();
		delete window.undo;
	}
}
/**
 * Get the width of the browser scrollbar
 * @return {number}
 */
function getScrollBarWidth() {
	var outer = document.createElement("div");
	outer.style.visibility = "hidden";
	outer.style.width = "100px";
	outer.style.msOverflowStyle = "scrollbar"; // needed for WinJS apps
	document.body.appendChild(outer);
	var widthNoScroll = outer.offsetWidth;
	outer.style.overflow = "scroll";
	var inner = document.createElement("div");
	inner.style.width = "100%";
	outer.appendChild(inner);
	var widthWithScroll = inner.offsetWidth;
	outer.parentNode.removeChild(outer);
	return widthNoScroll - widthWithScroll;
}
/**
 * Checks if the scrollbar is shown
 * @returns {boolean}
 */
function windowScrollbar() {
	// The Modern solution
	if (typeof window.innerWidth === "number") {
		return window.innerWidth > document.documentElement.clientWidth
	}
	// rootElem for quirksmode
	var rootElem = document.documentElement || document.body;
	// Check overflow style property on body for fauxscrollbars
	var overflowStyle;
	if (typeof rootElem.currentStyle !== "undefined") {
		overflowStyle = rootElem.currentStyle.overflow
	}
	overflowStyle = overflowStyle || window.getComputedStyle(rootElem, "").overflow;
	// Also need to check the Y axis overflow
	var overflowYStyle;
	if (typeof rootElem.currentStyle !== "undefined") {
		overflowYStyle = rootElem.currentStyle.overflowY
	}
	overflowYStyle = overflowYStyle || window.getComputedStyle(rootElem, "").overflowY;
	var contentOverflows = rootElem.scrollHeight > rootElem.clientHeight;
	var overflowShown = /^(visible|auto)$/.test(overflowStyle) ||
	                    /^(visible|auto)$/.test(overflowYStyle);
	var alwaysShowScroll = overflowStyle === "scroll" || overflowYStyle === "scroll";
	return (contentOverflows && overflowShown) || (alwaysShowScroll)
}
/**
 * Window resize handler.
 */
function windowResize() {
	
	// if (!window.counter) {
	// 	window.counter = 0;
	// }
	// window.counter++;
	// console.log(window.counter);
	$(".modal").modal("handleUpdate");
	if (!window.scrollBarWidth) {
		window.scrollBarWidth = getScrollBarWidth();
	}
	var $alertContainer = $("#alert-container");
	var $layout = $("#layout");
	var $navbar = $("#navbar");
	var $navbarContainer = $("#navbar-container");
	var $alert = $("#alert");
	var $containerScrollfix = $(".container-scrollfix");
	var hasVScroll = !$("body").hasClass("modal-open") && windowScrollbar();
	var paddingTop = $navbar.outerHeight(true) + $alert.outerHeight(true);
	
	function fixWidth() {
		var heightFix = false;
		if (window.navbarRightMargin &&
		    $navbarContainer.css("margin-right") != window.navbarRightMargin) {
			$navbarContainer.css("margin-right", window.navbarRightMargin);
			heightFix = true;
		}
		if ($containerScrollfix.length > 0 && hasVScroll &&
		    parseInt($containerScrollfix.css("padding-right")) != 0) {
			$containerScrollfix.css("padding-right", 0);
			$alertContainer.css("padding-left", window.scrollBarWidth / 2);
			heightFix = true;
		} else if ($containerScrollfix.length > 0 && !hasVScroll &&
		           parseInt($containerScrollfix.css("padding-right")) != window.scrollBarWidth) {
			$containerScrollfix.css("padding-right", window.scrollBarWidth);
			$alertContainer.css("padding-left", 0);
			heightFix = true
		}
		if ($alert.length > 0 && $alert.outerWidth() != $layout.width()) {
			$alert.outerWidth($layout.width());
			heightFix = true;
		}
		if (heightFix) {
			windowResize()
		}
	}
	
	function fixHeight() {
		if ($layout.length > 0 && (parseInt($layout.css("padding-top")) != paddingTop ||
		                           $alertContainer.length > 0 &&
		                           parseInt($alertContainer.css("top")) !=
		                           $navbar.outerHeight(true))) {
			$alertContainer.css("top", $navbar.outerHeight(true));
			$layout.css("padding-top", paddingTop);
			windowResize()
		}
	}
	
	autoFit();
	fixHeight();
	fixWidth();
}
/**
 * Sets the wanted size of the right margin of the navbar.
 */
function setNavbarMargin() {
	if (!window.navbarRightMargin) {
		window.navbarRightMargin = $("#navbar-container").css("margin-right");
	}
}
/**
 * Start firing the window resize handler until the bootstrap animation ends.
 */
function startWindowResize() {
	if (window.resizing) {
		window.clearInterval(window.resizing);
	}
	windowResize();
	window.resizing = window.setInterval(windowResize, 10);
}
/**
 * End the firing of the window resize handler.
 */
function endWindowResize() {
	window.clearInterval(window.resizing);
	windowResize();
}
