<?php

namespace App\Http\Middleware;

use Closure;
use Log;

/**
 * Class ApiResponseFix
 *
 * @package App\Http\Middleware
 */
class ApiResponseFix {
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		Log::warning($request->url());
		Log::debug($request->all());
		$response = $next ( $request );
		return $response->header ( 'Content-Type', 'text/html; charset=UTF-8' );
	}
}
