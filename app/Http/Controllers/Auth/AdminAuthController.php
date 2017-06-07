<?php
	namespace App\Http\Controllers\Auth;

	use App\Http\Controllers\Controller;
	use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
	use Illuminate\Foundation\Auth\ThrottlesLogins;
	
	/**
	 * Class AdminAuthController
	 *
	 * @package App\Http\Controllers\Auth
	 */
	class AdminAuthController extends Controller {

		/*
		 * |--------------------------------------------------------------------------
		 * | Login Controller
		 * |--------------------------------------------------------------------------
		 * |
		 * | This controller handles the authentication of admins.
		 * |
		 */
		use AuthenticatesAndRegistersUsers, ThrottlesLogins;

		/**
		 * Guard used for authentication.
		 *
		 * @var string
		 */
		protected $guard = "admin";

		/**
		 * Where to redirect admins after login.
		 *
		 * @var string
		 */
		protected $redirectTo = "/admin";

		/**
		 * Where to redirect admins after logout.
		 *
		 * @var string
		 */
		protected $redirectAfterLogout = "/admin";

		/**
		 * Show the admin login form.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function showLoginForm() {
			return view("admin.auth.login");
//			->with("numCols", 6);
		}

		/**
		 * Create a new authentication controller instance.
		 */
		public function __construct() {
			$this->middleware("guest", ["except" => "logout"]);
		}
	}
