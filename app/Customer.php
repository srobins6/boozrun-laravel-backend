<?php
	namespace App;
	
	use Illuminate\Database\Query\Builder;
	use Illuminate\Foundation\Auth\User as Authenticatable;
	use Storage;
	
	/**
	 * App\Customer
 
*
*@property integer                                                    $id
	 * @property string                                                     $email
	 * @property string                                                     $password
	 * @property string                                                     $name
	 * @property string                                                     $birthday
	 * @property string                                                     $phone
	 * @property string                                                     $address
	 * @property float                                                      $latitude
	 * @property float                                                      $longitude
	 * @property string                                                     $stripe_id
	 * @property string                                                     $remember_token
	 * @property \Carbon\Carbon                                             $created_at
	 * @property \Carbon\Carbon                                             $updated_at
	 * @property-read mixed                                                 $current_order
	 * @property-read mixed                                                 $current_orders
	 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
	 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Promo[] $usedPromos
	 * @method static Builder|Customer whereId($value)
	 * @method static Builder|Customer whereEmail($value)
	 * @method static Builder|Customer wherePassword($value)
	 * @method static Builder|Customer whereName($value)
	 * @method static Builder|Customer whereBirthday($value)
	 * @method static Builder|Customer wherePhone($value)
	 * @method static Builder|Customer whereAddress($value)
	 * @method static Builder|Customer whereLatitude($value)
	 * @method static Builder|Customer whereLongitude($value)
	 * @method static Builder|Customer whereProfileImage($value)
	 * @method static Builder|Customer whereStripeId($value)
	 * @method static Builder|Customer whereRememberToken($value)
	 * @method static Builder|Customer whereCreatedAt($value)
	 * @method static Builder|Customer whereUpdatedAt($value)
	 * @mixin \Eloquent
	 * @mixin \Eloquent
	 * @mixin \Eloquent
	 * @property-read mixed                                                 $submitted
	 * @property-read mixed                                                 $packed
	 * @property-read mixed                                                 $delivering
	 * @property-read mixed                                                 $delivered
	 * @property-read mixed                                                 $cancelled
	 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Promo[] $promos
	 * @property string                                                     $fb_id
	 * @property string                                                     $temp_fb_id
	 * @property-read mixed                                                 $image
	 * @method static Builder|Customer whereFbId($value)
	 * @method static Builder|Customer whereTempFbId($value)
	 */
	class Customer extends Authenticatable {
		
		/**
		 * Attributes that should be mutated as dates.
		 *
		 * @var array
		 */
		protected $dates = ["created_at",
		                    "updated_at",
		                    "birthday"];
		
		/**
		 * The attributes that are  mass assignable.
		 *
		 * @var array
		 */
		
		
		
		
		protected $fillable = ["email",
		                       "password",
		                       "name",
		                       "birthday",
		                       "phone",
		                       "address",
		                       "latitude",
		                       "longitude",
		                       "stripe_id",
		                       "fb_id"];
		
		/**
		 * The attributes excluded from the model's JSON form.
		 *
		 * @var array
		 */
		protected $hidden = ["password",
		                     "remember_token",
		                     "stripe_id",
		                     "fb_id"];
		
		/**
		 * Gets the path to the profile image for the customer.
		 *
		 * @return string
		 */
		public function getImageAttribute() {
			return "profile_images/" . $this->id . ".png";
		}
		
		/**
		 * Get the most recent active order for the customer.
		 *
		 * @return mixed
		 */
		public function getCurrentOrderAttribute() {
			return $this->orders()->where("status", "!=", "cart")->where("status", "!=", "cancelled")->get()
			            ->sortByDesc("updated_at")->first();
		}
		
		/**
		 * Get all active orders for the customer.
		 *
		 * @return mixed
		 */
		public function getCurrentOrdersAttribute() {
			return $this->orders()->where("status", "!=", "cart")->where("status", "!=", "cancelled")->get()
			            ->sortBy("updated_at")->get();
		}
		
		/**
		 * Method runs on model initialization.
		 */
		public static function boot() {
			parent::boot();
			Customer::creating(function ($customer) {
				\Stripe\Stripe::setApiKey(config("services")["stripe"]["secret"]);
				$stripeCustomer = \Stripe\Customer::create();
				$customer->stripe_id = $stripeCustomer["id"];
			});
			Customer::created(function ($customer) {
				if (!Storage::disk("public")->exists($customer->image)) {
					Storage::disk("public")->copy("profile_images/default.png", $customer->image);
				}
			});
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
		 * Customers can have many orders.
		 */
		public function orders() {
			return $this->hasMany('App\Order');
		}
		
		/**
		 * Customers can have many used promos.
		 */
		public function promos() {
			return $this->belongsToMany('App\Promo');
		}
		
		/**
		 * Overrides the default setting of a customer's address.
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
				} else {
					return false;
				}
			}
			return true;
		}
		
		/**
		 * Encrypt the user's password.
		 *
		 * @param $password
		 */
		public function setPasswordAttribute($password) {
			$this->attributes["password"] = bcrypt($password);
		}
		
		/**
		 * @param $fb_id
		 */
		public function setFbIdAttribute($fb_id){
			$this->attributes["fb_id"] = bcrypt($fb_id);
		}
	}
