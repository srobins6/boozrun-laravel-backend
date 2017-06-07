<?php
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	use Mail;
	
	/**
	 * App\Order
	 *
	 * @property integer                                                      $driver_id
	 * @property integer                                                      $store_id
	 * @property integer                                                      $customer_id
	 * @property integer                                                      $id
	 * @property float                                                        $total
	 * @property float                                                        $subtotal
	 * @property float                                                        $tip
	 * @property float                                                        $tax
	 * @property string                                                       $status
	 * @property string                                                       $address
	 * @property string                                                       $name
	 * @property string                                                       $phone
	 * @property string                                                       $notes
	 * @property string                                                       $stripe_id
	 * @property \Carbon\Carbon                                               $submitted_at
	 * @property \Carbon\Carbon                                               $packed_at
	 * @property \Carbon\Carbon                                               $delivering_at
	 * @property \Carbon\Carbon                                               $delivered_at
	 * @property \Carbon\Carbon                                               $created_at
	 * @property \Carbon\Carbon                                               $updated_at
	 * @property \Carbon\Carbon                                               $cancelled_at
	 * @property-read \App\Driver                                             $driver
	 * @property-read \App\Store                                              $store
	 * @property-read \App\Customer                                           $customer
	 * @property-read \App\OldShoppingCart                                    $oldShoppingCart
	 * @property-read mixed                                                   $items
	 * @property-read mixed                                                   $delivery
	 * @property-read mixed                                                   $current_cost
	 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
	 * @method static Order find($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereDriverId($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereStoreId($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereCustomerId($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereId($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereTotal($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereSubtotal($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereTip($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereTax($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereStatus($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereAddress($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereName($value)
	 * @method static \Illuminate\Database\Query\Builder|Order wherePhone($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereNotes($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereStripeId($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereSubmittedAt($value)
	 * @method static \Illuminate\Database\Query\Builder|Order wherePackedAt($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereDeliveringAt($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereDeliveredAt($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereCreatedAt($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereUpdatedAt($value)
	 * @method static \Illuminate\Database\Query\Builder|Order whereCancelledAt($value)
	 * @mixin \Eloquent
	 * @mixin \Eloquent
	 * @property integer                                                      $promo_id
	 * @property-read \App\Promo                                              $promo
	 * @method static \Illuminate\Database\Query\Builder|Order wherePromoId($value)
	 * @mixin \Eloquent
	 */
	class Order extends Model {
		
		/**
		 * The attributes that are not mass assignable.
		 *
		 * @var array
		 */
		protected $guarded = [];
		
		/**
		 * All the possible statuses for the order.
		 *
		 * @var array
		 */
		public static $statuses = ["cart",
		                           "submitted",
		                           "packed",
		                           "delivering",
		                           "delivered",
		                           "cancelled"];
		
		/**
		 * Attributes that should be mutated as dates.
		 *
		 * @var array
		 */
		protected $dates = ["created_at",
		                    "updated_at",
		                    "delivering_at",
		                    "delivered_at",
		                    "submitted_at",
		                    "packed_at",
		                    "cancelled_at"];
		
		/**
		 * Order belongs to one driver.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
		public function driver() {
			return $this->belongsTo('App\Driver');
		}
		
		/**
		 * Order has at most one promo.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
		public function promo() {
			return $this->belongsTo('App\Promo');
		}
		
		/**
		 * Order belongs to one store.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
		public function store() {
			return $this->belongsTo('App\Store');
		}
		
		/**
		 * Order belongs to one customer.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
		public function customer() {
			return $this->belongsTo('App\Customer');
		}
		
		/**
		 * Order has one shopping cart, for iPhone app compatibility.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\HasOne
		 */
		public function oldShoppingCart() {
			return $this->hasOne('App\OldShoppingCart');
		}
		
		public function packed() {
			$this->status = "packed";
			$this->packed_at = time();
			$this->save();
		}
		
		/**
		 * @param $driver
		 */
		public function delivering($driver) {
			$this->status = "delivering";
			$this->delivering_at = time();
			$this->driver()->associate($driver);
			$this->save();
		}
		
		public function delivered() {
			$this->status = "delivered";
			$this->delivered_at = time();
			$this->save();
		}
		
		public function cancel() {
			$this->status = "cancelled";
			$this->cancelled_at = time();
			\Stripe\Stripe::setApiKey(config("services")["stripe"]["secret"]);
			\Stripe\Refund::create(["charge"                 => $this->stripe_id,
			                        "reverse_transfer"       => true,
			                        "refund_application_fee" => true]);
			$this->save();
		}
		
		/**
		 * Gets the items in the current order.
		 */
		public function getItemsAttribute() {
			$items = $this->products;
			foreach ($items as $item) {
				$item->quantity = $item->pivot->quantity;
				$item->orderPrice = $item->price * $item->quantity;
			}
			return $items;
		}
		
		/**
		 * @return float
		 */
		public function getDeliveryAttribute() {
			return $this->store->delivery;
		}
		
		/**
		 * @return int
		 */
		public function getCurrentCostAttribute() {
			$price = 0;
			foreach ($this->items as $item) {
				$price += $item->orderPrice;
			}
			return $price;
		}
		
		/**
		 * Submit an order
		 *
		 * @param      $name
		 * @param      $phone
		 * @param      $address
		 * @param      $source
		 * @param      $tip
		 * @param      $notes
		 *
		 * @return Result|\Illuminate\Http\RedirectResponse
		 */
		public function submit($name, $phone, $address, $source, $tip, $notes) {
			if (!$this->store->open) {
				return errorResult("Store is not currently open");
			}
			try {
				$productMessages = [];
				foreach ($this->items as $item) {
					$stock = intval($item->stock);
					$quantity = intval($item->quantity);
					if ($stock == 0) {
						$this->removeItem($item);
						$productMessages[] = "$item->name is now out of stock and has been removed from your order.";
					} elseif ($quantity > $stock) {
						$this->updateItem($item, $stock);
						$productMessages[] = "Not enough $item->name in stock. Quantity in order updated.";
					}
				}
				if (count($productMessages) > 0) {
					$message = implode(" ", $productMessages) . " Sorry!";
					$result = errorResult($message);
				} else {
					$this->subtotal = $this->currentCost;
					if ($this->promo && $this->customer && $this->promo->stores->contains($this->store) &&
						(!$this->promo->customers->contains($this->customer) || $this->promo->reusable == true)
					) {
						if ($this->promo->type == "fixed") {
							$this->subtotal -= $this->promo->amount;
						} elseif ($this->promo->type == "percent") {
							$this->subtotal -= ($this->subtotal * $this->promo->amount) / 100;
						}
						if (!$this->promo->reusable) {
							$this->promo->customers()->attach($this->customer_id);
						}
					}
					$this->name = $name;
					$this->phone = $phone;
					$this->address = $address;
					$this->customer->address = $address;
					$this->customer->save();
					$this->notes = $notes;
					$this->tip = $tip;
					$this->tax = round($this->store->taxrate / 100 * $this->subtotal, 2);
					$this->submitted_at = time();
					$this->total = $this->subtotal + $this->delivery + $this->tip + $this->tax;
					$application_fee = 30 + round(2.9 * $this->total * 100) + $this->store->fixed_fee * 100 +
						$this->store->percent_fee * 100 * ($this->subtotal);
					\Stripe\Stripe::setApiKey(config("services")["stripe"]["secret"]);
					$arr = ['amount'          => $this->total * 100,
					        'currency'        => 'usd',
					        'source'          => $source,
					        'application_fee' => $application_fee,
					        'destination'     => $this->store->stripe_id];
					$stripeCharge = \Stripe\Charge::create($arr);
					$this->stripe_id = $stripeCharge["id"];
					$this->status = "submitted";
					$this->save();
					foreach ($this->products as $item) {
						$item->stock -= $item->quantity;
						unset($item->quantity);
						unset($item->orderPrice);
						$item->save();
					}
					Mail::send("customer.emails.order",
					           ["order" => $this],
						function ($m) {
							$m->to($this->customer->email, $this->customer->name)->subject("Order submitted");
						});
					$result = successResult("Order submitted");
				}
			} catch (\Exception $ex) {
				
				$result = errorResult($ex->getMessage());
			}
			return $result;
		}
		
		/**
		 * Add item to order.
		 *
		 * @param mixed   $product
		 * @param integer $quantity
		 *
		 * @return Result|\Illuminate\Http\RedirectResponse
		 */
		public function addItem($product, $quantity) {
			if (!$product instanceof Product) {
				$product = Product::find($product);
			}
			if ($product) {
				if ($quantity > 0) {
					if ($this->products->contains($product->id)) {
						return $this->updateItem($product, $quantity + $this->products->find($product->id)->pivot->quantity);
					} else {
						if ($quantity <= $product->stock) {
							$this->products()->attach($product->id, ["quantity" => $quantity]);
							return successResult("Product $product->id added to order");
						} else {
							return errorResult("Not enough product $product->id in stock");
						}
					}
				} else {
					return errorResult("Quantity is 0");
				}
			} else {
				return errorResult(productNotFound($product));
			}
		}
		
		/**
		 * Update the quantity of an item in the order.
		 *
		 * @param mixed   $product
		 * @param integer $quantity
		 *
		 * @return Result|\Illuminate\Http\RedirectResponse
		 */
		public function updateItem($product, $quantity) {
			if (!$product instanceof Product) {
				$product = Product::find($product);
			}
			if ($product) {
				if (!$this->products->contains($product->id) && $quantity > 0) {
					return $this->addItem($product, $quantity);
				} else {
					if ($quantity == 0) {
						return $this->removeItem($product);
					} elseif ($quantity <= $product->stock) {
						$this->products()->updateExistingPivot($product->id, ["quantity" => $quantity]);
						return successResult("Product $product->id quantity updated");
					} else {
						return errorResult("Not enough $product->id in stock");
					}
				}
			} else {
				return errorResult(productNotFound($product));
			}
		}
		
		/**
		 * Remove item from order.
		 *
		 * @param mixed $product
		 *
		 * @return Result|\Illuminate\Http\RedirectResponse
		 */
		public function removeItem($product) {
			if (!$product instanceof Product) {
				$product = Product::find($product);
			}
			if ($product) {
				if ($this->products->contains($product->id)) {
					$this->products()->detach($product->id);
				}
				return successResult("Product $product->id removed from order");
			} else {
				return errorResult(productNotFound($product));
			}
		}
		
		/**
		 * Order contains many products, which can be in many orders.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
		 */
		public function products() {
			return $this->belongsToMany('App\Product')->withPivot("quantity");
		}
	}