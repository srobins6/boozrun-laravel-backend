<?php
	namespace App\Http\Middleware;

	use Closure;
	
	/**
	 * Class RedirectIfAuthenticated
	 *
	 * @package App\Http\Middleware
	 */
	class RedirectIfAuthenticated {

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
			/**
			 * The actual guard for the path of the request
			 *
			 * @var string $realGuard
			 */
			$matches = [];
			preg_match("/\\/?([^\\/]*)/", $request->path(), $matches);
			$realGuard = $matches [1];
			$redirectPath = "";
			global $app;
			if (isset ($app ['config'] ['auth'] ['guards'] [$realGuard])) {
				$guard = $realGuard;
				$redirectPath = "$realGuard/";
			}
			if (auth($guard)->check()) {
				return redirect($redirectPath . '/');
			}
			return $next ($request);
		}
	}
