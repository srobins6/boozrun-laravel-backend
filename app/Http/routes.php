<?php
	/*
	 * |--------------------------------------------------------------------------
	 * | Routes File
	 * |--------------------------------------------------------------------------
	 * |
	 * | Here is where you will register all of the routes in an application.
	 * | It's a breeze. Simply tell Laravel the URIs it should respond to
	 * | and give it the controller to call when that URI is requested.
	 * |
	 */
	/*
	 * |--------------------------------------------------------------------------
	 * | Application Routes
	 * |--------------------------------------------------------------------------
	 * |
	 * | This route group applies the 'web' middleware group to every route
	 * | it contains. The 'web' middleware group is defined in your HTTP
	 * | kernel and includes session state, CSRF protection, and more.
	 * |
	 */
	/**
	 * Test routing info
	 */
	Route::group(['middleware' => 'web'],
		function () {
			Route::any('/test', 'TestController@test');
			//			Route::get('/flashtest', 'TestController@flashtest');
			//			Route::get('/spawn', 'TestController@spawn');
			//			Route::get('/truncate', 'TestController@truncate');
		});
	/**
	 * Customer routing info
	 */
	Route::group(['middleware' => 'web'],
		function () {
			// Login
			Route::get('login', 'Auth\CustomerAuthController@showLoginForm');
			Route::post('login', 'Auth\CustomerAuthController@login');
			Route::get('logout', 'Auth\CustomerAuthController@logout');
			// Registration Routes...
			Route::get('signup', 'Auth\CustomerAuthController@showRegistrationForm');
			Route::post('signup', 'Auth\CustomerAuthController@register');
			// Password Reset Routes...
			Route::get('password/reset/{token?}',
			           'Auth\CustomerPasswordController@showResetForm');
			Route::post('password/email', 'Auth\CustomerPasswordController@sendResetLinkEmail');
			Route::post('password/reset', 'Auth\CustomerPasswordController@reset');
			Route::post('findstore', 'CustomerController@findStore');
			Route::get('findstore', 'CustomerController@showFindStore');
		});
	Route::group(['middleware' => ['web',
	                               'auth:customer']],
		function () {
			Route::get('account', 'CustomerController@showAccountUpdateForm');
			Route::post('account', 'CustomerController@accountUpdate');
		});
	Route::group(['middleware' => ['web',
	                               'shopping']],
		function () {
			Route::get('/', 'CustomerController@showIndex');
			Route::post('/orderadd', 'CustomerController@orderAddItem');
		});
	
	Route::group(['middleware' => ['web',
	                               'shopping',
	                               'auth:customer']],
		function () {
		});
	/**
	 * Driver routing info
	 */
	Route::group(['middleware' => 'web'],
		function () {
			// Login
			Route::get('driver/login', 'Auth\DriverAuthController@showLoginForm');
			Route::post('driver/login', 'Auth\DriverAuthController@login');
			Route::get('driver/logout', 'Auth\DriverAuthController@logout');
			// Application Routes
			Route::get('driver/apply', 'Auth\DriverAuthController@showRegistrationForm');
			Route::post('driver/apply', 'Auth\DriverAuthController@register');
			// Password Reset Routes...
			Route::get('driver/password/reset/{token?}', 'Auth\DriverPasswordController@showResetForm');
			Route::post('driver/password/email', 'Auth\DriverPasswordController@sendResetLinkEmail');
			Route::post('driver/password/reset', 'Auth\DriverPasswordController@reset');
		});
	Route::group(['middleware' => ['web', 'auth:driver']],
		function () {
			// Home
			Route::get('driver', 'DriverController@showIndex');
			// Account
			Route::get('driver/account', 'DriverController@showAccountUpdateForm');
			Route::post('driver/account', 'DriverController@accountUpdate');
			// Orders
			Route::get('driver/orders', 'DriverController@showOrders');
			Route::get('driver/orders/history', 'DriverController@showOrderHistory');
			Route::post('driver/{storeId}/orders/{orderId}/cancel', 'DriverController@driverOrderCancel');
			Route::post('driver/{storeId}/orders/{orderId}/delivering', 'DriverController@driverOrderDelivering');
			Route::post('driver/{storeId}/orders/{orderId}/delivered', 'DriverController@driverOrderDelivered');
			Route::post('driver/{storeId}/orders/update', 'DriverController@driverOrdersUpdate');
			
			// Tips
			Route::get('driver/tips', 'DriverController@showTips');
		});
	
	/**
	 * Store routing info
	 */
	Route::group(['middleware' => 'web'],
		function () {
			// Login
			Route::get('store/login', 'Auth\StoreAuthController@showLoginForm');
			Route::post('store/login', 'Auth\StoreAuthController@login');
			Route::get('store/logout', 'Auth\StoreAuthController@logout');
			// Password Reset
			Route::get('store/password/reset/{token?}',
			           'Auth\StorePasswordController@showResetForm');
			Route::post('store/password/email',
			            'Auth\StorePasswordController@sendResetLinkEmail');
			Route::post('store/password/reset', 'Auth\StorePasswordController@reset');
		});
	Route::group(['middleware' => ['web', 'auth:store']],
		function () {
			Route::get('store/verify', 'StoreController@showStripeVerify');
			Route::post('store/verify', 'StoreController@stripeVerify');
		});
	Route::group(['middleware' => ['web', 'auth:store', 'verified']],
		function () {
			// Index
			Route::get('store/', 'StoreController@showIndex');
			// Orders
			Route::get('store/orders', 'StoreController@showOrders');
			Route::post('store/{storeId}/orders/{orderId}/packed', 'StoreController@storeOrderPacked');
			Route::post('store/{storeId}/orders/{orderId}/delivering', 'StoreController@storeOrderDelivering');
			Route::post('store/{storeId}/orders/{orderId}/delivered', 'StoreController@storeOrderDelivered');
			Route::post('store/{storeId}/orders/{orderId}/cancel', 'StoreController@storeOrderCancel');
			Route::get('store/{storeId}/orders/update', 'StoreController@storeOrdersUpdate');
			// Account Management
			Route::get('store/account', 'StoreController@showAccountUpdateForm');
			Route::post('store/account', 'StoreController@accountUpdate');
			// Store Products
			Route::get('store/products', 'StoreController@showStoreProducts');
			Route::post('store/{storeId}/products/add', 'StoreController@storeProductsAdd');
			Route::post('store/{storeId}/products/{productId}/delete', 'StoreController@storeProductsDelete');
			Route::post('store/{storeId}/products/deleteall', 'StoreController@storeProductsDeleteAll');
			Route::post('store/{storeId}/products/update', 'StoreController@storeProductsUpdate');
			Route::post('store/{storeId}/products/upload', 'StoreController@storeProductsUpload');
			// Store Hours
			Route::get('store/hours', 'StoreController@showStoreHours');
			Route::post('store/{storeId}/hours/update', 'StoreController@storeHoursUpdate');
			Route::post('store/{storeId}/hours/add', 'StoreController@storeHoursAdd');
			Route::post('store/{storeId}/hours/{hoursId}/delete', 'StoreController@storeHoursDelete');
			Route::post('store/{storeId}/hours/{hoursId}/active', 'StoreController@storeHoursActiveSet');
			// Drivers
			Route::get('store/drivers', 'StoreController@showDrivers');
		});
	/**
	 * Admin routing info
	 */
	Route::group(['middleware' => 'web'],
		function () {
			// Login
			Route::get('admin/login', 'Auth\AdminAuthController@showLoginForm');
			Route::post('admin/login', 'Auth\AdminAuthController@login');
			Route::get('admin/logout', 'Auth\AdminAuthController@logout');
			// Password Reset
			Route::get('admin/password/reset/{token?}', 'Auth\AdminPasswordController@showResetForm');
			Route::post('admin/password/email', 'Auth\AdminPasswordController@sendResetLinkEmail');
			Route::post('admin/password/reset', 'Auth\AdminPasswordController@reset');
		});
	Route::group(['middleware' => ['web', 'auth:admin']],
		function () {
			// Routes available to inventory managers
			Route::get('admin/', 'AdminController@showIndex');
			Route::get('admin/stores', 'AdminController@showStores');
			// Store Products
			Route::get('admin/stores/{storeId}/products', 'AdminController@showStoreProducts');
			Route::post('admin/stores/{storeId}/products/add', 'AdminController@storeProductsAdd');
			Route::post('admin/stores/{storeId}/products/{productId}/delete', 'AdminController@storeProductsDelete');
			Route::post('admin/stores/{storeId}/products/deleteall', 'AdminController@storeProductsDeleteAll');
			Route::post('admin/stores/{storeId}/products/update', 'AdminController@storeProductsUpdate');
			Route::post('admin/stores/{storeId}/products/upload', 'AdminController@storeProductsUpload');
			
			// Account
			Route::get('admin/account', 'AdminController@showAccountUpdateForm');
			Route::post('admin/account', 'AdminController@accountUpdate');
		});
	Route::group(['middleware' => ['web', 'auth:admin', 'control']],
		function () {
			// Images
			Route::get('admin/images', 'AdminController@showImages');
			Route::post('admin/images/update', 'AdminController@imagesUpdate');
			Route::post('admin/images/upload', 'AdminController@imagesUpload');
			Route::post('admin/images/{imageId}/delete', 'AdminController@imagesDelete');
			// Categories
			Route::get('admin/categories', 'AdminController@showCategories');
			Route::post('admin/categories/update', 'AdminController@categoriesUpdate');
			Route::post('admin/categories/addcategory', 'AdminController@categoriesCategoryAdd');
			Route::post('admin/categories/{categoryId}/addsubcategory', 'AdminController@categoriesSubcategoryAdd');
			Route::post('admin/categories/{categoryId}/delete', 'AdminController@categoriesDelete');
			//Promos
			Route::get('admin/promos', 'AdminController@showPromos');
			Route::post('admin/promos/add', 'AdminController@promosAdd');
			Route::post('admin/promos/{promoId}/delete', 'AdminController@promosDelete');
			
			// Admins
			Route::get('admin/admins', 'AdminController@showAdmins');
			Route::post('admin/admins/{adminId}/delete', 'AdminController@adminDelete');
			Route::get('admin/admins/add', 'AdminController@showAdminAddForm');
			Route::post('admin/admins/add', 'AdminController@adminAdd');
			Route::post('admin/admins/{adminId}/fullcontrol', 'AdminController@adminFullControlSet');
			// Customers
			Route::get('admin/customers/', 'AdminController@showCustomers');
			Route::post('admin/customers/{customerId}/delete', 'AdminController@customerDelete');
			Route::get('admin/customers/{customerId}/info', 'AdminController@showCustomerInfoForm');
			Route::post('admin/customers/{customerId}/update', 'AdminController@customerInfoUpdate');
			Route::get('admin/customers/{customerId}/orders', 'AdminController@showCustomerOrders');
			// Drivers
			Route::get('admin/drivers/confirmed', 'AdminController@showDrivers');
			Route::get('admin/drivers/applicants', 'AdminController@showDriverApplicants');
			Route::post('admin/drivers/{driverId}/active', 'AdminController@driverActiveSet');
			Route::post('admin/drivers/{driverId}/delete', 'AdminController@driverDelete');
			Route::post('admin/drivers/{driverId}/confirm', 'AdminController@driverConfirm');
			Route::post('admin/drivers/{driverId}/stores', 'AdminController@driverStoresUpdate');
			Route::post('admin/drivers/{driverId}/active', 'AdminController@driverActiveSet');
			Route::get('admin/drivers/{driverId}/info', 'AdminController@showDriverInfoForm');
			Route::get('admin/drivers/{driverId}/tips', 'AdminController@showDriverTips');
			
			Route::post('admin/drivers/{driverId}/update', 'AdminController@driverInfoUpdate');
			// Stores
			Route::post('admin/stores/{storeId}/delete', 'AdminController@storeDelete');
			Route::post('admin/stores/{storeId}/active', 'AdminController@storeActiveSet');
			Route::get('admin/stores/{storeId}/info', 'AdminController@showStoreInfoForm');
			Route::get('admin/stores/{storeId}/contract', 'AdminController@storeContract');
			
			Route::post('admin/stores/{storeId}/update', 'AdminController@storeInfoUpdate');
			Route::get('admin/stores/add', 'AdminController@showStoreAddForm');
			Route::post('admin/stores/add', 'AdminController@storeAdd');
			// Store Hours
			Route::get('admin/stores/{storeId}/hours', 'AdminController@showStoreHours');
			Route::post('admin/stores/{storeId}/hours/update', 'AdminController@storeHoursUpdate');
			Route::post('admin/stores/{storeId}/hours/add', 'AdminController@storeHoursAdd');
			Route::post('admin/stores/{storeId}/hours/{hoursId}/delete', 'AdminController@storeHoursDelete');
			Route::post('admin/stores/{storeId}/hours/{hoursId}/active', 'AdminController@storeHoursActiveSet');
			// Store Orders
			Route::get('admin/stores/{storeId}/orders', 'AdminController@showStoreOrders');
			Route::post('admin/stores/{storeId}/orders/{orderId}/packed', 'AdminController@storeOrderPacked');
			Route::post('admin/stores/{storeId}/orders/{orderId}/delivering', 'AdminController@storeOrderDelivering');
			Route::post('admin/stores/{storeId}/orders/{orderId}/delivered', 'AdminController@storeOrderDelivered');
			Route::post('admin/stores/{storeId}/orders/{orderId}/cancel', 'AdminController@storeOrderCancel');
			// Store Drivers
			Route::get('admin/stores/{storeId}/drivers', 'AdminController@showStoreDrivers');
			Route::post('admin/stores/{storeId}/drivers/update', 'AdminController@storeDriversUpdate');
		});
	/**
	 * Backwards compatibility for current iPhone API calls
	 */
	Route::group(['middleware' => 'api'],
		function () {
			// Route::match(array('GET', 'POST'), 'api/getLocalStores/test', array('uses' => 'ApiController@getLocalStoresTest'));
			Route::any('api/storegetlocalstores', 'OldApiController@storeGetLocalStores');
			// Route::match(array('GET', 'POST'), 'api/login', array('uses' => 'ApiController@validateLoginOld'));
			Route::any('api/customerlogin', 'OldApiController@customerLogin');
			// Route::match(array('GET', 'POST'), 'api/signup/test', array('uses' => 'ApiController@signUpTest'));
			Route::any('api/customersignup', 'OldApiController@customerSignup');
			Route::any('api/customerpasswordreset', 'OldApiController@customerPasswordReset');
			
//			Route::any('api/old', function () { });
			// Route::get('api/users/address/{email}', array('uses' => 'ApiController@lastDeliveredAddress'));
			Route::get('api/customergetlastaddress/{customerId}', 'OldApiController@customerGetLastAddress');
			// Route::match(array('GET', 'POST'), 'api/getUserProfile', array('uses' => 'ApiController@getUserProfile'));
			Route::any('api/customergetprofile', 'OldApiController@customerGetProfile');
			// Route::match(array('GET', 'POST'), 'api/updateProfile', array('uses' => 'ApiController@updateProfile'));
			Route::any('api/customerupdateprofile', 'OldApiController@customerUpdateProfile');
			// Route::match(array('GET', 'POST'), 'api/getProducts', array('uses' => 'ApiController@getAllProductsStore'));
			Route::any('api/storegetproducts', 'OldApiController@storeGetProducts');
			// Route::match(array('GET', 'POST'), 'api/GetProductByCategory', array('uses' => 'ApiController@getProductByCategory'));
			Route::any('api/storegetproductsbycategory', 'OldApiController@storeGetProductsByCategory');
			// Route::match(array('GET', 'POST'), 'api/GetProductBySubCategory', array('uses' => 'ApiController@getProductBySubCategory'));
			Route::any('api/storegetproductsbysubcategory', 'OldApiController@storeGetProductsBySubcategory');
			// Route::match(array('GET', 'POST'), 'api/GetShoppingCart', array('uses' => 'ApiController@getShoppingCart'));
			Route::any('api/orderget', 'OldApiController@orderGet');
			// Route::match(array('GET', 'POST'), 'api/CreateShoppingCart', array('uses' => 'ApiController@createShoppingCart'));
			Route::any('api/ordercreate', 'OldApiController@orderCreate');
			// Route::match(array('GET', 'POST'), 'api/RemoveShoppingCart', array('uses' => 'ApiController@removeShoppingCart'));
			Route::any('api/orderdelete', 'OldApiController@orderDelete');
			// Route::match(array('GET', 'POST'), 'api/AddShoppingCart', array('uses' => 'ApiController@addShoppingCart'));
			Route::any('api/orderadditem', 'OldApiController@orderAddItem');
			// Route::match(array('GET', 'POST'), 'api/removeItemInCart', array('uses' => 'ApiController@removeItemInCart'));
			Route::any('api/orderremoveitem', 'OldApiController@orderRemoveItem');
			// Route::match(array('GET', 'POST'), 'api/replaceItemInCart', array('uses' => 'ApiController@replaceItemInCart'));
			Route::any('api/orderupdateitem', 'OldApiController@orderUpdateItem');
			// Route::match(array('GET', 'POST'), 'api/PlaceOrder/test', array('uses' => 'ApiController@placeOrderTest'));
			Route::any('api/ordersubmit', 'OldApiController@orderSubmit');
			// Route::match(array('GET', 'POST'), 'api/getStoreTiming', array('uses' => 'ApiController@getStoreTimeing'));
			Route::any('api/storegethours', 'OldApiController@storeGetHours');
			Route::any('api/promoverify', 'OldApiController@promoVerify');
		});


