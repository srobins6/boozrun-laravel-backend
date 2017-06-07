<?php
	namespace App\Http\Controllers\Auth;

	use App\Http\Controllers\Controller;
	use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
	use Illuminate\Foundation\Auth\ThrottlesLogins;
	
	/**
	 * Class StoreAuthController
	 *
	 * @package App\Http\Controllers\Auth
	 */
	class StoreAuthController extends Controller {

		/*
		 * |--------------------------------------------------------------------------
		 * | Login Controller
		 * |--------------------------------------------------------------------------
		 * |
		 * | This controller handles the the authentication of stores.
		 * |
		 */
		use AuthenticatesAndRegistersUsers, ThrottlesLogins;

		/**
		 * Guard used for authentication.
		 *
		 * @var string
		 */
		protected $guard = "store";

		/**
		 * View for login.
		 *
		 * @var string
		 */
		protected $loginView = "store.auth.login";

		/**
		 * Where to redirect stores after login / registration.
		 *
		 * @var string
		 */
		protected $redirectTo = "/store";

		/**
		 * Where to redirect stores after logout.
		 *
		 * @var string
		 */
		protected $redirectAfterLogout = "/store";

		/**
		 * Show the store login form.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function showLoginForm() {
			return view("store.auth.login");
//			->with("numCols", 6);
		}

		/**
		 * Create a new authentication controller instance.
		 */
		public function __construct() {
			$this->middleware("guest", ["except" => "logout"]);
		}
	}
