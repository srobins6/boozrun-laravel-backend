<?php
	namespace App;
	
	use Illuminate\Foundation\Auth\User as Authenticatable;
	
	/**
 * App\Driver
 *
 * @property integer                                                    $id
 * @property string                                                     $email
 * @property string                                                     $password
 * @property string                                                     $name
 * @property string                                                     $city
 * @property string                                                     $phone
 * @property boolean                                                    $confirmed
 * @property boolean                                                    $active
 * @property boolean                                                    $crime
 * @property string                                                     $crime_details
 * @property boolean                                                    $accidents
 * @property string                                                     $accidents_details
 * @property boolean                                                    $violations
 * @property string                                                     $violations_details
 * @property string                                                     $remember_token
 * @property \Carbon\Carbon                                             $created_at
 * @property \Carbon\Carbon                                             $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Store[] $stores
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @method static Driver find($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|Driver wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereName($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|Driver wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereConfirmed($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereCrime($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereCrimeDetails($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereAccidents($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereAccidentsDetails($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereViolations($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereViolationsDetails($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Driver whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin \Eloquent
 * @mixin \Eloquent
 * @property-read mixed                                                 $tips
 */
	class Driver extends Authenticatable {
		
		/**
		 * The attributes that are  mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = ["email",
		                       "password",
		                       "name",
		                       "city",
		                       "phone",
		                       "confirmed",
		                       "active",
		                       "crime",
		                       "crime_details",
		                       "accidents",
		                       "accidents_details",
		                       "violations",
		                       "violations_details"];
		
		/**
		 * The attributes excluded from the model's JSON form.
		 *
		 * @var array
		 */
		protected $hidden = ["password",
		                     "remember_token"];
		
		/**
		 * Driver can have many stores, which can have many drivers.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
		 */
		public function stores() {
			return $this->belongsToMany('App\Store');
		}
		
		/**
		 * Driver can have many orders.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\HasMany
		 */
		public function orders() {
			return $this->hasMany('App\Order');
		}
		
		/**
		 * Encrypts the password when it is set.
		 *
		 * @param $password
		 */
		public function setPasswordAttribute($password) {
			$this->attributes["password"] = bcrypt($password);
		}
		
		/**
		 * @return array
		 */
		public function getTipsAttribute() {
			$tips = [];
			foreach ($this->stores as $store) {
				$orders =
					$this->orders->where("status", "delivered")->where("store_id", $store->id)->groupBy(function ($item) {
						return $item->submitted_at->toFormattedDateString();
					});
				$tips[$store->id] = [];
				foreach ($orders as $date => $orderGroup) {
					$tip = 0;
					foreach ($orderGroup as $order) {
						$tip += $order->tip;
					}
					$tips[$store->id][$date] = $tip;
				}
			}
			return $tips;
		}
	}
