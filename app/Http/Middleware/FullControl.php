<?php
	namespace App\Http\Middleware;

	use Closure;
	
	/**
	 * Class FullControl
	 *
	 * @package App\Http\Middleware
	 */
	class FullControl {

		/**
		 * Handle an incoming request.
		 *
		 * @param  \Illuminate\Http\Request $request
		 * @param  \Closure                 $next
		 *
		 * @return mixed
		 */
		public function handle($request, Closure $next) {
			if (auth("admin")->user() && auth("admin")->user()->control == true) {
				return $next($request);
			} elseif (auth("admin")->user()) {
				return back();
			} else {
				return redirect(url("admin"));
			}
		}
	}
