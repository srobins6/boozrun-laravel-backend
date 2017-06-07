<?php
	use App\Result;
	use App\Store;
	use Illuminate\Http\RedirectResponse;
	use Illuminate\Http\Request;
	use Illuminate\Support\Collection;
	
	/**
	 * Debug data to a log.
	 *
	 * @param mixed  $data
	 * @param string $mode
	 */
	function log_debug($data, $mode = "a") {
		ob_start();
		var_dump($data);
		$data = ob_get_clean() . "\n";
		$data .= "<br><br><br>";
		$myfile = fopen("../log.html", $mode);
		fwrite($myfile, $data);
	}
	
	/**
	 * Debug data.
	 *
	 * @param mixed $data
	 */
	function debug($data) {
		ob_start();
		var_dump($data);
		$data = ob_get_clean() . "\n<br><br>";
		echo $data;
	}
	
	/**
	 * Parse an address into componants.
	 *
	 * @param $address
	 *
	 * @return mixed
	 */
	function addressParse($address) {
		$re = "/(?'street'[^,]*), (?'city'[^,]*), (?'state'\\w*) (?'zipcode'\\w*)/";
		preg_match($re, $address, $matches);
		return $matches;
	}
	
	/**
	 * Gets the list of stores within a specified distance.
	 *
	 * @param float   $latitude
	 * @param float   $longitude
	 * @param integer $miles
	 * @param bool    $demo
	 *
	 * @return Collection
	 */
	function getLocalStores($latitude = 0.0, $longitude = 0.0, $miles = 4, $demo = true) {
		$stores = Store::whereActive(1)->get()->filter(function ($store) use ($longitude, $latitude, $miles, $demo) {
			if ($store->id == 1) {
				$store->distance = 100000;
				return $demo;
			}
			$store->distance = distance($latitude, $longitude, $store->latitude, $store->longitude, "m");
			return $store->distance <= $miles;
		});
		$stores = $stores->sortBy("distance");
		return $stores;
	}
	
	/**
	 * Gets the distance between two gps coordinate pairs.
	 *
	 * @param float  $lat1
	 * @param float  $lon1
	 * @param float  $lat2
	 * @param float  $lon2
	 * @param string $unit
	 *
	 * @return float
	 */
	function distance($lat1, $lon1, $lat2, $lon2, $unit) {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);
		if ($unit == "K") {
			return ($miles * 1.609344);
		} else {
			if ($unit == "N") {
				return ($miles * 0.8684);
			} else {
				return $miles;
			}
		}
	}
	
//	/**
//	 * Gets the gps coordinates from a given address.
//	 *
//	 * @param string $input
//	 *
//	 * @return null|Address
//	 */
//	function coords($input) {
//		$addressModel = Address::find($input);
//		if ($addressModel) {
//			return $addressModel;
//		} else {
//			$queryParams = ["address" => $input,
//			                "key"     => env("GOOGLE_KEY")];
//			$query = http_build_query($queryParams);
//			$url = "https://maps.googleapis.com/maps/api/geocode/json?$query";
//			$response = json_decode(file_get_contents($url));
//			if ($response->status == "OK") {
//				return Address::create(["input"     => $input,
//				                        "address"   => $response->results [0]->formatted_address,
//				                        "latitude"  => $response->results [0]->geometry->location->lat,
//				                        "longitude" => $response->results [0]->geometry->location->lng]);
//			} else {
//				return null;
//			}
//		}
//	}
//
	/**
	 * Generate an error result for the old api.
	 *
	 * @param string $message
	 * @param mixed  $data
	 *
	 * @return array
	 */
	function apiSuccessResult($data, $message) {
		return apiResult("success", $data, $message);
	}
	
	/**
	 * Generate an error result for the old api.
	 *
	 * @param string $message
	 * @param mixed  $data
	 *
	 * @return array
	 */
	function apiErrorResult($data, $message) {
		return apiResult("Error", $data, $message);
	}
	
	/**
	 * Generate a result for the old api.
	 *
	 * @param string $status
	 * @param string $message
	 * @param mixed  $data
	 *
	 * @return array
	 */
	function apiResult($status, $data, $message) {
		$result = ["status"  => $status,
		           "message" => $message,
		           "data"    => $data];
		Log::debug($result);
		return $result;
	}
	
	/**
	 * Generate a success result.
	 *
	 * @param string       $message
	 * @param Request|null $request
	 * @param mixed        $data
	 *
	 * @return Result|RedirectResponse
	 */
	function successResult($message, Request $request = null, $data = []) {
		return result(true, $message, "success", $request, $data);
	}
	
	/**
	 * Generate an error result.
	 *
	 * @param string       $message
	 * @param Request|null $request
	 * @param mixed        $data
	 *
	 * @return Result|RedirectResponse
	 */
	function errorResult($message, Request $request = null, $data = []) {
		return result(false, $message, "danger", $request, $data);
	}
	
	/**
	 * Generate a result.
	 *
	 * @param string  $status
	 * @param string  $message
	 * @param string  $alertType
	 * @param Request $request
	 * @param mixed   $data
	 *
	 * @return Result|\Illuminate\Contracts\Routing\ResponseFactory|RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	function result($status, $message, $alertType = "info", Request $request = null, $data = []) {
		if (!is_array($data)) {
			$data = ["originalData" => $data];
		}
		if ($message) {
			$message = rtrim($message);
			if (!preg_match("/([.!?])$/", $message, $matches) && strlen($message) > 0) {
				$message = $message . ".";
			}
		}
		if ($request && !$request->ajax()) {
			if ($message) {
				addAlert($request, $message, $alertType);
			}
			if (isset($data["redirectUrl"])) {
				$redirect = redirect($data["redirectUrl"]);
			} else {
				$redirect = back();
			}
			$result = $redirect;
		} else {
			if (isset($data["redirectUrl"]) && $message) {
				addAlert($request, $message, $alertType);
			}
			$result = new Result ();
			$result->data = $data;
			if ($message) {
				$result->message = $message;
				$result->alertType = $alertType;
			}
			if ($request && $request->ajax()) {
				if (!$status) {
					return response($result, 404);
				} else {
					return response($result);
				}
			} else {
				$result->status = $status;
			}
		}
		return $result;
	}
	
	/**
	 * Add an alert to the current session.
	 *
	 * @param Request $request
	 * @param string  $message
	 * @param string  $alertType
	 */
	function addAlert(Request $request, $message = "", $alertType = "info") {
		$request->session()->flash("alert", $message);
		$request->session()->flash("alertType", $alertType);
	}
	
	/**
	 * Get a list of cities with active stores in them.
	 *
	 * @return mixed
	 */
	function getCities() {
		$stores = getActiveStores();
		return $stores->keyBy("city")->forget("")->pluck("city")->unique();
	}
	
	/**
	 * @param mixed $id
	 *
	 * @return string
	 */
	function storeNotFound($id) {
		return "Store $id not found";
	}
	
	/**
	 * @param mixed $id
	 *
	 * @return string
	 */
	function orderNotFound($id) {
		return "Order $id not found";
	}
	
	/**
	 * @param mixed $id
	 *
	 * @return string
	 */
	function adminNotFound($id) {
		return "Admin $id not found";
	}
	
	/**
	 * @param mixed $id
	 *
	 * @return string
	 */
	function driverNotFound($id) {
		return "Driver $id not found";
	}
	
	/**
	 * @param mixed $id
	 *
	 * @return string
	 */
	function hoursNotFound($id) {
		return "Hours $id not found";
	}
	
	/**
	 * @param mixed $id
	 *
	 * @return string
	 */
	function customerNotFound($id) {
		return "Customer $id not found";
	}
	
	/**
	 * @param mixed $id
	 *
	 * @return string
	 */
	function productNotFound($id) {
		return "Product $id not found";
	}
	
	/**
	 * @param mixed $id
	 *
	 * @return string
	 */
	function categoryNotFound($id) {
		return "Category $id not found";
	}
	
	/**
	 * @param $id
	 *
	 * @return string
	 */
	function imageNotFound($id) {
		return "Image $id not found";
	}
	
	//	/**
	//	 * Create an image from an uploaded file.
	//	 *
	//	 * @param       $fileOriginalExtension
	//	 * @param       $fileOriginalName
	//	 * @param       $filePathName
	//	 * @param null  $name
	//	 * @param bool  $default
	//	 *
	//	 * @return Image
	//	 */
	//	function createImage($fileOriginalExtension, $fileOriginalName, $filePathName, $name = null, $default = false) {
	//		if ($name) {
	//			$fileName = strtolower($name) . ".png";
	//		} else {
	//			$fileName = strtolower(str_replace("." . $fileOriginalExtension, ".png", $fileOriginalName));
	//		}
	//		$imageSize = getimagesize($filePathName);
	//		$width = $imageSize[0];
	//		$height = $imageSize[1];
	//		$image = imagecreatefromstring(file_get_contents($filePathName));
	//		$ratio = $height / $width;
	//		$fullImage = imagecreatetruecolor(500, 500);
	//		$white = imagecolorallocate($fullImage, 255, 255, 255);
	//		imagefilledrectangle($fullImage, 0, 0, 500, 500, $white);
	//		if ($width > $height) {
	//			$fullWidth = 460;
	//			$fullHeight = $fullWidth * $ratio;
	//			$full = imagescale($image, $fullWidth, $fullHeight);
	//			imagecopy($fullImage, $full, (500 - $fullWidth) / 2, (500 - $fullHeight) / 2, 0, 0, $fullWidth, $fullHeight);
	//		} else {
	//			$fullHeight = 460;
	//			$fullWidth = $fullHeight / $ratio;
	//			$full = imagescale($image, $fullWidth, $fullHeight);
	//			imagecopy($fullImage, $full, (500 - $fullWidth) / 2, (500 - $fullHeight) / 2, 0, 0, $fullWidth, $fullHeight);
	//		}
	//		$fullImageFile = storage_path("app/public/product_images/full/" . $fileName);
	//		imagepng($fullImage, $fullImageFile);
	//		$smallImage = imagescale($fullImage, 100, 100);
	//		$smallImageFile = storage_path("app/product_images/small/" . $fileName);
	//		imagepng($smallImage, $smallImageFile);
	//		$data = ["full"    => "/product_images/full/" . $fileName,
	//		         "small"   => "/product_images/small/" . $fileName,
	//		         "default" => $default];
	//		if ($name) {
	//			$data["name"] = $name;
	//		}
	//		$image = Image::create($data);
	//		return $image;
	//	}
	
	/**
	 * Generate a random password of specified length.
	 *
	 * @param int $length
	 *
	 * @return string
	 */
	function generatePassword($length = 8) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$count = mb_strlen($chars);
		for ($i = 0, $result = ""; $i < $length; $i++) {
			$index = rand(0, $count - 1);
			$result .= mb_substr($chars, $index, 1);
		}
		return $result;
	}
	
	/**
	 * Create a fillename from existing paramaters.
	 *
	 * @param string      $name
	 * @param string|null $extension
	 *
	 * @return string
	 */
	function fileName($name, $extension = null) {
		$re = "/(?'name'.*)\\.?(?'extension'.*)$/";
		preg_match($re, $name, $matches);
		if (!$extension && isset($matches["extension"])) {
			$extension = $matches["extension"];
		} elseif (!$extension) {
			$extension = "txt";
		}
		$name = preg_replace("/\\s+/", "", strtolower($matches["name"]));
		$name = $name . "." . $extension;
		return $name;
	}
	
	/**
	 * Get a list of active real stores.
	 *
	 * @return mixed
	 */
	function getActiveStores() {
		$stores = Store::where("active", "1")->get()->except(1);
		return $stores;
	}
	
	/**
	 * Generates the bootstrap responsive class for the number of columns
	 *
	 * @param $numCols
	 *
	 * @return string
	 */
	function colsClass($numCols) {
		$lg = min($numCols, 12);
		$md = min($numCols + 2, 12);
		$sm = min($numCols + 4, 12);
		$xs = min($numCols + 6, 12);
		$lgo = (12 - $lg) / 2;
		$mdo = (12 - $md) / 2;
		$smo = (12 - $sm) / 2;
		$xso = (12 - $xs) / 2;
		return "col-lg-$lg col-md-$md col-sm-$sm col-xs-$xs col-lg-offset-$lgo col-md-offset-$mdo
				     col-sm-offset-$smo col-xs-offset-$xso";
	}
	
	/**
	 * @param        $latitude
	 * @param        $longitude
	 * @param string $username
	 *
	 * @return string
	 */
	function get_timezone($latitude, $longitude, $username = "boozrun") {
		//connect to web service
		$url = 'http://ws.geonames.org/timezone?lat=' . $latitude . '&lng=' . $longitude . '&style=full&username=' .
			urlencode($username);
		$xml = file_get_contents($url);
		//		$ch = curl_init();
		//		curl_setopt($ch, CURLOPT_URL, $url);
		//		curl_setopt($ch, CURLOPT_HEADER, false);
		//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//		$xml = curl_exec($ch);
		//		curl_close($ch);
		//		if (!$xml) {
		//			$GLOBALS['error'] = 'The GeoNames service did not return any data: ' . $url;
		//			return false;
		//		}
		
		//parse XML response
		$data = new SimpleXMLElement($xml);
		//echo '<pre>'.print_r($data,true).'</pre>'; die();
		$timezone = trim(strip_tags($data->timezone->timezoneId));
		return $timezone;
	}
	
	/**
	 * @param        $cur_lat
	 * @param        $cur_long
	 * @param string $country_code
	 *
	 * @return mixed|string
	 * from stackexchange
	 */
	function get_nearest_timezone($cur_lat, $cur_long, $country_code = '') {
		$timezone_ids = ($country_code) ? DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $country_code) :
			DateTimeZone::listIdentifiers();
		
		if ($timezone_ids && is_array($timezone_ids) && isset($timezone_ids[0])) {
			
			$time_zone = '';
			$tz_distance = 0;
			
			//only one identifier?
			if (count($timezone_ids) == 1) {
				$time_zone = $timezone_ids[0];
			} else {
				
				foreach ($timezone_ids as $timezone_id) {
					$timezone = new DateTimeZone($timezone_id);
					$location = $timezone->getLocation();
					$tz_lat = $location['latitude'];
					$tz_long = $location['longitude'];
					
					$theta = $cur_long - $tz_long;
					$distance = (sin(deg2rad($cur_lat)) * sin(deg2rad($tz_lat))) +
						(cos(deg2rad($cur_lat)) * cos(deg2rad($tz_lat)) * cos(deg2rad($theta)));
					$distance = acos($distance);
					$distance = abs(rad2deg($distance));
					// echo '<br />'.$timezone_id.' '.$distance;
					
					if (!$time_zone || $tz_distance > $distance) {
						$time_zone = $timezone_id;
						$tz_distance = $distance;
					}
				}
			}
			return $time_zone;
		}
		return 'unknown';
	}
	
	/**
	 * @param $id
	 *
	 * @return string
	 */
	function promoNotFound($id) {
		return "Promo $id not found";
	}