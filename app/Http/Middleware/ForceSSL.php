<?php

	namespace App\Http\Middleware;

	use Closure;
	
	/**
	 * Class ForceSSL
	 *
	 * @package App\Http\Middleware
	 */
	class ForceSSL {

		/**
		 * Handle an incoming request.
		 *
		 * @param  \Illuminate\Http\Request $request
		 * @param  \Closure                 $next
		 *
		 * @return mixed
		 */
		public function handle($request, Closure $next) {
			if (!$request->secure() && env('APP_ENV') === 'prod') {
				$redirectPath = $request->getPathInfo();
				if (strlen($request->getQueryString()) > 0) {
					$redirectPath .= "?" . $request->getQueryString();
				}
				return redirect()->secure($redirectPath);
			}

			return $next($request);
		}
	}
