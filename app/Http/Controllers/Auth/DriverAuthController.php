<?php
	namespace App\Http\Controllers\Auth;

	use App\Driver;
	use App\Http\Controllers\Controller;
	use App\Store;
	use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
	use Illuminate\Foundation\Auth\ThrottlesLogins;
	use Mail;
	use Validator;
	
	/**
	 * Class DriverAuthController
	 *
	 * @package App\Http\Controllers\Auth
	 */
	class DriverAuthController extends Controller {

		/*
		 * |--------------------------------------------------------------------------
		 * | Registration & Login Controller
		 * |--------------------------------------------------------------------------
		 * |
		 * | This controller handles the applications of new drivers, as well as the
		 * | authentication of existing drivers.
		 * |
		 */
		use AuthenticatesAndRegistersUsers, ThrottlesLogins;

		/**
		 * How many bootstrap columns to use for views in this controller.
		 *
		 * @var array
		 */
		protected $viewColumns;

		/**
		 * Guard used for authentication.
		 *
		 * @var string
		 */
		protected $guard = "driver";

		/**
		 * Where to redirect drivers after login / registration.
		 *
		 * @var string
		 */
		protected $redirectTo = "/driver";

		/**
		 * Where to redirect drivers after logout.
		 *
		 * @var string
		 */
		protected $redirectAfterLogout = "/driver";

		/**
		 * Create a new authentication controller instance.
		 */
		public function __construct() {
			$this->middleware("guest", ["except" => "logout"]);
		}

		/**
		 * Show the driver application form.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function showRegistrationForm() {
			$cities = Store::all()->keyBy("city")->forget("")->pluck("city")->unique()->toArray();
			return view("driver.auth.apply",
			            [//				            "numCols" => 8,
			             "cities" => $cities]);
		}

		/**
		 * Show the driver login form.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function showLoginForm() {
			return view("driver.auth.login");
			//			->with("numCols", 6);
		}

		/**
		 * Get a validator for an incoming application request.
		 *
		 * @param array $data
		 *
		 * @return \Illuminate\Contracts\Validation\Validator
		 */
		protected function validator(array $data) {
			return Validator::make($data,
			                       ["email"    => "required|email|max:255|unique:drivers",
			                        "password" => "required|confirmed|min:6"]);
		}

		/**
		 * Create a new driver instance after a valid application.
		 *
		 * @param array $data
		 *
		 * @return Driver
		 */
		protected function create(array $data) {
			$driver = Driver::create($data);
			Mail::send("driver.emails.welcome",
			           ["driver" => $driver],
				function ($m) use ($driver) {
					$m->to($driver->email, $driver->name)->subject("Welcome to BoozRun!");
				});
			$driver->password = $data["password"];
			$driver->save();
			return $driver;
		}
	}
