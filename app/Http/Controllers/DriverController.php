<?php
	namespace App\Http\Controllers;
	
	use App\Driver;
	use App\Order;
	use App\Store;
	use Illuminate\Http\Request;
	use JavaScript;
	use Validator;
	
	/**
	 * Class DriverController
	 * This controller handles the routing for drivers.
	 *
	 * @package App\Http\Controllers
	 */
	class DriverController extends Controller {
		
		/**
		 * Current driver.
		 *
		 * @var Driver
		 */
		protected $driver;
		
		/**
		 * Create a new controller instance.
		 */
		public function __construct() {
			JavaScript::put(["urls" => ["driver" => url("driver") . "/",
			                            "order"  => "/orders/"]]);
			$this->driver = auth("driver")->user();
		}
		
		/**
		 * Show the view for drivers to pick up orders.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showOrders() {
			\JavaScript::put(["driverId" => $this->driver->id]);
			return view("driver.orders", ["driver" => $this->driver]);
		}
		
		/**
		 * Show the view for order history.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showOrderHistory() {
			return view("driver.orderhistory", ["driver" => $this->driver]);
		}
		
		/**
		 * Show the view for tip history.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showTips() {
			return view("driver.tips", ["driver" => $this->driver]);
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
		public function driverOrderDelivering(Request $request, $storeId, $orderId) {
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
		 * Cancel an order.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 * @param integer $orderId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function driverOrderCancel(Request $request, $storeId, $orderId) {
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
		 * Mark an order as delivered.
		 *
		 * @param Request $request
		 * @param integer $storeId
		 * @param integer $orderId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function driverOrderDelivered(Request $request, $storeId, $orderId) {
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
		 * Default route.
		 *
		 * @param Request $request
		 *
		 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
		 */
		public function showIndex(Request $request) {
			$request->session()->reflash();
			return redirect("/driver/orders");
		}
		
		/**
		 * Show the form to update the driver account.
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function showAccountUpdateForm() {
			$cities = Store::all()->keyBy("city")->forget("")->pluck("city")->unique()->toArray();
			return view("driver.account",
			            ["cities" => $cities,
			             "driver" => $this->driver//			,"numCols" => 8
			            ]);
		}
		/**
		 * Updates the driver account.
		 *
		 * @param Request $request
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse|null
		 */
		public function accountUpdate(Request $request) {
			$validator = Validator::make($request->all(),
			                             ["email"           => "email|max:255|unique:drivers,email," . $this->driver->id,
			                              "password"        => "confirmed",
			                              "currentPassword" => "required|password_match:admin"]);
			if ($validator->fails()) {
				return errorResult("Account update failed",
				                   $request,
				                   ["validatorErrors" => $validator->errors(),
				                    "redirectUrl"     => back()->withErrors($validator)->getTargetUrl()]);
			} else {
				$this->driver->update($request->all());
				return successResult("Account updated", $request);
			}
		}
		/**
		 * @param Request $request
		 * @param         $driverId
		 *
		 * @return \App\Result|\Illuminate\Http\RedirectResponse
		 */
		public function driverOrdersUpdate(Request $request, $driverId) {
			$driver = Driver::find($driverId);
			$orders = [];
			foreach ($driver->stores as $store) {
				$storeOrders = [];
				$storeOrders["packed"] = $store->packed->transform(function ($order) {
					return view("shared.elements.driverorderrow", ["order" => $order])->render();
				})->implode("\n");
				$storeOrders["delivering"] =
					$store->delivering->where("driver_id", $driver->id)->transform(function ($order) {
						return view("shared.elements.driverorderrow", ["order" => $order])->render();
					})->implode("\n");
				$storeOrders["delivered"] = $store->delivered->where("driver_id", $driver->id)->transform(function ($order) {
					return view("shared.elements.driverorderrow", ["order" => $order])->render();
				})->implode("\n");
				$storeOrders["cancelled"] = $store->cancelled->where("driver_id", $driver->id)->transform(function ($order) {
					return view("shared.elements.driverorderrow", ["order" => $order])->render();
				})->implode("\n");
				$orders[$store->id] = $storeOrders;
			}
			return successResult(null, $request, ["orders" => $orders]);
		}
	}
