<?php
	namespace App;

	use Illuminate\Database\Eloquent\Model;

	/**
 * App\Category
 *
 * @property integer                                                        $id
 * @property string                                                       $name
 * @property integer                                                      $parent_id
 * @property \Carbon\Carbon                                               $created_at
 * @property \Carbon\Carbon                                               $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
 * @property-read \Illuminate\Database\Eloquent\Collection|Category[]     $children
 * @property-read Category                                                $parent
 * @method static Category find($value)
 * @method static \Illuminate\Database\Query\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin \Eloquent
 * @mixin \Eloquent
 */
	class Category extends Model {

		/**
		 * The attributes that are not mass assignable.
		 *
		 * @var array
		 */
		protected $guarded = [];

		/**
		 * Category has many products, which can have many categories.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
		 */
		public function products() {
			return $this->belongsToMany('App\Product');
		}

		/**
		 * Category can have many child categories.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\HasMany
		 */
		public function children() {
			return $this->hasMany('App\Category', "parent_id");
		}

		/**
		 * Category has one parent category.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
		public function parent() {
			return $this->belongsTo('App\Category', "parent_id");
		}

		/**
		 * Method runs on model initialization.
		 */
		public static function boot() {
			parent::boot();
			Category::creating(function ($category) {
				if (!$category->name) {
					if ($category->parent_id == 0) {
						$category->name = "Category" . (Category::where("parent_id", "0")->get()->count() + 1);
					} else {
						$category->name =
							$category->parent->name . "Subcategory" . ($category->parent->children->count() + 1);
					}
				}
			});
		}
	}
