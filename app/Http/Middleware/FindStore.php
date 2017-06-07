<?php
	namespace App\Http\Middleware;

	use Closure;
	
	/**
	 * Class FindStore
	 *
	 * @package App\Http\Middleware
	 */
	class FindStore {

		/**
		 * Handle an incoming request.
		 *
		 * @param \Illuminate\Http\Request $request
		 * @param \Closure                 $next
		 *
		 * @return mixed
		 */
		public function handle($request, Closure $next) {
			if (!$request->session()->has('currentStore') && $request->url() != url("/findstore")) {
				return redirect("/findstore");
			}
			return $next ($request);
		}
	}
