<?php
	namespace App\Http\Controllers;
	
	use App\Address;
	use App\Category;
	use App\Customer;
	use App\Order;
	use App\Product;
	use App\Store;
	use Illuminate\Foundation\Auth\ResetsPasswords;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Auth;
	use Validator;
	
	/**
	 * Class CustomerController
	 *
	 * @package App\Http\Controllers
	 */
	class CustomerController extends Controller {
		
		/*
		 * |--------------------------------------------------------------------------
		 * | Customer Controller
		 * |--------------------------------------------------------------------------
		 * |
		 * | This controller handles the routing for customers.
		 * |
		 */
		use ResetsPasswords;
		
		/**
		 * Current customer.
		 *
		 * @var Customer
		 */
		protected $customer;
		
		/**
		 * Create a new controller instance.
		 *
		 * @param Request $request
		 */
		public function __construct(Request $request) {
			$this->customer = Auth::user();
			if ($request->session()->has("currentStore")) {
				$store = Store::find($request->session()->get("currentStore"));
				view()->share("currentStore", $store);
				view()->share("productNames", $store->products->pluck("name")->unique()->sort()->toArray());
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
			if ($request->has("storeId")) {
				$storeId = $request->storeId;
			} else {
				$storeId = $request->session()->get("currentStore");
			}
			return $this->showStoreProducts($request, $storeId);
		}
		
		/**
		 * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showFindStore() {
			$view = view("customer.findstore");
			
			if ($this->customer && $this->customer->address) {
				$view = $view->with("address", $this->customer->address);
			}
			return $view;
		}
		
		/**
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function findStore(Request $request) {
			$address = Address::firstOrCreateFromInput($request->address);
			if ($address) {
				$stores = getLocalStores($address->latitude, $address->longitude, 4, false);
				if (!$stores->isEmpty()) {
					// todo: let customer select which store to use
					$request->session()->put("currentStore", $stores->first()->id);
					return redirect("/");
				} else {
					return errorResult("No stores found for this address, please try again", $request);
				}
			} else {
				return errorResult("Address not found, please try again.", $request);
			}
		}
		
		/**
		 * Show the form to update the customer account.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showAccountUpdateForm() {
			return view("customer.account",
			            ["customer" => $this->customer,//			             "numCols" => 6
			            ]);
		}
		
		/**
		 * Updates the customer account.
		 *
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse|null
		 */
		public function accountUpdate(Request $request) {
			$validator = Validator::make($request->all(),
			                             ["email"           => "email|max:255|unique:customers,email," . $this->customer->id,
			                              "password"        => "confirmed",
			                              "currentPassword" => "required|password_match:admin"]);
			if ($validator->fails()) {
				return errorResult("Account update failed",
				                   $request,
				                   ["validatorErrors" => $validator->errors(),
				                    "redirectUrl"     => back()->withErrors($validator)->getTargetUrl()]);
			} else {
				$this->customer->update($request->all());
				return successResult("Account updated", $request);
			}
		}
		
		/**
		 * @param Request $request
		 * @param         $storeId
		 *
		 * @return \App\Result|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
		 */
		public function showStoreProducts(Request $request, $storeId) {
			$store = Store::find($storeId);
			$products = $store->products;
			$title = "All Products";
			if ($request->has("category")) {
				$category = Category::find($request->category);
				if ($category) {
					$title = $category->name;
				} else {
					return errorResult(categoryNotFound($request->category), $request);
				}
				$products = $products->filter(function ($item) use ($request) {
					return $item->categories->contains($request->category);
				});
			} elseif ($request->has("search")) {
				$regex = "/" . $request->search . "/i";
				$products = $products->filter(function ($item) use ($regex) {
					return preg_match($regex, $item->name);
				});
			}
			$products = $products->groupBy("name");
			if ($products->count() == 0) {
				addAlert($request, "No products found.", "danger");
			}
			return view("customer.shop", ["products" => $products, "title" => $title]);
		}
		
		/**
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function orderAddItem(Request $request) {
			$product = Product::find($request->productId);
			$order = Order::find($request->session()->get("currentOrder"));
			$result = $order->addItem($product, intval($request->quantity));
			if (!$result->status) {
				return errorResult("Not enough in stock.", $request);
			} else {
				if ($request->quantity > 1) {
					$message = $request->quantity . " " . $product->name . "s added to cart";
				} else {
					$message = "$product->name added to cart";
				}
				return successResult($message, $request);
			}
		}
	}
