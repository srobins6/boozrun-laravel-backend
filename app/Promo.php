<?php
	namespace App;

	use Illuminate\Database\Eloquent\Model;

	/**
 * App\Promo
 *
 * @property integer                                                       $id
 * @property string                                                        $code
 * @property boolean                                                       $active
 * @property string                                                        $type
 * @property float                                                         $amount
 * @property \Carbon\Carbon                                                $created_at
 * @property \Carbon\Carbon                                                $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Customer[] $usedBy
 * @method static Promo find($value)
 * @method static \Illuminate\Database\Query\Builder|Promo whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Promo whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|Promo whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|Promo whereType($value)
 * @method static \Illuminate\Database\Query\Builder|Promo whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|Promo whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Promo whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin \Eloquent
 * @property boolean $reusable
 * @property \Carbon\Carbon $expiration_date
 * @property \Illuminate\Database\Eloquent\Collection|\App\Store[] $stores
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @method static \Illuminate\Database\Query\Builder|Promo whereReusable($value)
 * @method static \Illuminate\Database\Query\Builder|Promo whereExpirationDate($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Customer[] $customers
 */
	class Promo extends Model {

		private $tempStores;

		/**
		 * Attributes that should be mutated as dates.
		 *
		 * @var array
		 */
		protected $dates = ["created_at",
		                    "updated_at",
		                    "expiration_date"];

		/**
		 * The attributes that are not mass assignable.
		 *
		 * @var array
		 */
		protected $guarded = [];
		
		/**
		 * @param $stores
		 */
		public function setStoresAttribute($stores) {
			$this->tempStores = $stores;
		}
		
		/**
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
		 */
		public function stores() {
			return $this->belongsToMany('App\Store');
		}
		
		/**
		 * @return \Illuminate\Database\Eloquent\Relations\HasMany
		 */
		public function orders() {
			return $this->hasMany('App\Order');
		}

		/**
		 * Promos can be used by many customers
		 */
		public function customers() {
			return $this->belongsToMany('App\Customer');
		}

		public static function boot() {
			parent::boot();
			Promo::saved(function ($promo) {
				if ($promo->tempStores) {
					$promo->stores()->sync($promo->tempStores);
					unset($promo->tempStores);
				}
			});
		}
	}
