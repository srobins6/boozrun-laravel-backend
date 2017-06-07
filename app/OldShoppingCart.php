<?php
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	/**
 * App\OldShoppingCart
 *
 * @property string          $session_id
 * @property integer         $order_id
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 * @property-read \App\Order $order
 * @method static OldShoppingCart find($value)
 * @method static \Illuminate\Database\Query\Builder|OldShoppingCart whereSessionId($value)
 * @method static \Illuminate\Database\Query\Builder|OldShoppingCart whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|OldShoppingCart whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|OldShoppingCart whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin \Eloquent
 * @mixin \Eloquent
 */
	class OldShoppingCart extends Model {
		
		/**
		 * The attributes that are not mass assignable.
		 *
		 * @var array
		 */
		protected $guarded = [];
		
		/**
		 * Primary key for shopping cart database table.
		 *
		 * @var string
		 */
		protected $primaryKey = "session_id";
		
		/**
		 * Runs on model initialization, handles creating an order instance for the shopping cart.
		 */
		public static function boot() {
			parent::boot();
			OldShoppingCart::creating(function ($oldShoppingCart) {
				$tempOrder = Order::create([]);
				$oldShoppingCart->order()->associate($tempOrder);
			});
		}
		
		/**
		 * Shopping cart belongs to one order.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
		public function order() {
			return $this->belongsTo('App\Order');
		}
	}
