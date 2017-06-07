<?php
	namespace App\Http\Controllers;
	
	use App\Admin;
	use App\Category;
	use App\Customer;
	use App\Driver;
	use App\Image;
	use App\Order;
	use App\Product;
	use App\Promo;
	use App\Store;
	use Illuminate\Foundation\Auth\ResetsPasswords;
	use Illuminate\Http\Request;
	use JavaScript;
	use Mail;
	use Storage;
	use Symfony\Component\HttpFoundation\File\File;
	use Validator;
	use ZipArchive;
	
	/**
	 * Class AdminController
	 *
	 * @package App\Http\Controllers
	 */
	class AdminController extends Controller {
		
		/*
		 * |--------------------------------------------------------------------------
		 * | Admin Controller
		 * |--------------------------------------------------------------------------
		 * |
		 * | This controller handles the routing for admins.
		 * |
		 */
		use ResetsPasswords;
		
		/**
		 * Current admin.
		 *
		 * @var Admin
		 */
		protected $admin;
		
		/**
		 * Create a new controller instance.
		 */
		public function __construct() {
			$this->admin = auth("admin")->user();
			$control = true;
			if ($this->admin) {
				$control = $this->admin->control;
			}
			view()->share("control", $control);
			JavaScript::put(["urls" => ["store"        => url("admin/stores") . "/",
			                            "driver"       => url("admin/drivers") . "/",
			                            "image"        => url("admin/images") . "/",
			                            "admin"        => url("admin/admins") . "/",
			                            "customer"     => url("admin/customers") . "/",
			                            "category"     => url("admin/categories") . "/",
			                            "promo"        => url("admin/promos") . "/",
			                            "storehours"   => "/hours/",
			                            "storeorder"   => "/orders/",
			                            "storeproduct" => "/products/"]]);
		}
		
		/**
		 * Show the form to update the admin account.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showAccountUpdateForm() {
			return view("admin.account",
			            ["admin" => $this->admin,//			             "numCols" => 6
			            ]);
		}
		
		/**
		 * Updates the admin account.
		 *
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse|null
		 */
		public function accountUpdate(Request $request) {
			$validator = Validator::make($request->all(),
			                             ["email"           => "email|max:255|unique:admins,email," . $this->admin->id,
			                              "password"        => "confirmed",
			                              "currentPassword" => "required|password_match:admin"]);
			if ($validator->fails()) {
				return errorResult("Account update failed",
				                   $request,
				                   ["validatorErrors" => $validator->errors(),
				                    "redirectUrl"     => back()->withErrors($validator)->getTargetUrl()]);
			} else {
				$this->admin->update($request->all());
				return successResult("Account updated", $request);
			}
		}
		
		/**
		 * Sets the active hours of a store.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 * @param integer $hoursId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		function storeHoursActiveSet(Request $request, $storeId, $hoursId) {
			$store = Store::find($storeId);
			if (!$store) {
				return errorResult(storeNotFound($storeId), $request);
			} else {
				$newHours = $store->hours->find($hoursId);
				if ($newHours) {
					$store->activeHours = $newHours;
					return successResult("Active hours set to $newHours->name for store $store->name", $request);
				} else {
					return errorResult(hoursNotFound($hoursId) . " for store $store->name", $request);
				}
			}
		}
		
		/**
		 * Set store status.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		function storeActiveSet(Request $request, $storeId) {
			$active = $request->active;
			$store = Store::find($storeId);
			if (!$store) {
				return errorResult(storeNotFound($storeId), $request);
			} else {
				if ($active == "true") {
					$active = 1;
					$activeName = "active";
				} else {
					$active = 0;
					$activeName = "inactive";
				}
				$store->active = $active;
				$store->save();
				return successResult("Status set to $activeName for store $store->name", $request);
			}
		}
		
		/**
		 * Set an admin's full control powers.
		 *
		 * @param Request $request
		 * @param integer $adminId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		function adminFullControlSet(Request $request, $adminId) {
			$control = $request->control;
			$admin = Admin::find($adminId);
			if (!$admin) {
				return errorResult(adminNotFound($adminId), $request);
			} else {
				if ($control == "true") {
					$control = "1";
					$controlName = "full control";
				} else {
					$control = "0";
					$controlName = "inventory manager";
				}
				$admin->control = $control;
				$admin->save();
				return successResult("Status set to $controlName for admin $admin->name", $request);
			}
		}
		
		/**
		 * Delete a driver or reject a driver applicant.
		 *
		 * @param Request $request
		 * @param integer $driverId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		function driverDelete(Request $request, $driverId) {
			$driver = Driver::find($driverId);
			if ($driver) {
				if ($request->has("redirect") && $request->redirect == "true") {
					if ($driver->confirmed) {
						$url = "admin/drivers/confirmed";
					} else {
						$url = "admin/drivers/applicants";
					}
					$data = ["redirectUrl" => url($url)];
				} else {
					$data = [];
				}
				$driverName = $driver->name;
				$driver->delete();
				return successResult("Driver $driverName deleted", $request, $data);
			} else {
				return errorResult(driverNotFound($driverId), $request);
			}
		}
		
		/**
		 * Update the stores for a driver
		 *
		 * @param Request $request
		 * @param integer $driverId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		function driverStoresUpdate(Request $request, $driverId) {
			$stores = $request->stores;
			if (!$stores) {
				$stores = [];
			}
			$stores = array_unique($stores);
			$driver = Driver::find($driverId);
			if ($driver) {
				$driver->stores()->sync($stores);
				$driver->save();
				return successResult("Driver $driver->name stores updated",
				                     $request,
				                     ["driverId" => $driverId,
				                      "stores"   => $stores]);
			} else {
				return errorResult(driverNotFound($driverId), $request);
			}
		}
		
		/**
		 * Confirm a driver applicant
		 *
		 * @param Request $request
		 * @param integer $driverId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		function driverConfirm(Request $request, $driverId) {
			$driver = Driver::find($driverId);
			if ($driver) {
				$driver->active = true;
				$driver->confirmed = true;
				$driver->save();
				Mail::send("driver.emails.confirmed",
				           ["driver" => $driver],
					function ($m) use ($driver) {
						$m->to($driver->email, $driver->name)->subject("You have been confirmed as a driver for BoozRun!");
					});
				return successResult("Driver $driver->name confirmed", $request);
			} else {
				return errorResult(driverNotFound($driverId), $request);
			}
		}
		
		/**
		 * Set the status of a driver.
		 *
		 * @param Request $request
		 * @param integer $driverId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		function driverActiveSet(Request $request, $driverId) {
			$active = $request->active;
			$driver = Driver::find($driverId);
			if (!$driver) {
				return errorResult(driverNotFound($driverId), $request);
			} else {
				if ($active == "true") {
					$active = 1;
					$activeName = "active";
				} else {
					$active = 0;
					$activeName = "inactive";
				}
				$driver->active = $active;
				$driver->save();
				return successResult("Status set to $activeName for driver $driver->name", $request);
			}
		}
		
		/**
		 * Default route.
		 *
		 * @param Request $request
		 *
		 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
		 */
		public function showIndex(Request $request) {
			$request->session()->reflash();
			return redirect("/admin/stores");
		}
		
		/**
		 * Show the manage stores view.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showStores() {
			$cities = getCities();
			$stores = Store::all();
			return view("admin.stores.stores",
			            ["stores" => $stores,
			             "cities" => $cities]);
		}
		
		/**
		 * Show the manage customers view.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showCustomers() {
			$customers = Customer::all();
			return view("admin.customers.customers",
			            ["customers" => $customers]);
		}
		
		/**
		 * Show the view to manage a store's current orders.
		 *
		 * @param integer $storeId
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showStoreOrders($storeId) {
			$store = Store::find($storeId);
			return view("admin.stores.orders", ["store" => $store]);
		}
		
		/**
		 * Show the view to manage a store's current drivers.
		 *
		 * @param integer $storeId
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showStoreDrivers($storeId) {
			$store = Store::find($storeId);
			$cities = getCities();
			$drivers = Driver::whereActive(true)->whereConfirmed(true)->get();
			$driversUrl = url("/admin/stores/$store->id/drivers");
			return view("admin.stores.drivers",
			            ["store"      => $store,
			             "drivers"    => $drivers,
			             "cities"     => $cities,
			             "driversUrl" => $driversUrl]);
		}
		
		/**
		 * Updates the hours for a store.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeHoursUpdate(Request $request, $storeId) {
			$store = Store::find($storeId);
			if (!$store) {
				return errorResult(storeNotFound($storeId), $request);
			} else {
				$store->activeHours = $store->hours->find($request->activeHours);
				$store->save();
				foreach ($request->hours as $hoursId => $hoursInfo) {
					$hours = $store->hours()->find($hoursId);
					$hours->days = $hoursInfo ["days"];
					if (isset ($hoursInfo ["name"])) {
						$hours->name = $hoursInfo ["name"];
					}
					$hours->save();
				}
				return successResult("All hours updated successfully for store $store->name", $request);
			}
		}
		
		/**
		 * Add new hours to a store.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeHoursAdd(Request $request, $storeId) {
			$store = Store::find($storeId);
			if (!$store) {
				return errorResult(storeNotFound($storeId), $request);
			} else {
				$newHours = $store->hours()->create([]);
				$newHours = $store->hours->find($newHours->id);
				return successResult("New hours created for store $store->name",
				                     $request,
				                     ["newRow"  => view("shared.elements.hoursrow",
				                                        ["hours" => $newHours,
				                                         "store" => $store])->render(),
				                      "hoursId" => $newHours->id]);
			}
		}
		
		/**
		 * Add a product to a store.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeProductsAdd(Request $request, $storeId) {
			$categories = Category::all();
			$parentCategories = $categories->filter(function ($value) {
				return intval($value->parent_id) == 0;
			})->sortBy("id");
			$childCategories = $categories->reject(function ($value) {
				return intval($value->parent_id) == 0;
			})->sortBy("parent_id");
			$store = Store::find($storeId);
			if (!$store) {
				return errorResult(storeNotFound($storeId), $request);
			} else {
				$newProduct = $store->products()->create([]);
				$newProduct = $store->products->find($newProduct->id);
				$newOption = view("shared.elements.productoption")->with("product", $newProduct)->render();
				$newRow = view("shared.elements.productrow",
				               ["product"          => $newProduct,
				                "parentCategories" => $parentCategories,
				                "childCategories"  => $childCategories,
				                "store"            => $store])->render();
				return successResult("New product created for store $store->name",
				                     $request,
				                     ["newRow"    => $newRow,
				                      "newOption" => $newOption,
				                      "productId" => $newProduct->id]);
			}
		}
		
		/**
		 * Updates the products for a store.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeProductsUpdate(Request $request, $storeId) {
			$store = Store::find($storeId);
			if (!$store) {
				return errorResult(storeNotFound($storeId), $request);
			} else {
				$message = "Products updated successfully for store $store->name";
				if (isset ($request->products)) {
					foreach ($request->products as $productId => $productInfo) {
						$product = Product::find($productId);
						if ($product) {
							if (isset ($productInfo ["categories"])) {
								$product->categories()->sync($productInfo ["categories"]);
							}
							if (isset ($productInfo ["image_id"]) && count($productInfo) == 1 &&
								count($request->products) == 1
							) {
								$message = "$product->name image updated";
							}
							if (isset($productInfo["active"])) {
								$productInfo["active"] = true;
							} else {
								$productInfo["active"] = false;
							}
							$product->update($productInfo);
						}
					}
				}
				return successResult($message, $request);
			}
		}
		
		/**
		 * Updates the products for a store.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeDriversUpdate(Request $request, $storeId) {
			$store = Store::find($storeId);
			if (!$store) {
				return errorResult(storeNotFound($storeId), $request);
			} else {
				$store->drivers()->sync($request->drivers);
				return successResult("Drivers updated successfully for store $store->name", $request);
			}
		}
		
		/**
		 * Upload a csv of products for a store.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeProductsUpload(Request $request, $storeId) {
			$store = Store::find($storeId);
			if (!$store) {
				return errorResult(storeNotFound($storeId), $request);
			} else {
				$file = $request->file("productsFile");
				/** @noinspection PhpParamsInspection */
				$products = array_map("str_getcsv", file($file->getPathname()));
				$headers = array_shift($products);
				foreach ($products as $productInfo) {
					$newProduct = [];
					foreach ($headers as $key => $value) {
						$newProduct [$value] = $productInfo [$key];
					}
					$existingProduct =
						$store->products->where("size", $newProduct["size"])->where("name", $newProduct["name"])->first();
					if ($existingProduct) {
						$existingProduct->update($newProduct);
					} else {
						$store->products()->create($newProduct);
					}
				}
				return successResult("Products uploaded successfully for store $store->name", $request);
			}
		}
		
		/**
		 * Show the view to manage a store's hours.
		 *
		 * @param integer $storeId
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showStoreHours($storeId) {
			$store = Store::find($storeId);
			$hoursUrl = url("/admin/stores/$store->id/hours");
			return view("admin.stores.hours",
			            ["store"    => $store,
			             "hoursUrl" => $hoursUrl]);
		}
		
		/**
		 * Show the view to manage a store's products.
		 *
		 * @param integer $storeId
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showStoreProducts($storeId) {
			$store = Store::find($storeId);
			$categories = Category::all();
			$products = $store->products->sortBy("name");
			$parentCategories = $categories->filter(function ($value) {
				return intval($value->parent_id) == 0;
			})->sortBy("id");
			$childCategories = $categories->reject(function ($value) {
				return intval($value->parent_id) == 0;
			})->sortBy("parent_id");
			$productUrl = url("/admin/stores/$store->id/products");
			return view("admin.stores.products",
			            ["store"            => $store,
			             "images"           => Image::all()->sortBy("name"),
			             "childCategories"  => $childCategories,
			             "parentCategories" => $parentCategories,
			             "productUrl"       => $productUrl,
			             "products"         => $products]);
		}
		
		/**
		 * Update a store's info.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeInfoUpdate(Request $request, $storeId) {
			$store = Store::find($storeId);
			$storeData = $request->store;
			$validator = $this->storeInfoUpdateValidator($storeData, $store->id);
			if ($validator->fails()) {
				return errorResult("Store $store->name update failed",
				                   $request,
				                   ["validatorErrors" => $validator->errors(),
				                    "redirectUrl"     => back()->withErrors($validator)->getTargetUrl()]);
			} else {
				$store->update($storeData);
				$contractFile = $request->file("store") ["contract"];
				if ($contractFile) {
					Storage::put("store_contracts/" . $store->id,
					             file_get_contents($contractFile->getRealPath()));
					$store->save();
				}
				return successResult("Store $store->name updated", $request);
			}
		}
		
		/**
		 * Gets the contract file for a store.
		 *
		 * @param $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse|mixed
		 */
		function storeContract($storeId) {
			$store = Store::find($storeId);
			if ($store) {
				if (Storage::exists("store_contracts/$storeId")) {
					return response()->download(storage_path("app/store_contracts/$storeId"), $store->name);
				} else {
					return errorResult("No contract found for store $storeId");
				}
			} else {
				return errorResult(storeNotFound($storeId));
			}
		}
		
		/**
		 * Get a validator for an incoming store info update request.
		 *
		 * @param array   $data
		 * @param integer $storeId
		 *
		 * @return \Illuminate\Contracts\Validation\Validator
		 */
		function storeInfoUpdateValidator(array $data, $storeId) {
			return Validator::make($data, ["email" => "email|max:255|unique:stores,email," . $storeId]);
		}
		
		/**
		 * Show view to manage store info.
		 *
		 * @param integer $storeId
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showStoreInfoForm($storeId) {
			$store = Store::find($storeId);
			return view("admin.stores.info",
			            ["store" => $store]);
			//			             ,"numCols" => 8]);
		}
		
		/**
		 * Show view to manage customer info.
		 *
		 * @param integer $customerId
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showCustomerInfoForm($customerId) {
			$customer = Customer::find($customerId);
			return view("admin.customers.info",
			            ["customer" => $customer]);
			//			,"numCols"  => 8]);
		}
		
		/**
		 * @param $customerId
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showCustomerOrders($customerId) {
			$customer = Customer::find($customerId);
			return view("admin.customers.orders",
			            ["customer" => $customer]);
			//			             ,"numCols" => 8]);
		}
		
		/**
		 * Update a driver's info.
		 *
		 * @param Request $request
		 * @param integer $driverId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function driverInfoUpdate(Request $request, $driverId) {
			$driver = Driver::find($driverId);
			if ($driver) {
				$driverData = $request->driver;
				$validator =
					Validator::make($request->all(), ["email" => "email|max:255|unique:drivers,email," . $driverId]);
				if ($validator->fails()) {
					return errorResult("Driver $driver->name update failed",
					                   $request,
					                   ["validatorErrors" => $validator->errors(),
					                    "redirectUrl"     => back()->withErrors($validator)->getTargetUrl()]);
				} else {
					$driver->update($driverData);
					if (isset($driverData["stores"])) {
						$driver->stores()->sync($driverData["stores"]);
					}
					return successResult("Driver $driver->name updated", $request);
				}
			} else {
				return errorResult(driverNotFound($driverId));
			}
		}
		
		/**
		 * Show view to manage driver info.
		 *
		 * @param integer $driverId
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showDriverInfoForm($driverId) {
			$driver = Driver::find($driverId);
			$cities = getCities();
			$stores = getActiveStores();
			JavaScript::put(["driverDeleteUrl" => url("/admin/drivers/$driver->id/delete")]);
			return view("admin.drivers.info",
			            ["driver" => $driver,
			             //			             "numCols" => 8,
			             "cities" => $cities,
			             "stores" => $stores]);
		}
		
		/**
		 * Show view to view driver tips.
		 *
		 * @param integer $driverId
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showDriverTips($driverId) {
			$driver = Driver::find($driverId);
			return view("admin.drivers.tips",
			            ["driver" => $driver]);
			//			             ,"numCols" => 8]);
		}
		
		/**
		 * Show the view to manage admins.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showAdmins() {
			$superAdmin = $this->admin->id <= 2;
			JavaScript::put(["superAdmin" => $superAdmin]);
			$admins = Admin::all();
			return view("admin.admins.admins",
			            ["admins"       => $admins,
			             "currentAdmin" => $this->admin,
			             "superAdmin"   => $superAdmin]);
		}
		
		/**
		 * Show the view to add a new admin.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showAdminAddForm() {
			return view("admin.admins.add");
			//			["numCols" => 8]);
		}
		
		/**
		 * Add a new admin.
		 *
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function adminAdd(Request $request) {
			$adminData = $request->admin;
			$validator = Validator::make($adminData, ["email" => "email|max:255|unique:admins"]);
			if ($validator->fails()) {
				return errorResult("Admin creation failed",
				                   $request,
				                   ["validatorErrors" => $validator->errors(),
				                    "redirectUrl"     => back()->withInput($adminData)->withErrors($validator)
				                                               ->getTargetUrl()]);
			} else {
				$admin = Admin::create($adminData);
				Mail::send("admin.emails.welcome",
				           [],
					function ($m) use ($admin) {
						$m->to($admin->email, $admin->name)->subject("Welcome to BoozRun!");
					});
				return successResult("Admin $admin->name created", $request, ["redirectUrl" => url("admin/admins")]);
			}
		}
		
		/**
		 * Show view to manage confirmed drivers.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showDrivers() {
			return view("admin.drivers.confirmed",
			            ["drivers" => Driver::whereConfirmed("1")->get(),
			             "cities"  => getCities(),
			             "stores"  => getActiveStores()]);
		}
		
		/**
		 * Show view to manage driver applicants.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showDriverApplicants() {
			$cities = getCities();
			$drivers = Driver::where("confirmed", "0")->get();
			return view("admin.drivers.applicants",
			            ["drivers" => $drivers,
			             "cities"  => $cities]);
		}
		
		/**
		 * Deletes a store.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeDelete(Request $request, $storeId) {
			$store = Store::find($storeId);
			if ($store) {
				if ($request->has("redirect") && $request->redirect == "true") {
					$data = ["redirectUrl" => url("admin/stores")];
				} else {
					$data = [];
				}
				$store->delete();
				return successResult("Store $store->name deleted", $request, $data);
			} else {
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Deletes an admin.
		 *
		 * @param Request $request
		 * @param integer $adminId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function adminDelete(Request $request, $adminId) {
			$admin = Admin::find($adminId);
			if ($admin) {
				if ($this->admin->id == $adminId) {
					$data = ["redirectUrl" => url("admin/admins")];
				} else {
					$data = [];
				}
				$admin->delete();
				return successResult("Admin $admin->name deleted", $request, $data);
			} else {
				return errorResult(adminNotFound($adminId), $request);
			}
		}
		
		/**
		 * Deletes an customer.
		 *
		 * @param Request $request
		 * @param integer $customerId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function customerDelete(Request $request, $customerId) {
			$customer = Customer::find($customerId);
			if ($customer) {
				if ($request->has("redirect") && $request->redirect == "true") {
					$data = ["redirectUrl" => url("admin/customers")];
				} else {
					$data = [];
				}
				
				$customer->delete();
				return successResult("Customer $customer->name deleted", $request, $data);
			} else {
				return errorResult(customerNotFound($customerId), $request);
			}
		}
		
		/**
		 * Deletes hours from a store.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 * @param integer $hoursId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeHoursDelete(Request $request, $storeId, $hoursId) {
			$store = Store::find($storeId);
			if ($store) {
				$hours = $store->hours->find($hoursId);
				if ($hours) {
					$needNewActiveHours = false;
					$hoursName = $hours->name;
					if ($hours->active) {
						$needNewActiveHours = true;
					}
					$hours->delete();
					if ($needNewActiveHours) {
						$defaultHours = $store->hours->where("name", "Default")->first();
						$defaultHours->active = true;
						$defaultHours->save();
						$data = ["activeId" => $store->activeHours->id];
					} else {
						$data = [];
					}
					return successResult("Hours $hoursName deleted", $request, $data);
				} else {
					return errorResult(hoursNotFound($hoursId), $request);
				}
			} else {
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Cancel an order.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 * @param integer $orderId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeOrderCancel(Request $request, $storeId, $orderId) {
			$store = Store::find($storeId);
			if ($store) {
				$order = Order::find($orderId);
				if ($order) {
					$order->cancel();
					$order->save();
					$timeString = $order->cancelled_at->day != $order->submitted_at->day ?
						$order->cancelled_at->format("M j, Y g:i a") : $order->cancelled_at->format("g:i a");
					
					return successResult("Order cancelled", $request, ["time" => $timeString]);
				} else {
					return errorResult(orderNotFound($orderId), $request);
				}
			} else {
				
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Mark an order as ready for pickup.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 * @param integer $orderId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeOrderPacked(Request $request, $storeId, $orderId) {
			$store = Store::find($storeId);
			if ($store) {
				$order = Order::find($orderId);
				if ($order) {
					$order->packed();
					return successResult("Order marked as ready for pickup",
					                     $request,
					                     ["time" => $order->packed_at->format("g:i a")]);
				} else {
					return errorResult(orderNotFound($orderId), $request);
				}
			} else {
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Mark an order as out for delivery.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 * @param integer $orderId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeOrderDelivering(Request $request, $storeId, $orderId) {
			$store = Store::find($storeId);
			if ($store) {
				$order = Order::find($orderId);
				if ($order) {
					$driver = Driver::find($request->driverId);
					if ($driver) {
						$order->delivering($driver);
						return successResult("Order marked as out for delivery by $driver->name.",
						                     $request,
						                     ["driverName" => $driver->name,
						                      "time"       => $order->delivering_at->format("g:i a")]);
					} else {
						return errorResult(driverNotFound($request->driverId));
					}
				} else {
					return errorResult(orderNotFound($orderId), $request);
				}
			} else {
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Mark an order as out for delivery.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 * @param integer $orderId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeOrderDelivered(Request $request, $storeId, $orderId) {
			$store = Store::find($storeId);
			if ($store) {
				$order = Order::find($orderId);
				if ($order) {
					$order->delivered();
					$driver = $order->driver;
					return successResult("Order marked as delivered by $driver->name.",
					                     $request,
					                     ["time" => $order->delivered_at->format("g:i a")]);
				} else {
					return errorResult(orderNotFound($orderId), $request);
				}
			} else {
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function promosAdd(Request $request) {
			$promo = Promo::whereCode($request->code)->first();
			
			if ($promo) {
				$promo->update($request->except(["stores", "_token"]));
				$message = "Promo $promo->code updated";
			} else {
				$promo = Promo::create($request->all());
				$message = "Promo $promo->code created";
			}
			return successResult($message,
			                     $request,
			                     ["newRow"  => view("shared.elements.promorow",
			                                        ["promo" => $promo])->render(),
			                      "promoId" => $promo->id]);
		}
		
		/**
		 * @param Request $request
		 * @param         $promoId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function promosDelete(Request $request, $promoId) {
			$promo = Promo::find($promoId);
			if ($promo) {
				$promo->delete();
				return successResult("Promo $promo->code deleted");
			} else {
				return errorResult(promoNotFound($promoId), $request);
			}
		}
		
		/**
		 * Deletes product from a store.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeProductsDelete(Request $request, $storeId) {
			$store = Store::find($storeId);
			if ($store) {
				$productId = $request->productId;
				$product = $store->products->find($productId);
				if ($product) {
					$productName = $product->name;
					$product->delete();
					return successResult("Product $productName deleted", $request);
				} else {
					return errorResult(productNotFound($productId), $request);
				}
			} else {
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Deletes all products from a store.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeProductsDeleteAll(Request $request, $storeId) {
			$store = Store::find($storeId);
			if ($store) {
				$store->products->each(function ($item) {
					$item->delete();
				});
				return successResult("All products deleted", $request);
			} else {
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Show the form to add a new store.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showStoreAddForm() {
			return view("admin.stores.add");
			//			, ["numCols" => 8]);
		}
		
		/**
		 * Add a new store.
		 *
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeAdd(Request $request) {
			$storeData = $request->store;
			$validator = Validator::make($storeData, ["email" => "email|max:255|unique:stores"]);
			if ($validator->fails()) {
				return errorResult("Store creation failed",
				                   $request,
				                   ["validatorErrors" => $validator->errors(),
				                    "redirectUrl"     => back()->withInput($storeData)->withErrors($validator)
				                                               ->getTargetUrl()]);
			} else {
				$store = Store::create($storeData);
				$password = generatePassword(8);
				$store->password = $password;
				$store->save();
				if ($storeData["contract"]) {
					$contractFile = $request->file("store") ["contract"];
					Storage::put("store_contracts/" . $store->id,
					             file_get_contents($contractFile->getRealPath()));
					$store->save();
				}
				Mail::send("store.emails.welcome",
				           ["store"    => $store,
				            "password" => $password],
					function ($m) use ($store) {
						$m->to($store->email, $store->owner_name)->subject("Welcome to BoozRun!");
					});
				return successResult("Store $store->name created", $request, ["redirectUrl" => url("admin/stores")]);
			}
		}
		
		/**
		 * Show the view to manage categories.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showCategories() {
			$categories = Category::where("parent_id", "0")->get()->sortBy("id");
			return view("admin.categories", ["categories" => $categories]);
		}
		
		/**
		 * Show the view to manage promos.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showPromos() {
			return view("admin.promos",
			            ["promos" => Promo::all(),
			             "stores" => getActiveStores()]);
		}
		
		/**
		 * Update categories.
		 *
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function categoriesUpdate(Request $request) {
			$categoryInfos = $request->categories;
			foreach ($categoryInfos as $categoryId => $categoryInfo) {
				$category = Category::find($categoryId);
				$category->name = $categoryInfo ["name"];
				if ($request->hasFile("file" . $categoryId)) {
					$file = $request->file("file" . $categoryId);
					//					$filePathName = $file->getPathname();
					//					$fileOriginalExtension = $file->getClientOriginalExtension();
					//					$fileOriginalName = $file->getClientOriginalName();
					Image::createFromFile($file, strtolower("default" . $category->name), true);
					//					createImage($fileOriginalExtension,
					//					            $fileOriginalName,
					//					            $filePathName,
					//					            strtolower("default" . $category->name),
					//					            true);
				}
				$category->save();
			}
			return successResult("Categories updated", $request);
		}
		
		/**
		 * Add a new category.
		 *
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function categoriesCategoryAdd(Request $request) {
			$newCategory = Category::create([]);
			return successResult("New category created",
			                     $request,
			                     ["newDiv"     => view("shared.elements.categorydiv")->with("category", $newCategory)
			                                                                         ->render(),
			                      "categoryId" => $newCategory->id]);
		}
		
		/**
		 * Add a new subcategory.
		 *
		 * @param Request $request
		 * @param         $categoryId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function categoriesSubcategoryAdd(Request $request, $categoryId) {
			
			$category = Category::find($categoryId);
			if ($category) {
				$newCategory = $category->children()->create([]);
				return successResult("New subcategory created for $category->name",
				                     $request,
				                     ["newLi"      => view("shared.elements.subcategoryli")
					                     ->with("subcategory", $newCategory)->render(),
				                      "categoryId" => $newCategory->id]);
			} else {
				return errorResult(categoryNotFound($request->categoryId));
			}
		}
		
		/**
		 * Delete a category.
		 *
		 * @param Request $request
		 * @param         $categoryId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 * @throws \Exception
		 */
		public function categoriesDelete(Request $request, $categoryId) {
			$category = Category::find($categoryId);
			if ($category) {
				$categoryId = $category->id;
				$categoryName = $category->name;
				if (!$category->parent) {
					foreach ($category->children as $subcategory) {
						$subcategory->products()->detach();
						$subcategory->delete();
					}
					$message = "Category $categoryName and all subcategories deleted";
				} else {
					$message = "Category $categoryName deleted";
				}
				$category->products()->detach();
				$category->delete();
				return successResult($message, $request, ["categoryId" => $categoryId]);
			} else {
				return errorResult(categoryNotFound($request->categoryId));
			}
		}
		
		/**
		 * Show the view to manage images.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showImages() {
			return view("admin.images", ["images" => Image::all()->sortBy("name")]);
		}
		
		/**
		 * Upload images.
		 *
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function imagesUpload(Request $request) {
			$count = 0;
			foreach ($request->files as $files) {
				foreach ($files as $file) {
					debug($file);
					$valid = $file->isValid();
					preg_match("/image/", $file->getMimeType(), $matches);
					if ($matches && $valid) {
						Image::createFromFile($file);
						//						$filePathName = $file->getPathname();
						//						$fileOriginalExtension = $file->getClientOriginalExtension();
						//						$fileOriginalName = $file->getClientOriginalName();
						//						createImage($fileOriginalExtension, $fileOriginalName, $filePathName);
						$count++;
					} elseif ($valid && $file->getMimeType() == "application/zip") {
						$zip = new ZipArchive ();
						if ($zip->open($file->getPathname()) === true) {
							$zip->extractTo($file->getPath());
							for ($i = 0; $i < $zip->numFiles; $i++) {
								$filename = $zip->getNameIndex($i);
								$zippedFile = new File ($file->getPath() . "/" . $filename);
								preg_match("/image/", $zippedFile->getMimeType(), $matches);
								if ($matches) {
									Image::createFromFile($zippedFile);
									//									$filePathName = $zippedFile->getPathname();
									//									$fileOriginalExtension = $zippedFile->getExtension();
									//									$fileOriginalName = $zippedFile->getFilename();
									//									createImage($fileOriginalExtension, $fileOriginalName, $filePathName);
									$count++;
								}
							}
						}
					}
				}
			}
			if ($count > 0) {
				return successResult("Images uploaded", $request);
			} else {
				return errorResult("No valid image files uploaded", $request);
			}
		}
		
		/**
		 * Update images.
		 *
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function imagesUpdate(Request $request) {
			if (isset ($request->images)) {
				foreach ($request->images as $imageId => $imageInfo) {
					$image = Image::find($imageId);
					if ($image->name != $imageInfo["name"]) {
						$image->update($imageInfo);
					}
				}
			}
			return successResult("Images updated (refresh to view changed files)", $request);
		}
		
		/**
		 * Delete an image.
		 *
		 * @param Request $request
		 * @param         $imageId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 * @throws \Exception
		 */
		public function imagesDelete(Request $request, $imageId) {
			$image = Image::find($imageId);
			if ($image) {
				$imageName = $image->name;
				$image->delete();
				return successResult("Image $imageName deleted", $request);
			} else {
				return errorResult(imageNotFound($imageId), $request);
			}
		}
	}
