<?php
	namespace App\Http\Controllers\Auth;
	
	use App\Http\Controllers\Controller;
	use App\Store;
	use Config;
	use Illuminate\Foundation\Auth\ResetsPasswords;
	use Illuminate\Http\Request;
	use Password;
	
	/**
	 * Class StorePasswordController
	 *
	 * @package App\Http\Controllers\Auth
	 */
	class StorePasswordController extends Controller {
		
		/*
		 * |--------------------------------------------------------------------------
		 * | Password Reset Controller
		 * |--------------------------------------------------------------------------
		 * |
		 * | This controller is responsible for handling store password reset requests.
		 */
		use ResetsPasswords;
		
		/**
		 * View for requesting a password reset.
		 *
		 * @var string
		 */
		protected $linkRequestView = "store.auth.passwords.email";
		
		/**
		 * View for password reset.
		 *
		 * @var string
		 */
		protected $resetView = "store.auth.passwords.reset";
		
		/**
		 * Create a new password controller instance.
		 */
		public function __construct() {
			Config::set("auth.defaults.passwords", "store");
			$this->middleware("guest");
		}
		
		/**
		 * Where to redirect stores after password reset.
		 *
		 * @var string
		 */
		protected $redirectTo = "/store";
		
		/**
		 * Reset the given store's password.
		 *
		 * @param Store  $store
		 * @param string $password
		 */
		protected function resetPassword($store, $password) {
			$store->password = $password;
			$store->save();
			\Auth::guard("store")->login($store);
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
	}
