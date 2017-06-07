<?php
	namespace App;

	use Illuminate\Database\Eloquent\Model;

	/**
 * App\Product
 *
 * @property integer                                                    $id
 * @property string                                                     $name
 * @property integer                                                    $store_id
 * @property float                                                      $price
 * @property integer                                                    $stock
 * @property string                                                     $size
 * @property integer                                                    $image_id
 * @property string                                                     $description
 * @property boolean                                                    $active
 * @property \Carbon\Carbon                                             $created_at
 * @property \Carbon\Carbon                                             $updated_at
 * @property-read \App\Store                                            $store
 * @property \App\Image                                                 $image
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Order[] $orders
 * @property mixed                                                      $category
 * @property \Illuminate\Database\Eloquent\Collection|\App\Category[]   $categories
 * @property-write mixed                                                $subcategories
 * @method static Product find($value)
 * @method static \Illuminate\Database\Query\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Query\Builder|Product whereStoreId($value)
 * @method static \Illuminate\Database\Query\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|Product whereStock($value)
 * @method static \Illuminate\Database\Query\Builder|Product whereSize($value)
 * @method static \Illuminate\Database\Query\Builder|Product whereImageId($value)
 * @method static \Illuminate\Database\Query\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|Product whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin \Eloquent
 * @mixin \Eloquent
 */
	class Product extends Model {

		private $tempCategories;

		/**
		 * The attributes that are not mass assignable.
		 *
		 * @var array
		 */
		protected $guarded = [];

		/**
		 * Runs on model startup, handles saving of categories relations
		 */
		public static function boot() {
			parent::boot();
			Product::creating(function ($product) {
				if (!$product->name && $product->store) {
					$productNum = ($product->store->products->count() + 1);
					$product->name = "Product" . $productNum;
				}
			});
			Product::created(function ($product) {
				if (isset($product->tempCategories)) {
					$product->categories()->sync($product->tempCategories);
					unset($product->tempCategories);
				}
				$productName = strtolower(preg_replace("/\\W/", "", $product->name));
				$image = Image::where("name", strtolower($productName))->first();
				if (!$image && $product->category) {
					$image = Image::where("name", "default" . strtolower($product->category->name))->first();
				}
				if (!$image) {
					$image = Image::find(1);
				}
				if ($image) {
					$product->image()->associate($image);
					$product->save();
				}
			});
			Product::saved(function ($product) {
				if (isset($product->tempCategories)) {
					$product->categories()->sync($product->tempCategories);
					unset($product->tempCategories);
				}
				$productName = strtolower(preg_replace("/\\W/", "", $product->name));
				$image = Image::where("name", strtolower($productName))->first();
				if ($image) {
					$product->image()->associate($image);
//					$product->save();
				}
			});
		}

		/**
		 * Product belongs to a single store.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
		public function store() {
			return $this->belongsTo('App\Store');
		}

		/**
		 * Product has a single image.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
		public function image() {
			return $this->belongsTo('App\Image');
		}

		/**
		 * Product can be in many orders, which can contain many products.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
		 */
		public function orders() {
			return $this->belongsToMany('App\Order')->withPivot("quantity");
		}

		/**
		 * Get the first non subcategory category for the product.
		 *
		 * @return mixed
		 */
		public function getCategoryAttribute() {
			return $this->categories()->where("parent_id", 0)->first();
		}

		/**
		 * Product can have many categories, which can have many products.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
		 */
		public function categories() {
			return $this->belongsToMany('App\Category');
		}

		/**
		 * Handles setting of the categories attribute.
		 *
		 * @param mixed $value
		 */
		public function setCategoriesAttribute($value) {
			if (!is_array($value)) {
				$value = explode(",", $value);
			}
			$categories = collect([]);
			foreach ($value as $item) {
				if (!$item instanceof Category) {
					$category = Category::where("name", trim(ucwords($item)))->first();
					if (!$category) {
						$category = Category::find($item);
					}
				} else {
					$category = $item;
				}
				if ($category instanceof Category) {
					while ($category != null) {
						$categories->push($category);
						$category = $category->parent;
					}
				}
			}
			if (!isset($this->tempCategories)) {
				$this->tempCategories = $categories->pluck("id")->toArray();
			} else {
				$this->tempCategories =
					array_unique(array_merge($this->tempCategories, $categories->pluck("id")->toArray()));
			}
		}

		/**
		 * Handles setting of the subcategories attribute.
		 *
		 * @param mixed $value
		 */
		public function setSubcategoriesAttribute($value) {
			$this->categories = $value;
			//			if (!is_array($value)) {
			//				$value = explode(",", $value);
			//			}
			//			$categories = collect([]);
			//			foreach ($value as $item) {
			//				if (!$item instanceof Category) {
			//					$category = Category::where("name", trim(ucwords($item)))->first();
			//					if (!$category) {
			//						$category = Category::find($item);
			//					}
			//				} else {
			//					$category = $item;
			//				}
			//				if ($category instanceof Category) {
			//					while ($category != null) {
			//						$categories->push($category);
			//						$category = $category->parent;
			//					}
			//				}
			//			}
			//			if (!isset($this->tempCategories)) {
			//				$this->tempCategories = $categories->pluck("id")->toArray();
			//			} else {
			//				$this->tempCategories =
			//					array_unique(array_merge($this->tempCategories, $categories->pluck("id")->toArray()));
			//			}
		}
	}
