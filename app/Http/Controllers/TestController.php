<?php
	namespace App\Http\Controllers;
	
	use App\Customer;
	use App\Driver;
	use App\Order;
	use App\Product;
	use App\Store;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;
	
	class TestController extends Controller {
		
		function test(/** @noinspection PhpUnusedParameterInspection */
			Request $request) {
			//			return view("layouts.newemail");
			$order = Order::find(11);
			\Mail::send("customer.emails.order",
			            ["order" => $order],
				function ($m) use ($order) {
					$m->to("srobins6@gmail.com", $order->customer->name)->subject("Order submitted");
				});
			return view("customer.emails.order",
			            ["order" => $order]);
		}
		
		function ordersSpawn() {
			foreach (Store::all() as $store) {
				foreach (Customer::all() as $customer) {
					for ($i = 0; $i < 2; $i++) {
						$order = $store->orders()->create([]);
					}
				}
			}
		}
		
		function spawn(Request $request) {
			$this->truncate($request);
			//			Customer::create(["name"     => "Sol Robinson",
			//			                  "email"    => "srobins6@gmail.com",
			//			                  "password" => "password",
			//			                  "birthday" => "1993-04-19",
			//			                  "phone"    => '2178988543',
			//			                  "address"  => "310 East Springfield, Champaign Illinois, 61820"]);
			//			Store::create(["email"      => "srobins6@gmail.com",
			//			               "owner_name" => "Owner Name",
			//			               "name"       => "Campus Pantry",
			//			               "phone"      => "2176007549",
			//			               "contract"   => "files/store_contracts/campuspantry.txt",
			//			               "address"    => "112 E Green Street Suite B Champaign, Il 61820",
			//			               "password"   => "turtles",
			//			               "taxrate"    => 9]);
			for ($i = 1; $i <= 10; $i++) {
				//				Store::create(["email"      => "$i@test.com",
				//				               "owner_name" => "Owner $i",
				//				               "name"       => "Test store $i",
				//				               "phone"      => "5555555555",
				//				               "address"    => "112 E Green Street Suite B Champaign, Il 61820",
				//				               "taxrate"    => 9,
				//				               "active"     => ($i % 2 == 0)]);
				//				Customer::create(["name"     => "Test Customer $i",
				//				                  "email"    => "$i@test.com",
				//				                  "password" => "password",
				//				                  "birthday" => "1993-04-19",
				//				                  "phone"    => '5555555555',
				//				                  "address"  => "310 East Springfield, Champaign Illinois, 61820"]);
				//				Driver::create(["email"     => "$i@test.com",
				//				                "name"      => "Test driver $i",
				//				                "phone"     => "5555555555",
				//				                "city"      => "Champaign",
				//				                "confirmed" => ($i % 2 == 0),
				//				                "active"    => ($i % 3 == 0)]);
				//				Admin::create(["email"   => "$i@test.com",
				//				               "name"    => "Test admin $i",
				//				               "control" => ($i % 2 == 0)]);
			}
			return back();
		}
		
		function flashtest(Request $request) {
			$session = $request->session();
			debug($session->hasOldInput());
			debug($session->hasOldInput());
		}
		
		/**
		 * @param
		 *            request
		 */
		/** @noinspection PhpUnusedPrivateMethodInspection
		 * @param $request
		 */
		private function testorderproductadd($request) {
			$request->session()->forget('currentOrder');
			if (!$request->session()->has('currentOrder')) {
				$order = Order::create([]);
				$request->session()->put('currentOrder', $order);
			}
			$order = $request->session()->get('currentOrder');
			$product = Product::create(["name"       => "beertest",
			                            "store_id"   => 2,
			                            "price"      => 3.5,
			                            "stock"      => 10,
			                            "categories" => ["beer",
			                                             11]]);
			$order->addItem($product->id, 1);
		}
		
		/**
		 */
		/** @noinspection PhpUnusedPrivateMethodInspection */
		private function productcreationtest() {
			$product = Product::create(["name"       => "beertest",
			                            "store_id"   => 2,
			                            "price"      => 3.5,
			                            "stock"      => 10,
			                            "categories" => ["beer"]]);
			echo $product->categories->pluck('name');
			/** @noinspection PhpUnusedLocalVariableInspection */
			$product2 = Product::create(["name"       => "beertest",
			                             "store_id"   => 1,
			                             "price"      => 3.5,
			                             "stock"      => 10,
			                             "categories" => ["wine",
			                                              "red"]]);
			/** @noinspection PhpUnusedLocalVariableInspection */
			$product3 = Product::create(["name"       => "aletest",
			                             "store_id"   => 1,
			                             "price"      => 3.5,
			                             "stock"      => 10,
			                             "categories" => ["ales"]]);
			/** @noinspection PhpUnusedLocalVariableInspection */
			$product4 = Product::create(["name"       => "aletest2",
			                             "store_id"   => 1,
			                             "price"      => 3.5,
			                             "stock"      => 10,
			                             "categories" => "ales"]);
		}
		
		/**
		 * @param Request $request
		 */
		function logintest(/** @noinspection PhpUnusedParameterInspection */
			Request $request) {
			$password = "orio419";
			$email = "test2@test.com";
			Auth::attempt(['email'    => $email,
			               'password' => $password]);
			$customer = Auth::user();
			$store = Store::where("email", $email)->first();
			if ($store) {
				$storeId = ($store->id);
			} else {
				$storeId = 0;
			}
			$username = implode("-", explode(" ", $customer->name));
			/** @noinspection PhpUnusedLocalVariableInspection */
			$customerData = ["s_id" => $storeId,
			                 "id"   => $customer->id];
			debug($username);
		}
		
		function truncate(Request $request) {
			Artisan::call('migrate:refresh');
			$request->session()->flush();
			return back()->withInput();
		}
		
		/**
		 * @param Request $request
		 */
		function test2(/** @noinspection PhpUnusedParameterInspection */
			Request $request) {
			Customer::truncate();
			$customer = new Customer ();
			$customer->email = "3";
			$customer->save();
			Customer::create(["email" => "1"]);
			Customer::create(["email" => "2"]);
		}
		
		function test1(/** @noinspection PhpUnusedParameterInspection */
			Request $request) {
			Customer::truncate();
			Store::truncate();
			Driver::truncate();
			DB::table('role_customer')->truncate();
			for ($index = 1; $index < 6; $index++) {
				$customer = new Customer ();
				$customer->email = "store" . $index;
				$customer->save();
				$customer->store()->create([]);
				$customer->roles()->attach("store");
			}
			for ($index = 1; $index < 6; $index++) {
				$customer = new Customer ();
				$customer->email = "driver" . $index;
				$customer->save();
				$customer->roles()->attach("driver");
				$customer->driver()->create([]);
				for ($storeindex = $index; $storeindex < 5; $storeindex++) {
					$customer->driver->stores()->attach(Store::find($storeindex));
				}
			}
		}
	}
