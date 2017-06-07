<?php
	namespace App\Http\Controllers\Auth;
	
	use App\Admin;
	use App\Http\Controllers\Controller;
	use Config;
	use Illuminate\Foundation\Auth\ResetsPasswords;
	use Illuminate\Http\Request;
	use Password;
	
	/**
	 * Class AdminPasswordController
	 *
	 * @package App\Http\Controllers\Auth
	 */
	class AdminPasswordController extends Controller {
		
		/*
		 * |--------------------------------------------------------------------------
		 * | Password Reset Controller
		 * |--------------------------------------------------------------------------
		 * |
		 * | This controller is responsible for handling admin password reset requests.
		 */
		use ResetsPasswords;
		
		/**
		 * View for requesting a password reset.
		 *
		 * @var string
		 */
		protected $linkRequestView = "admin.auth.passwords.email";
		
		/**
		 * View for password reset.
		 *
		 * @var string
		 */
		protected $resetView = "admin.auth.passwords.reset";
		
		/**
		 * Where to redirect admins after password reset.
		 *
		 * @var string
		 */
		protected $redirectTo = "/admin";
		
		/**
		 * Reset the given admin's password.
		 *
		 * @param Admin  $admin
		 * @param string $password
		 */
		protected function resetPassword($admin, $password) {
			$admin->password = $password;
			$admin->save();
			\Auth::guard("admin")->login($admin);
		}
		
		/**
		 * Create a new password controller instance.
		 */
		public function __construct() {
			Config::set("auth.defaults.passwords", "admin");
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
	}
