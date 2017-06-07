<?php
	
	namespace App\Http\Controllers;
	
	use App\Category;
	use App\Driver;
	use App\Image;
	use App\Order;
	use App\Product;
	use App\Store;
	use Illuminate\Foundation\Auth\ResetsPasswords;
	use Illuminate\Http\Request;
	use JavaScript;
	use Validator;
	
	
	/**
	 * Class StoreController
	 *
	 * @package App\Http\Controllers
	 */
	class StoreController extends Controller {
		
		/*
		 * |--------------------------------------------------------------------------
		 * | Store Controller
		 * |--------------------------------------------------------------------------
		 * |
		 * | This controller handles the routing for stores.
		 * |
		 */
		use ResetsPasswords;
		
		/**
		 * Current store.
		 *
		 * @var Store
		 */
		protected $store;
		
		/**
		 * Create a new controller instance.
		 */
		public function __construct() {
			$this->store = auth('store')->user();
			JavaScript::put(["urls" => ["store"        => url("store") . "/",
			                            "storehours"   => "/hours/",
			                            "storeorder"   => "/orders/",
			                            "storeproduct" => "/products/"]]);
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
			return redirect("/store/orders");
		}
		
		/**
		 * @param Request $request
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showStripeVerify(Request $request) {
			$request->session()->ageFlashData();
			return view("store.verify");
		}
		
		/**
		 * @param Request $request
		 */
		public function stripeVerify(Request $request) {
			//todo: verification of input
			$name = explode(" ", $request->name);
			$dob = explode("-", $request->birthday);
			\Stripe\Stripe::setApiKey(config("services")["stripe"]["secret"]);
			$account = \Stripe\Account::retrieve($this->store->stripe_id);
			$account->external_account = ["object"         => "bank_account",
			                              "account_number" => $request->account_number,
			                              "routing_number" => $request->routing_number,
			                              "country"        => "US",
			                              "currency"       => "usd"];
			$account->legal_entity = ["type"       => "individual",
			                          "first_name" => $name[0],
			                          "last_name"  => end($name),
			                          "dob"        => ["day" => $dob[2], "month" => $dob[1], "year" => $dob[0]]];
			$account->tos_acceptance->date = time();
			$account->tos_acceptance->ip = $_SERVER['REMOTE_ADDR'];
			$account->save();
		}
		
		/**
		 * Show the view to manage a store's current orders.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showOrders() {
			JavaScript::put(["storeId" => $this->store->id]);
			return view("store.orders", ["store" => $this->store]);
		}
		
		/**
		 * Show the view to manage a store's current orders.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showDrivers() {
			return view("store.drivers", ["store" => $this->store]);
		}
		
		/**
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeOrdersUpdate(Request $request) {
			$orders = [];
			$orders["submitted"] = $this->store->submitted->transform(function ($order) {
				return view("shared.elements.storeorderrow", ["order" => $order])->render();
			})->implode("\n");
			$orders["packed"] = $this->store->packed->transform(function ($order) {
				return view("shared.elements.storeorderrow", ["order" => $order])->render();
			})->implode("\n");
			$orders["delivering"] = $this->store->delivering->transform(function ($order) {
				return view("shared.elements.storeorderrow", ["order" => $order])->render();
			})->implode("\n");
			$orders["delivered"] = $this->store->delivered->transform(function ($order) {
				return view("shared.elements.storeorderrow", ["order" => $order])->render();
			})->implode("\n");
			$orders["cancelled"] = $this->store->cancelled->transform(function ($order) {
				return view("shared.elements.storeorderrow", ["order" => $order])->render();
			})->implode("\n");
			return successResult(null, $request, ["orders" => $orders]);
		}
		
		/**
		 * Sets the active hours of a store.
		 *
		 * @param Request $request
		 * @param         $storeId
		 * @param integer $hoursId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		function storeHoursActiveSet(Request $request, $storeId, $hoursId) {
			$store = Store::find($storeId);
			if ($store && $store = $this->store) {
				$newHours = $store->hours->find($hoursId);
				if ($newHours) {
					$store->activeHours = $newHours;
					return successResult("Active hours set to $newHours->name for store $store->name", $request);
				} else {
					return errorResult(hoursNotFound($hoursId) . " for store $store->name", $request);
				}
			} else {
				
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Add new hours to a store.
		 *
		 * @param Request $request
		 * @param         $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeHoursAdd(Request $request, $storeId) {
			$store = Store::find($storeId);
			if ($store && $store = $this->store) {
				$newHours = $store->hours()->create([]);
				$newHours = $store->hours->find($newHours->id);
				return successResult("New hours created for store $store->name",
				                     $request,
				                     ["newRow"  => view("shared.elements.hoursrow",
				                                        ["hours" => $newHours,
				                                         "store" => $store])->render(),
				                      "hoursId" => $newHours->id]);
			} else {
				
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Deletes hours from a store.
		 *
		 * @param Request $request
		 * @param         $storeId
		 * @param integer $hoursId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 * @throws \Exception
		 */
		public function storeHoursDelete(Request $request, $storeId, $hoursId) {
			$store = Store::find($storeId);
			if ($store && $store = $this->store) {
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
		 * Updates the hours for a store.
		 *
		 * @param Request $request
		 * @param         $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeHoursUpdate(Request $request, $storeId) {
			$store = Store::find($storeId);
			if ($store && $store = $this->store) {
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
			} else {
				
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Add a product to a store.
		 *
		 * @param Request $request
		 * @param         $storeId
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
			if ($store && $store = $this->store) {
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
			} else {
				
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Deletes product from a store.
		 *
		 * @param Request $request
		 * @param         $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 * @throws \Exception
		 */
		public function storeProductsDelete(Request $request, $storeId) {
			$store = Store::find($storeId);
			if ($store && $store = $this->store) {
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
		 * @param         $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeProductsDeleteAll(Request $request, $storeId) {
			$store = Store::find($storeId);
			if ($store && $store = $this->store) {
				$store->products->each(function ($item) {
					$item->delete();
				});
				return successResult("All products deleted", $request);
			} else {
				
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Updates the products for a store.
		 *
		 * @param Request $request
		 * @param         $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeProductsUpdate(Request $request, $storeId) {
			$store = Store::find($storeId);
			if ($store && $store = $this->store) {
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
			} else {
				
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Upload a csv of products for a store.
		 *
		 * @param Request $request
		 * @param         $storeId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeProductsUpload(Request $request, $storeId) {
			$store = Store::find($storeId);
			if ($store && $store = $this->store) {
				$file = $request->file("productsFile");
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
			} else {
				
				return errorResult(storeNotFound($storeId), $request);
			}
		}
		
		/**
		 * Show the view to manage a store's hours.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showStoreHours() {
			$store = $this->store;
			$hoursUrl = url("/store/$store->id/hours");
			return view("store.hours",
			            ["store"    => $store,
			             "hoursUrl" => $hoursUrl]);
		}
		
		/**
		 * Show the view to manage a store's products.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showStoreProducts() {
			$store = $this->store;
			$categories = Category::all();
			$products = $store->products->sortBy("name");
			$parentCategories = $categories->filter(function ($value) {
				return intval($value->parent_id) == 0;
			})->sortBy("id");
			$childCategories = $categories->reject(function ($value) {
				return intval($value->parent_id) == 0;
			})->sortBy("parent_id");
			$productUrl = url("/store/products");
			return view("store.products",
			            ["store"            => $store,
			             "images"           => Image::all()->sortBy("name"),
			             "childCategories"  => $childCategories,
			             "parentCategories" => $parentCategories,
			             "productUrl"       => $productUrl,
			             "products"         => $products]);
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
			if ($store && $store = $this->store) {
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
			if ($store && $store = $this->store) {
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
			if ($store && $store = $this->store) {
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
		 * Mark an order as delivered.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 * @param integer $orderId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function storeOrderDelivered(Request $request, $storeId, $orderId) {
			$store = Store::find($storeId);
			if ($store && $store = $this->store) {
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
		 * Show the form to update the store account.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showAccountUpdateForm() {
			return view("store.account",
			            ["store" => $this->store//			             "numCols" => 6
			            ]);
		}
		
		/**
		 * Updates the store account.
		 *
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse|null
		 */
		public function accountUpdate(Request $request) {
			$validator = Validator::make($request->all(),
			                             ["email"           => "email|max:255|unique:stores,email," . $this->store->id,
			                              "password"        => "confirmed",
			                              "currentPassword" => "required|password_match:store"]);
			if ($validator->fails()) {
				return errorResult("Account update failed",
				                   $request,
				                   ["validatorErrors" => $validator->errors(),
				                    "redirectUrl"     => back()->withErrors($validator)->getTargetUrl()]);
			} else {
				$this->store->update($request->except(["currentPassword", "password_confirmation"]));
				return successResult("Account updated", $request);
			}
		}
	}