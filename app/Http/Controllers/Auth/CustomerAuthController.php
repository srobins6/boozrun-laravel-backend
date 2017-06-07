<?php
	namespace App\Http\Controllers\Auth;
	
	use App\Customer;
	use App\Http\Controllers\Controller;
	use App\Store;
	use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
	use Illuminate\Foundation\Auth\ThrottlesLogins;
	use Illuminate\Http\Request;
	use Mail;
	use Validator;
	
	/**
	 * Class CustomerAuthController
	 *
	 * @package App\Http\Controllers\Auth
	 */
	class CustomerAuthController extends Controller {
		
		/*
		 * |--------------------------------------------------------------------------
		 * | Registration & Login Controller
		 * |--------------------------------------------------------------------------
		 * |
		 * | This controller handles the registration of new customers, as well as the
		 * | authentication of existing customers.
		 * |
		 */
		use AuthenticatesAndRegistersUsers, ThrottlesLogins;
		
		/**
		 * Guard used for authentication.
		 *
		 * @var string
		 */
		protected $guard = "customer";
		
		/**
		 * Where to redirect customers after login / registration.
		 *
		 * @var string
		 */
		protected $redirectTo = "/";
		
		/**
		 * Create a new authentication controller instance.
		 *
		 * @param Request $request
		 */
		public function __construct(Request $request) {
			if ($request->session()->has("currentStore")) {
				$store = Store::find($request->session()->get("currentStore"));
				view()->share("currentStore", $store);
				view()->share("productNames", $store->products->pluck("name")->unique()->sort()->toArray());
			}
			$this->middleware("guest", ["except" => "logout"]);
		}
		
		/**
		 * Show the customer registration form.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function showRegistrationForm() {
			$maxDate = date_create(date("Y-m-d"));
			date_modify($maxDate, "-21 year");
			$maxDate = $maxDate->format("Y-m-d");
			return view("customer.auth.register",
			            [//				"numCols" => 6,
			             "maxDate" => $maxDate]);
		}
		
		/**
		 * Show the customer login form.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function showLoginForm() {
			return view("customer.auth.login");
			//			->with("numCols", 6);
		}
		
		/**
		 * Get a validator for an incoming registration request.
		 *
		 * @param array $data
		 *
		 * @return \Illuminate\Contracts\Validation\Validator
		 */
		function validator(array $data) {
			return Validator::make($data,
			                       ["email"    => "required|email|max:255|unique:customers",
			                        "password" => "required|confirmed|min:6",
			                        "name"     => "required",
			                        "birthday" => "date"]);
		}
		
		/**
		 * Create a new customer instance after a valid registration.
		 *
		 * @param array $data
		 *
		 * @return Customer
		 */
		function create(array $data) {
			$customer = Customer::create($data);
			Mail::send("customer.emails.welcome",
			           [],
				function ($m) use ($customer) {
					$m->to($customer->email, $customer->name)->subject("Welcome to BoozRun!");
				});
			return $customer;
		}
	}
