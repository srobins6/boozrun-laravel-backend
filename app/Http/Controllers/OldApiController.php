<?php
	namespace App\Http\Controllers;
	
	use App\Category;
	use App\Customer;
	use App\Http\Controllers\Auth\CustomerAuthController;
	use App\Http\Controllers\Auth\CustomerPasswordController;
	use App\OldShoppingCart;
	use App\Product;
	use App\Promo;
	use App\Store;
	use Auth;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Storage;
	
	/**
	 * Class OldApiController
	 *
	 * @package App\Http\Controllers
	 */
	class OldApiController extends Controller {
		
		/*
		 * |--------------------------------------------------------------------------
		 * | Old Api Controller
		 * |--------------------------------------------------------------------------
		 * |
		 * | This controller handles the routing for api calls from the iPhone app.
		 * |
		 */
		/**
		 * @param Request $request
		 * @param         $customerId
		 *
		 * @return string
		 */
		function customerGetLastAddress(Request $request, $customerId) {
			$us_state_abbrevs_names = ["AL" => "ALABAMA",
			                           "AK" => "ALASKA",
			                           "AS" => "AMERICAN SAMOA",
			                           "AZ" => "ARIZONA",
			                           "AR" => "ARKANSAS",
			                           "CA" => "CALIFORNIA",
			                           "CO" => "COLORADO",
			                           "CT" => "CONNECTICUT",
			                           "DE" => "DELAWARE",
			                           "DC" => "DISTRICT OF COLUMBIA",
			                           "FM" => "FEDERATED STATES OF MICRONESIA",
			                           "FL" => "FLORIDA",
			                           "GA" => "GEORGIA",
			                           "GU" => "GUAM GU",
			                           "HI" => "HAWAII",
			                           "ID" => "IDAHO",
			                           "IL" => "ILLINOIS",
			                           "IN" => "INDIANA",
			                           "IA" => "IOWA",
			                           "KS" => "KANSAS",
			                           "KY" => "KENTUCKY",
			                           "LA" => "LOUISIANA",
			                           "ME" => "MAINE",
			                           "MH" => "MARSHALL ISLANDS",
			                           "MD" => "MARYLAND",
			                           "MA" => "MASSACHUSETTS",
			                           "MI" => "MICHIGAN",
			                           "MN" => "MINNESOTA",
			                           "MS" => "MISSISSIPPI",
			                           "MO" => "MISSOURI",
			                           "MT" => "MONTANA",
			                           "NE" => "NEBRASKA",
			                           "NV" => "NEVADA",
			                           "NH" => "NEW HAMPSHIRE",
			                           "NJ" => "NEW JERSEY",
			                           "NM" => "NEW MEXICO",
			                           "NY" => "NEW YORK",
			                           "NC" => "NORTH CAROLINA",
			                           "ND" => "NORTH DAKOTA",
			                           "MP" => "NORTHERN MARIANA ISLANDS",
			                           "OH" => "OHIO",
			                           "OK" => "OKLAHOMA",
			                           "OR" => "OREGON",
			                           "PW" => "PALAU",
			                           "PA" => "PENNSYLVANIA",
			                           "PR" => "PUERTO RICO",
			                           "RI" => "RHODE ISLAND",
			                           "SC" => "SOUTH CAROLINA",
			                           "SD" => "SOUTH DAKOTA",
			                           "TN" => "TENNESSEE",
			                           "TX" => "TEXAS",
			                           "UT" => "UTAH",
			                           "VT" => "VERMONT",
			                           "VI" => "VIRGIN ISLANDS",
			                           "VA" => "VIRGINIA",
			                           "WA" => "WASHINGTON",
			                           "WV" => "WEST VIRGINIA",
			                           "WI" => "WISCONSIN",
			                           "WY" => "WYOMING",
			                           'AE" => "ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
			                           "AA" => "ARMED FORCES AMERICA (EXCEPT CANADA)",
			                           "AP" => "ARMED FORCES PACIFIC"];
			$customer = Customer::find($customerId);
			$res_data = [];
			$res_data ["response"] = 1;
			$res_data ["error"] = false;
			$res_data ["error_msg"] = "";
			$res_data ["msg"] = "";
			if ($customer) {
				if ($customer->address) {
					$address = addressParse($customer->address);
					$data = ["street"  => $address["street"],
					         "city"    => $address["city"],
					         "state"   => ucwords(strtolower($us_state_abbrevs_names[$address["state"]])),
					         "zipcode" => $address["zipcode"],
					         "phone"   => $customer->phone];
					$res_data ["status"] = "success";
					$res_data ["data"] = $data;
				} else {
					$res_data ["response"] = 0;
					$res_data ["error"] = true;
					$res_data ["error_msg"] = "No address found";
					
					$queryParams = ["latlng" => $request->latitude . "," . $request->longitude,
					                "key"    => env("GOOGLE_KEY")];
					$query = http_build_query($queryParams);
					$url = "https://maps.googleapis.com/maps/api/geocode/json?$query";
					
					$queryResponse = json_decode(file_get_contents($url));
					if (count($queryResponse->results) > 0) {
						$result = $queryResponse->results[0];
						
						if ($result->formatted_address) {
							$res_data ["response"] = 1;
							$res_data ["error"] = false;
							$res_data ["error_msg"] = "";
							$address = addressParse($result->formatted_address);
							$data = ["street"  => $address["street"],
							         "city"    => $address["city"],
							         "state"   => ucwords(strtolower($us_state_abbrevs_names[$address["state"]])),
							         "zipcode" => $address["zipcode"]];
							$res_data ["status"] = "success";
							$res_data ["data"] = $data;
						};
					}
				}
			} else {
				$res_data ["response"] = 0;
				$res_data ["error"] = true;
				$res_data ["error_msg"] = "User Not Found";
			}
			return json_encode($res_data);
		}
		
		/**
		 * Gets the customer's profile information.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function customerGetProfile(Request $request) {
			$customer = Customer::find($request->user_id);
			if ($customer) {
				if (strlen($customer->address) > 0) {
					$matches = addressParse($customer->address);
					$city = $matches ["city"];
					$street = $matches ["street"];
					$zipcode = $matches ["zipcode"];
					$state = $matches ["state"];
				} else {
					$city = "";
					$street = "";
					$zipcode = "";
					$state = "";
				}
				$user = ["id"              => $customer->id,
				         "phone"           => $customer->phone,
				         "email"           => $customer->email,
				         "profile_picture" => $customer->image,
				         "favorite_drink"  => "",
				         "name"            => $customer->name,
				         "password"        => $customer->password,
				         "birthday"        => $customer->birthday->format("m-d-Y"),
				         "city"            => $city,
				         "street"          => $street,
				         "zipcode"         => $zipcode,
				         "state"           => $state];
				$order = $customer->currentOrder;
				if ($order) {
					$orderStatus = ["submitted"  => "sent",
					                "packed"     => "packed",
					                "delivering" => "inRoute",
					                "delivered"  => "delivered"];
					$user["order_status"] = $orderStatus[$order->status];
				} else {
					$user["order_status"] = "e";
				}
				return apiSuccessResult($user, "User profile for user id " . $request->user_id);
			} else {
				return apiErrorResult("", "No User for user id : " . $request->user_id);
			}
		}
		
		/**
		 * Customer login.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function customerLogin(Request $request) {
			if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
				$customer = Auth::user();
			} elseif ($request->has("fb_id")) {
				$possibleCustomer = Customer::whereEmail($request->email)->first();
				if ($possibleCustomer and \Hash::check($request->fb_id, $possibleCustomer->fb_id) == true) {
					$customer = $possibleCustomer;
				}
			}
			if (isset($customer)) {
				if ($request->session()->has("fb_id")) {
					$customer->fb_id = $request->session()->get("fb_id");
					$customer->save();
				}
				/**
				 * @var Customer $customer
				 */
				{
					$customerData = ["s_id"     => 0,
					                 "id"       => $customer->id,
					                 "username" => implode("-", explode(" ", $customer->name)),
					                 "email"    => $customer->email,
					                 "is_admin" => 2];
				}
				return apiSuccessResult($customerData, "login success");
			} else {
				
				return apiErrorResult("false", "login fail");
			}
		}
		
		/**
		 * @param Request $request
		 */
		function customerPasswordReset(Request $request) {
			$controller = new CustomerPasswordController($request);
			$controller->sendResetLinkEmail($request);
		}
		
		/**
		 * Signs up a new customer.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function customerSignup(Request $request) {
			$data = $request->except("dev_token", "favorite_drink", "order_status", "profile_picture");
			if ($request->fb_id) {
				$birthday = explode("/", $data["birthday"]);
			} else {
				$birthday = explode("-", $data["birthday"]);
			}
			$data["birthday"] = $birthday[2] . "-" . $birthday[0] . "-" . $birthday[1];
			
			$validationData = $data;
			$validationData ["password_confirmation"] = $data ["password"];
			$controller = new CustomerAuthController ($request);
			$validator = $controller->validator($validationData);
			if (!$validator->fails()) {
				$customer = $controller->create($data);
				if ($request->hasFile("profile_picture")) {
					Storage::disk("public")->delete($customer->image);
					$request->file("profile_picture")->move(public_path("profile_images"), $customer->id . ".png");
				}
				return apiSuccessResult($data, "User created successfully.");
			} else {
				if ($request->has("fb_id")) {
					$request->session()->put("fb_id", $request->fb_id);
				}
				
				$message = implode(", ", $validator->getMessageBag()->all());
				return apiErrorResult($data, $message);
			}
		}
		
		/**
		 * Update Customer profile.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function customerUpdateProfile(Request $request) {
			$customer = Customer::find($request->id);
			$data = ["name"  => $request->name,
			         "phone" => $request->phone];
			if ($request->has("password")) {
				$data["password"] = $request->password;
			}
			$customer->update($data);
			if ($request->hasFile("profile_picture")) {
				Storage::disk("public")->delete($customer->image);
				$request->file("profile_picture")->move(public_path("profile_images"), $customer->id . ".png");
			}
			$customer->save();
			return apiSuccessResult($data, "User created successfully");
		}
		
		/**
		 * Gets the current order.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function orderGet(Request $request) {
			$oldShoppingCart = OldShoppingCart::firstOrCreate(["session_id" => $request->session_id]);
			if ($oldShoppingCart) {
				$order = $oldShoppingCart->order;
				$items = [];
				foreach ($order->items as $index => $realItem) {
					if ($realItem->active) {
						$status = 1;
					} else {
						$status = 0;
					}
					$categories = $realItem->categories()->where("parent_id", 0)->get();
					$subcategories = $realItem->categories()->where("parent_id", ">", 0)->get();
					$subcategory = $subcategories->first();
					if ($subcategory) {
						$categoryId = $subcategory->parent->id;
						$subcategoryId = $subcategory->id;
					} else {
						$category = $categories->first();
						if ($category) {
							$categoryId = $category->id;
							$subcategoryId = $categoryId;
						} else {
							$categoryId = 1;
							$subcategoryId = 1;
						}
					}
					$item = ["id"           => $realItem->id,
					         "price"        => $realItem->price,
					         "quantity"     => $realItem->stock,
					         "image"        => $realItem->image->small,
					         "size"         => $realItem->size,
					         "store_id"     => $realItem->store->id,
					         "status"       => $status,
					         "name"         => $realItem->name,
					         "like"         => 0,
					         "comments"     => "none",
					         "category"     => $categoryId,
					         "sub_category" => $subcategoryId];
					$items [] = ["price"    => $realItem->orderPrice,
					             "quantity" => $realItem->quantity,
					             "item"     => $item];
				}
				return apiSuccessResult($items, "Get Shopping Cart Success");
			} else {
				return apiErrorResult("false", "No Session ID");
			}
		}
		
		/**
		 * Create a new order.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function orderCreate(Request $request) {
			$oldShoppingCart = OldShoppingCart::firstOrCreate(["session_id" => $request->session_id]);
			if ($oldShoppingCart) {
				return apiSuccessResult("true", "Create Shopping Cart success");
			} else {
				return apiErrorResult("false", "Create Shopping Cart Failed");
			}
		}
		
		/**
		 * Delete an order.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function orderDelete(Request $request) {
			$oldShoppingCart = OldShoppingCart::find($request->session_id);
			if (!$oldShoppingCart) {
				return apiErrorResult("false", "session_id does not exist");
			} else {
				$oldShoppingCart->delete();
				return apiSuccessResult("true", "Remove Shopping Cart Success");
			}
		}
		
		/**
		 * Add item to an order.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function orderAddItem(Request $request) {
			$oldShoppingCart = OldShoppingCart::firstOrCreate(["session_id" => $request->session_id]);
			$order = $oldShoppingCart->order;
			$productId = $request->itemID;
			$product = Product::find($productId);
			if ($product) {
				$result = $order->addItem($product, intval($request->quantity));
				if (!$result->status) {
					return apiErrorResult("false", "Not enough stock");
				} else {
					return apiSuccessResult("true", "Add Shopping Cart success");
				}
			} else {
				return apiErrorResult("false", "Add Shopping Cart Failed");
			}
		}
		
		/**
		 * Remove an item from the order.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function orderRemoveItem(Request $request) {
			$oldShoppingCart = OldShoppingCart::firstOrCreate(["session_id" => $request->session_id]);
			$order = $oldShoppingCart->order;
			$productId = $request->itemID;
			$product = Product::find($productId);
			if ($product) {
				$result = $order->removeItem($product);
				if (!$result->status) {
					return apiErrorResult("false", "Remove Item in Shopping Cart");
				} else {
					return apiSuccessResult("true", "Remove Item in Shopping Cart success");
				}
			} else {
				return apiErrorResult("false", "Remove Item in Shopping Cart");
			}
		}
		
		/**
		 * Updates the quantity of an item in the order.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function orderUpdateItem(Request $request) {
			$oldShoppingCart = OldShoppingCart::firstOrCreate(["session_id" => $request->session_id]);
			$order = $oldShoppingCart->order;
			$productId = $request->itemID;
			$product = Product::find($productId);
			if ($product) {
				$result = $order->updateItem($product, intval($request->new_quantity));
				if (!$result->status) {
					return apiErrorResult("false", "Remove Item in Shopping Cart");
				} else {
					return apiSuccessResult("true", "Remove Item in Shopping Cart success");
				}
			} else {
				return apiErrorResult("false", "Product not found");
			}
		}
		
		/**
		 * @param Request $request
		 *
		 * @return array
		 */
		function promoVerify(Request $request) {
			$promo = Promo::whereCode($request->promo_code)->first();
			if ($promo) {
				$customer = Customer::find($request->user_id);
				if ($customer) {
					
					if (!$promo->customers->contains($customer) || $promo->reusable == true) {
						
						$data = ["session_coupon" => $promo->code, "id" => $promo->id, "rate" => $promo->amount];
						if ($promo->type == "fixed") {
							$data["type"] = "D";
						} else {
							$data["type"] = "Q";
						}
						return apiSuccessResult($data,
						                        "Your promo code has been accepted! Enjoy.");
					} else {
						return apiErrorResult("false", "Promo $promo->code already used");
					}
				} else {
					return apiErrorResult("false", customerNotFound($request->user_id));
				}
			} else {
				return apiErrorResult("false", promoNotFound($request->promo_code));
			}
		}
		
		/**
		 * @param Request $request
		 *
		 * @return array
		 */
		function orderSubmit(Request $request) {
			$oldShoppingCart = OldShoppingCart::find($request->session_id);
			if ($oldShoppingCart) {
				$order = $oldShoppingCart->order;
				$order->customer_id = $request->user_id;
				$order->store_id = $request->store_id;
				$order->save();
				$expDate = explode("/", $request->expDate);
				$address = "$request->street, $request->city, $request->state $request->zipcode, USA";
				\Stripe\Stripe::setApiKey(config("services")["stripe"]["secret"]);
				try {
					$card = ["card" => ["number"    => $request->cardNo,
					                    "exp_month" => $expDate[0],
					                    "exp_year"  => substr("" . Carbon::now()->year, 0, 2) . $expDate[1],
					                    "cvc"       => $request->secNo]];
					$source = \Stripe\Token::create($card);
					if (strlen($request->session_coupon) > 0) {
						$order->promo()->associate(Promo::whereCode($request->session_coupon)->first());
						$order->save();
					}
					
					$submit = $order->submit($request->full_name,
					                         $request->phone,
					                         $address,
					                         $source,
					                         $request->tip,
					                         $request->notes);
					
					if ($submit->status) {
						return apiSuccessResult("true", "Order Success");
					} else {
						return apiErrorResult("false", $submit->message);
					}
				} catch (\Exception $ex) {
					return apiErrorResult("false", $ex->getMessage());
				}
			} else {
				return apiErrorResult("false", "No order found");
			}
		}
		
		/**
		 * Returns a list of stores in range.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function storeGetLocalStores(Request $request) {
			$latitude = floatval($request->get("latitude"));
			$longitude = floatval($request->get("longitude"));
			$miles = floatval($request->get("miles"));
			$stores = getLocalStores($latitude, $longitude, $miles, false);
			if (!$stores->isEmpty()) {
				foreach ($stores as $store) {
					$store->store_hash = "";
					$store->store_description = "";
					$store->s_id = $store->id;
					$store->store_address = $store->address;
					$store->store_status = 1;
					$store->store_lat = $store->latitude;
					$store->store_lang = $store->longitude;
					$store->store_phone = $store->phone;
					$store->store_tax_payment = $store->taxrate;
					$store->tax_price = $store->taxrate;
					$store->store_monthly_payment = "";
					$store->store_logo = null;
					unset ($store->taxrate);
					unset ($store->phone);
					unset ($store->email);
					unset ($store->owner_name);
					unset ($store->product_control);
					unset ($store->longitude);
					unset ($store->latitude);
					unset ($store->address);
				}
				$stores = [$stores->first()];
				return apiSuccessResult($stores, "Store which is the nearest from you");
			} else {
				return apiErrorResult($stores, "No store found");
			}
			//			)->header('Content-Type',
			//			'text/html; charset=UTF-8');
		}
		
		/**
		 * Returns a list of products by category for a store.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function storeGetProductsByCategory(Request $request) {
			$categoryName = $request->category;
			$category = Category::where("name", $categoryName)->first();
			$categoryId = $category->id;
			$storeId = $request->store_id;
			$products = Product::where("store_id", $storeId)->get()->filter(function ($item) use ($category) {
				return $item->categories->contains($category);
			});
			$tempProducts = [];
			foreach ($products as $product) {
				$subcategory = $product->categories()->where("parent_id", "!=", 0)->first();
				if ($subcategory) {
					$subcategoryId = $subcategory->id;
				} else {
					$subcategoryId = $categoryId;
				}
				$product->quantity = $product->stock;
				$product->category = $categoryId;
				$product->status = $product->active;
				$product->sub_category = $subcategoryId;
				$product->comments = [];
				$product->likes = 0;
				$image = $product->image->full;
				$product->thumb_image = $product->image->small;
				unset($product->image);
				$product->image = $image;
				unset ($product->categories);
				unset ($product->stock);
				unset ($product->active);
				$tempProducts [] = $product->toArray();
			}
			if ($products->isEmpty()) {
				return apiErrorResult("", "No Products for Category " . $categoryId);
			} else {
				return apiSuccessResult($tempProducts, "Products for category: " . $categoryId);
			}
		}
		
		/**
		 * Returns a list of products by subcategory for a store.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function storeGetProductsBySubcategory(Request $request) {
			$categoryName = $request->sub_category;
			$category = Category::where("name", $categoryName)->first();
			$categoryId = $category->id;
			$storeId = $request->store_id;
			$products = Product::where("store_id", $storeId)->get()->filter(function ($item) use ($category) {
				return $item->categories->contains($category);
			});
			$tempProducts = [];
			foreach ($products as $product) {
				$product->quantity = $product->stock;
				$product->category = $category->parent->id;
				$product->status = $product->active;
				$product->sub_category = $categoryId;
				$product->comments = [];
				$image = $product->image->full;
				$product->thumb_image = $product->image->small;
				unset($product->image);
				$product->image = $image;
				$product->likes = 0;
				unset ($product->categories);
				unset ($product->stock);
				unset ($product->active);
				$tempProducts [] = $product->toArray();
			}
			if ($products->isEmpty()) {
				return apiErrorResult("", "No Products for Category " . $categoryId);
			} else {
				return apiSuccessResult($tempProducts, "Products for category: " . $categoryId);
			}
		}
		
		/**
		 * Returns a list of all products for a store.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function storeGetProducts(Request $request) {
			$storeId = $request->store_id;
			$store = Store::find($storeId);
			if ($store) {
				$products = Product::where("store_id", $storeId)->get();
				$tempProducts = [];
				foreach ($products as $product) {
					$category = $product->categories()->where("parent_id", 0)->first();
					if ($category) {
						$categoryId = $category->id;
					} else {
						$categoryId = 1;
					}
					$subcategory = $product->categories()->where("parent_id", "!=", 0)->first();
					if ($subcategory) {
						$subcategoryId = $subcategory->id;
					} else {
						$subcategoryId = $categoryId;
					}
					
					$product->quantity = $product->stock;
					$product->category = $categoryId;
					$product->status = $product->active;
					$product->sub_category = $subcategoryId;
					$product->comments = [];
					$image = $product->image->full;
					$product->thumb_image = $product->image->small;
					unset($product->image);
					$product->image = $image;
					$product->likes = 0;
					unset ($product->categories);
					unset ($product->stock);
					unset ($product->active);
					$tempProducts [] = $product->toArray();
				}
				if ($products->isEmpty()) {
					return apiErrorResult("", "No Products for store $store->id");
				} else {
					return apiSuccessResult($tempProducts, "Products for store $store->id");
				}
			} else {
				return apiErrorResult("", storeNotFound($storeId));
			}
		}
		
		/**
		 * Gets the hours for a store.
		 *
		 * @param Request $request
		 *
		 * @return array
		 */
		function storeGetHours(Request $request) {
			$store = Store::find($request->store_id);
			$hours = $store->activeHours;
			$tempHours = [["term_name"  => "Monday",
			               "start_time" => $hours->mondayopen ? date("h:i A", strtotime($hours->mondaystart)) : "Closed",
			               "end_time"   => $hours->mondayopen ? date("h:i A", strtotime($hours->mondayend)) : "Closed"],
			              ["term_name"  => "Tuesday",
			               "start_time" => $hours->tuesdayopen ? date("h:i A", strtotime($hours->tuesdaystart)) : "Closed",
			               "end_time"   => $hours->tuesdayopen ? date("h:i A", strtotime($hours->tuesdayend)) : "Closed"],
			              ["term_name"  => "Wednesday",
			               "start_time" => $hours->wednesdayopen ? date("h:i A", strtotime($hours->wednesdaystart)) :
				               "Closed",
			               "end_time"   => $hours->wednesdayopen ? date("h:i A", strtotime($hours->wednesdayend)) :
				               "Closed"],
			              ["term_name"  => "Thursday",
			               "start_time" => $hours->thursdayopen ? date("h:i A", strtotime($hours->thursdaystart)) : "Closed",
			               "end_time"   => $hours->thursdayopen ? date("h:i A", strtotime($hours->thursdayend)) : "Closed"],
			              ["term_name"  => "Friday",
			               "start_time" => $hours->fridayopen ? date("h:i A", strtotime($hours->fridaystart)) : "Closed",
			               "end_time"   => $hours->fridayopen ? date("h:i A", strtotime($hours->fridayend)) : "Closed"],
			              ["term_name"  => "Saturday",
			               "start_time" => $hours->saturdayopen ? date("h:i A", strtotime($hours->saturdaystart)) : "Closed",
			               "end_time"   => $hours->saturdayopen ? date("h:i A", strtotime($hours->saturdayend)) : "Closed"],
			              ["term_name"  => "Sunday",
			               "start_time" => $hours->sundayopen ? date("h:i A", strtotime($hours->sundaystart)) : "Closed",
			               "end_time"   => $hours->sundayopen ? date("h:i A", strtotime($hours->sundayend)) : "Closed"]];
			return apiSuccessResult($tempHours, "Store Working Hours:" . $store->id);
		}
	}
