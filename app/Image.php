<?php
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\Storage;
	
	/**
	 * App\Image
	 *
	 * @property integer                                                      $id
	 * @property string                                                       $name
	 * @property string                                                       $full
	 * @property string                                                       $small
	 * @property boolean                                                      $default
	 * @property \Carbon\Carbon                                               $created_at
	 * @property \Carbon\Carbon                                               $updated_at
	 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product[] $products
	 * @method static Image find($value)
	 * @method static \Illuminate\Database\Query\Builder|Image whereId($value)
	 * @method static \Illuminate\Database\Query\Builder|Image whereName($value)
	 * @method static \Illuminate\Database\Query\Builder|Image whereFull($value)
	 * @method static \Illuminate\Database\Query\Builder|Image whereSmall($value)
	 * @method static \Illuminate\Database\Query\Builder|Image whereDefault($value)
	 * @method static \Illuminate\Database\Query\Builder|Image whereCreatedAt($value)
	 * @method static \Illuminate\Database\Query\Builder|Image whereUpdatedAt($value)
	 * @mixin \Eloquent
	 * @mixin \Eloquent
	 * @mixin \Eloquent
	 */
	class Image extends Model {
		
		/**
		 * The attributes that are not mass assignable.
		 *
		 * @var array
		 */
		protected $guarded = [];
		
		/**
		 * Get the string for the full sized file path
		 *
		 * @return string
		 */
		public function getFullAttribute() {
			return "product_images/full/" . $this->name . ".png";
		}
		
		/**
		 * Get the string for the small sized file path
		 *
		 * @return string
		 */
		public function getSmallAttribute() {
			return "product_images/small/" . $this->name . ".png";
		}
		
		/**
		 * Runs on model startup.
		 */
		public static function boot() {
			parent::boot();
			Image::updating(function ($image) {
				if (isset($image->original["name"]) && $image->original["name"] != $image->attributes["name"]) {
					$fileName = $image->attributes["name"] . ".png";
					$existingImage = Image::where("name", $image->attributes["name"])->first();
					$public = Storage::disk("public");
					if ($existingImage) {
						$public->delete($existingImage->full);
						$public->delete($existingImage->small);
					}
					Storage::disk("public")->move($image->small, "product_images/small/" . $fileName);
					Storage::disk("public")->move($image->full, "product_images/full/" . $fileName);
					if ($existingImage) {
						$image->delete();
					}
				}
			});
			Image::creating(function ($image) {
				if (!isset($image->original["name"])) {
					$re = "/product_images\\/full\\/(.*).png/";
					preg_match($re, $image->full, $matches);
					$image->name = strtolower($matches [1]);
					$existingImage = Image::where("name", $image->name)->first();
					if ($existingImage) {
						return false;
					}
				}
				return true;
			});
			Image::deleting(function ($image) {
				$public = Storage::disk("public");
				$public->delete($image->full);
				$public->delete($image->small);
				$image->products->each(function ($product) {
					$product->image()->dissociate();
					$product->save();
				});
			});
			Image::saved(function ($image) {
				$products = Product::all()->filter(function ($productValue) use ($image) {
					$re = '/\\W/';
					$subst = "";
					$result = strtolower(preg_replace($re, $subst, $productValue->name));
					$imageName = strtolower(preg_replace($re, $subst, $image->name));
					$nameMatch = $result == $imageName;
					return $nameMatch;
				});
				$image->products()->saveMany($products);
			});
		}
		
		/**
		 * Create an image from an uploaded file.
		 *
		 * @param      $file
		 * @param null $name
		 * @param bool $default
		 *
		 * @return Image
		 */
		public static function createFromFile($file, $name = null, $default = false) {
			$filePathName = $file->getPathname();
			$fileOriginalExtension = $file->getClientOriginalExtension();
			$fileOriginalName = $file->getClientOriginalName();
			if ($name) {
				$fileName = strtolower($name) . ".png";
			} else {
				$fileName = strtolower(str_replace("." . $fileOriginalExtension, "", $fileOriginalName));
			}
			$imageSize = getimagesize($filePathName);
			$width = $imageSize[0];
			$height = $imageSize[1];
			$image = imagecreatefromstring(file_get_contents($filePathName));
			$ratio = $height / $width;
			$fullImage = imagecreatetruecolor(500, 500);
			$white = imagecolorallocate($fullImage, 255, 255, 255);
			imagefilledrectangle($fullImage, 0, 0, 500, 500, $white);
			if ($width > $height) {
				$fullWidth = 460;
				$fullHeight = $fullWidth * $ratio;
				$full = imagescale($image, $fullWidth, $fullHeight);
				imagecopy($fullImage, $full, (500 - $fullWidth) / 2, (500 - $fullHeight) / 2, 0, 0, $fullWidth, $fullHeight);
			} else {
				$fullHeight = 460;
				$fullWidth = $fullHeight / $ratio;
				$full = imagescale($image, $fullWidth, $fullHeight);
				imagecopy($fullImage, $full, (500 - $fullWidth) / 2, (500 - $fullHeight) / 2, 0, 0, $fullWidth, $fullHeight);
			}
			imagepng($fullImage, public_path("product_images/full/$fileName.png"));
			$smallImage = imagescale($fullImage, 100, 100);
			imagepng($smallImage, public_path("product_images/small/$fileName.png"));
			$image = Image::create(["name" => $fileName, "default" => $default]);
			return $image;
		}
		
		/**
		 * Image can have many products.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\HasMany
		 */
		public function products() {
			return $this->hasMany('App\Product');
		}
	}
