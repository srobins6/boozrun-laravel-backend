<?php
	namespace App\Http\Controllers\Auth;
	
	use App\Http\Controllers\Controller;
	use App\Store;
	use Illuminate\Foundation\Auth\ResetsPasswords;
	use Illuminate\Http\Request;
	use Password;
	
	/**
	 * Class CustomerPasswordController
	 *
	 * @package App\Http\Controllers\Auth
	 */
	class CustomerPasswordController extends Controller {
		
		/*
		 * |--------------------------------------------------------------------------
		 * | Password Reset Controller
		 * |--------------------------------------------------------------------------
		 * |
		 * | This controller is responsible for handling customer password reset requests.
		 */
		use ResetsPasswords;
		
		/**
		 * View for requesting a password reset.
		 *
		 * @var string
		 */
		protected $linkRequestView = "customer.auth.passwords.email";
		
		/**
		 * View for password reset.
		 *
		 * @var string
		 */
		protected $resetView = "customer.auth.passwords.reset";
		
		/**
		 * Create a new password controller instance.
		 *
		 * @param Request $request
		 */
		public function __construct(Request $request) {
			if ($request->session()->has("currentStore")) {
				$store = Store::find($request->session()->get("currentStore"));
				view()->share("currentStore", $store);
				view()->share("productNames", $store->products->pluck("name")->unique()->sort()->toArray());
			}
			$this->middleware("guest");
		}
		
		/**
		 * Send a reset link to the given user.
		 *
		 * @param  \Illuminate\Http\Request $request
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function sendResetLinkEmail(Request $request) {
			$this->validateSendResetLinkEmail($request);
			$broker = $this->getBroker();
			$response = Password::broker($broker)->sendResetLink($this->getSendResetLinkEmailCredentials($request),
			                                                     $this->resetEmailBuilder());
			switch ($response) {
				case Password::RESET_LINK_SENT:
					return successResult("Password reset email sent.", $request, $this->getResetSuccessResponse($response));
				case Password::INVALID_USER:
				default:
					return $this->getSendResetLinkEmailFailureResponse($response);
			}
		}
		
		/**
		 * Reset the given user's password.
		 *
		 * @param Request $request
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function reset(Request $request) {
			$this->validate($request,
			                $this->getResetValidationRules(),
			                $this->getResetValidationMessages(),
			                $this->getResetValidationCustomAttributes());
			$credentials = $this->getResetCredentials($request);
			$broker = $this->getBroker();
			$response = Password::broker($broker)->reset($credentials,
				function ($user, $password) {
					$this->resetPassword($user, $password);
				});
			switch ($response) {
				case Password::PASSWORD_RESET:
					addAlert($request, "Password reset.", "success");
					return $this->getResetSuccessResponse($response);
				default:
					return $this->getResetFailureResponse($request, $response);
			}
		}
		
		/**
		 * @param $customer
		 * @param $password
		 */
		protected function resetPassword($customer, $password) {
			$customer->password = $password;
			$customer->save();
			auth($this->getGuard())->login($customer);
		}
	}
