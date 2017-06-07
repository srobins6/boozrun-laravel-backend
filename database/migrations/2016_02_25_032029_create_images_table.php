<?php
	use App\Category;
	use App\Image;
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	
	class CreateImagesTable extends Migration {
		
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up() {
			Schema::create('images',
				function (Blueprint $table) {
					$table->increments('id');
					$table->string('name');
					$table->boolean('default')->default(false);
					$table->timestamps();
				});
			Image::create(["default" => true,
			               "name"    => "default"]);
			$categories = Category::where("parent_id", "0")->get()->sortBy("id");
			foreach ($categories as $category) {
				$categoryName = strtolower($category->name);
				Image::create(["default" => true,
				               "name"    => "default" . $categoryName]);
			}
		}
		
		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down() {
			Schema::drop('images');
		}
	}
