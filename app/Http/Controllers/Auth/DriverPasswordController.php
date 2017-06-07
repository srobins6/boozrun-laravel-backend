<?php
	namespace App\Http\Controllers\Auth;

	use App\Driver;
	use App\Http\Controllers\Controller;
	use Illuminate\Foundation\Auth\ResetsPasswords;
	use Illuminate\Http\Request;
	use Password;
	
	/**
	 * Class DriverPasswordController
	 *
	 * @package App\Http\Controllers\Auth
	 */
	class DriverPasswordController extends Controller {

		/*
		 * |--------------------------------------------------------------------------
		 * | Password Reset Controller
		 * |--------------------------------------------------------------------------
		 * |
		 * | This controller is responsible for handling driver password reset requests.
		 */
		use ResetsPasswords;

		/**
		 * View for requesting a password reset.
		 *
		 * @var string
		 */
		protected $linkRequestView = "driver.auth.passwords.email";

		/**
		 * View for password reset.
		 *
		 * @var string
		 */
		protected $resetView = "driver.auth.passwords.reset";

		/**
		 * Create a new password controller instance.
		 */
		public function __construct() {
			$this->middleware("guest");
			\Config::set("auth.defaults.passwords", "driver");
		}

		/**
		 * Where to redirect drivers after password reset.
		 *
		 * @var string
		 */
		protected $redirectTo = "/driver";

		/**
		 * Reset the given driver's password.
		 *
		 * @param Driver $driver
		 * @param string $password
		 */
		protected function resetPassword($driver, $password) {
			$driver->password = $password;
			$driver->save();
			\Auth::guard("driver")->login($driver);
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
