<?php
	namespace App\Http\Middleware;

	use Auth;
	use Closure;
	
	/**
	 * Class StripeVerify
	 *
	 * @package App\Http\Middleware
	 */
	class StripeVerify {

		/**
		 * Handle an incoming request.
		 *
		 * @param \Illuminate\Http\Request $request
		 * @param \Closure                 $next
		 *
		 * @return mixed
		 */
		public function handle($request, Closure $next) {
			$store = Auth::guard("store")->user();
			/** @noinspection PhpUndefinedFieldInspection */
			if ($store && !$store->stripe_verified) {
				addAlert($request,
				         "Please verify your account by clicking <a href='" . url("store/verify") . "'>here</a>",
				         "danger");
			}
			return $next ($request);
		}
	}
