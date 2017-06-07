<?php
	namespace App;
	
	use Illuminate\Foundation\Auth\User as Authenticatable;
	
	/**
 * App\Store
 *
 * @property integer                                                      $id
 * @property string                                                       $email
 * @property string                                                       $password
 * @property string                                                       $owner_name
 * @property string                                                       $name
 * @property string                                                       $phone
 * @property string                                                       $address
 * @property string                                                       $city
 * @property float                                                        $latitude
 * @property float                                                        $longitude
 * @property float                                                        $taxrate
 * @property float                                                        $delivery
 * @property float                                                        $fixed_fee
 * @property float                                                        $percent_fee
 * @property string                                                       $stripe_id
 * @property string                                                       $stripe_key
 * @property string                                                       $stripe_secret
 * @property boolean                                                      $stripe_verified
 * @property boolean                                                      $product_control
 * @property boolean                                                      $active
 * @property string                                                       $remember_token
 * @property \Carbon\Carbon                                               $created_at
 * @property \Carbon\Carbon                                               $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Driver[]  $drivers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[]   $orders
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @property mixed                                                        $active_hours
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Hours[]   $hours
 * @property-read mixed                                                   $submitted
 * @property-read mixed                                                   $packed
 * @property-read mixed                                                   $delivering
 * @property-read mixed                                                   $delivered
 * @property-read mixed                                                   $cancelled
 * @method static Store find($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|Store wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereOwnerName($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereName($value)
 * @method static \Illuminate\Database\Query\Builder|Store wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereLatitude($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereLongitude($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereTaxrate($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereDelivery($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereFixedFee($value)
 * @method static \Illuminate\Database\Query\Builder|Store wherePercentFee($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereStripeId($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereStripeKey($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereStripeSecret($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereStripeVerified($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereProductControl($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereContract($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Store whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Promo[]   $promos
 * @mixin \Eloquent
 * @property-read mixed                                                   $open
 */
	class Store extends Authenticatable {
		
		/**
		 * The attributes that are  mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = ["email",
		                       "password",
		                       "owner_name",
		                       "name",
		                       "phone",
		                       "address",
		                       "city",
		                       "latitude",
		                       "longitude",
		                       "taxrate",
		                       "delivery",
		                       "fixed_fee",
		                       "percent_fee",
		                       "stripe_id",
		                       "stripe_key",
		                       "stripe_secret",
		                       "stripe_verified",
		                       "product_control",
		                       "active"];
		
		/**
		 * The attributes excluded from the model's JSON form.
		 *
		 * @var array
		 */
		protected $hidden = ["password",
		                     "remember_token",
		                     "stripe_id",
		                     "stripe_secret",
		                     "stripe_key",
		                     "stripe_verified"];
		
		
		/**
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
		 */
		public function promos() {
			return $this->belongsToMany('App\Promo');
		}
		
		/**
		 * Method runs on model initialization.
		 */
		public static function boot() {
			parent::boot();
			Store::creating(function ($store) {
				\Stripe\Stripe::setApiKey(config("services")["stripe"]["secret"]);
				$stripeStore = \Stripe\Account::create(["country" => "US",
				                                        "managed" => true]);
				$store->stripe_id = $stripeStore["id"];
				$store->stripe_key = $stripeStore["keys"]["publishable"];
				$store->stripe_secret = $stripeStore["keys"]["secret"];
			});
			Store::created(function ($store) {
				$store->hours()->create(["name"   => "Default",
				                         "active" => true]);
				$store->hours()->create(["name"   => "Break",
				                         "active" => false]);
			});
		}
		
		/**
		 * Store can have many drivers, which can have many stores.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
		 */
		public function drivers() {
			return $this->belongsToMany('App\Driver');
		}
		
		/**
		 * @return bool
		 */
		public function getOpenAttribute() {
			$day = strtolower(date('l'));
			$dayOpen = $day . "open";
			$dayStart = $day . "start";
			$dayEnd = $day . "end";
			$startString = $this->active_hours->$dayStart;
			$endString = $this->active_hours->$dayEnd;
			preg_match("/(\\d+):(\\d+):(\\d+)/", $startString, $startMatches);
			preg_match("/(\\d+):(\\d+):(\\d+)/", $endString, $endMatches);
			$start = 3600 * intval($startMatches[1]) + 60 * $startMatches[2] + $startMatches[3];
			$end = 3600 * intval($endMatches[1]) + 60 * $endMatches[2] + $endMatches[3];
			if ($end < $start) {
				$end += 86400;
			}
			$now = 3600 * intval(date('G')) + 60 * intval(date('i')) + intval(date('s'));
			if ($now < $start) {
				$day = strtolower(date('l', strtotime("yesterday")));
				$dayOpen = $day . "open";
				$dayStart = $day . "start";
				$dayEnd = $day . "end";
				$startString = $this->active_hours->$dayStart;
				$endString = $this->active_hours->$dayEnd;
				preg_match("/(\\d+):(\\d+):(\\d+)/", $startString, $startMatches);
				preg_match("/(\\d+):(\\d+):(\\d+)/", $endString, $endMatches);
				$start = 3600 * intval($startMatches[1]) + 60 * $startMatches[2] + $startMatches[3];
				$end = 3600 * intval($endMatches[1]) + 60 * $endMatches[2] + $endMatches[3];
				if ($end < $start) {
					$end += 86400;
				}
			}
			return ($now <= $end && $now >= $start && $dayOpen);
		}
		
		/**
		 * Store can have many orders.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\HasMany
		 */
		public function orders() {
			return $this->hasMany('App\Order');
		}
		
		/**
		 * Store can have many products.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\HasMany
		 */
		public function products() {
			return $this->hasMany('App\Product');
		}
		
		/**
		 * Gets the current active hours for the store.
		 */
		public function getActiveHoursAttribute() {
			return $this->hours()->where("active", true)->first();
		}
		
		/**
		 * Store can have multiple sets of hours.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\HasMany
		 */
		public function hours() {
			return $this->hasMany('App\Hours');
		}
		
		/**
		 * Sets the current active hours for the store.
		 *
		 * @param $id
		 */
		public function setActiveHoursAttribute($id) {
			$newHours = $this->hours->find($id);
			if ($newHours) {
				$currentActiveHours = $this->hours()->where("active", true)->get();
				foreach ($currentActiveHours as $hours) {
					$hours->active = false;
					$hours->save();
				}
				$newHours->active = true;
				$newHours->save();
			}
		}
		
		/**
		 * @return static
		 */
		public function getSubmittedAttribute() {
			return $this->orders->where("status", "submitted");
		}
		
		/**
		 * @return static
		 */
		public function getPackedAttribute() {
			return $this->orders->where("status", "packed");
		}
		
		/**
		 * @return static
		 */
		public function getDeliveringAttribute() {
			return $this->orders->where("status", "delivering");
		}
		
		/**
		 * @return static
		 */
		public function getDeliveredAttribute() {
			return $this->orders->where("status", "delivered");
		}
		
		/**
		 * @return static
		 */
		public function getCancelledAttribute() {
			return $this->orders->where("status", "cancelled");
		}
		
		/**
		 * Overrides the default setting of a stores's address.
		 *
		 * @param string $newAddress
		 *
		 * @return boolean
		 */
		public function setAddressAttribute($newAddress) {
			if (!isset ($this->original) || !isset ($this->original ["address"]) ||
				$this->original ["address"] != $newAddress
			) {
				$coords = Address::firstOrCreateFromInput($newAddress);
				if ($coords) {
					$this->attributes ["address"] = $coords->address;
					$this->attributes ["longitude"] = $coords->longitude;
					$this->attributes ["latitude"] = $coords->latitude;
					$parsedAddress = addressParse($this->attributes["address"]);
					if (isset($parsedAddress["city"])) {
						$this->attributes["city"] = $parsedAddress["city"];
					}
				} else {
					return false;
				}
			}
			return true;
		}
		
		/**
		 * Encrypts the password when it is set.
		 *
		 * @param $password
		 */
		public function setPasswordAttribute($password) {
			$this->attributes["password"] = bcrypt($password);
		}
	}
