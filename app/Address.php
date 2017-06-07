<?php
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	/**
 * Class Address
 *
 * @package App
 * @property string         $input
 * @property string         $address
 * @property float          $latitude
 * @property float          $longitude
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|Address whereInput($value)
 * @method static \Illuminate\Database\Query\Builder|Address whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|Address whereLatitude($value)
 * @method static \Illuminate\Database\Query\Builder|Address whereLongitude($value)
 * @method static \Illuminate\Database\Query\Builder|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Address whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Address extends Model {
		
		/**
		 * The attributes that are  mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = ["input",
		                       "latitude",
		                       "address",
		                       "longitude"];
		
		public $incrementing = false;
		
		public $primaryKey = "input";
		
		/**
		 * @param $input
		 *
		 * @return null|static
		 */
		public static function firstOrCreateFromInput($input) {
			$addressModel = Address::find($input);
			if ($addressModel) {
				return $addressModel;
			} else {
				$queryParams = ["address" => $input,
				                "key"     => env("GOOGLE_KEY")];
				$query = http_build_query($queryParams);
				$url = "https://maps.googleapis.com/maps/api/geocode/json?$query";
				$response = json_decode(file_get_contents($url));
				if ($response->status == "OK") {
					return Address::create(["input"     => $input,
					                        "address"   => $response->results [0]->formatted_address,
					                        "latitude"  => $response->results [0]->geometry->location->lat,
					                        "longitude" => $response->results [0]->geometry->location->lng]);
				} else {
					return null;
				}
			}
		}
	}
