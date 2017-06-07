<?php
	namespace App\Http\Middleware;

	use Closure;
	
	/**
	 * Class Authenticate
	 *
	 * @package App\Http\Middleware
	 */
	class Authenticate {

		/**
		 * Handle an incoming request.
		 *
		 * @param \Illuminate\Http\Request $request
		 * @param \Closure                 $next
		 * @param string|null              $guard
		 *
		 * @return mixed
		 */
		public function handle($request, Closure $next, $guard = null) {
			if (auth($guard)->guest()) {
				if ($request->ajax() || $request->wantsJson()) {
					return response('Unauthorized.', 401);
				} elseif ($guard && $guard != "customer") {
					return redirect()->guest($guard . '/login');
				} else {
					return redirect()->guest('login');
				}
			}
			return $next ($request);
		}
	}
