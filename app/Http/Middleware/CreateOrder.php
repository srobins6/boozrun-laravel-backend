<?php
	
	namespace App\Http\Middleware;
	
	use App\Order;
	use App\Store;
	use Closure;
	
	/**
	 * Class CreateOrder
	 *
	 * @package App\Http\Middleware
	 */
	class CreateOrder {
		
		/**
		 * Handle an incoming request.
		 *
		 * @param \Illuminate\Http\Request $request
		 * @param \Closure                 $next
		 *
		 * @return mixed
		 */
		public function handle($request, Closure $next) {
			if ($request->session()->has("currentStore")) {
				$store = Store::find($request->session()->get("currentStore"));
				if ($request->session()->has("currentOrder")) {
					$order = Order::find($request->session()->get("currentOrder"));
					if ($order->store != $store) {
						$order->delete();
						$order = $store->orders()->create([]);
						$request->session()->put("currentOrder", $order->id);
					}
				} else {
					$order = $store->orders()->create([]);
					$request->session()->put("currentOrder", $order->id);
				}
			}
			
			return $next ($request);
		}
	}
