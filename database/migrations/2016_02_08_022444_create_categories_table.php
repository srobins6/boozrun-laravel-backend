<?php
use App\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('categories', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('parent_id')->references('id')->on('categories')->onDelete('cascade')->default(0);
			$table->timestamps();
		});
		$beer = Category::create(["name" => "Beer"]);
		$wine = Category::create(["name" => "Wine"]);
		$liquor = Category::create(["name" => "Liquor"]);
		$extras = Category::create(["name" => "Extras"]);
		$tobacco = Category::create(["name" => "Tobacco"]);
		$beer->children()->create(["name" => "Ales"]);
		$beer->children()->create(["name" => "Lagers"]);
		$beer->children()->create(["name" => "Ciders"]);
		$beer->children()->create(["name" => "Stouts"]);
		$beer->children()->create(["name" => "Pilsners/Porters"]);
		$wine->children()->create(["name" => "Red"]);
		$wine->children()->create(["name" => "White"]);
		$wine->children()->create(["name" => "Sparkling"]);
		$wine->children()->create(["name" => "Dessert"]);
		$liquor->children()->create(["name" => "Brandy"]);
		$liquor->children()->create(["name" => "Cognac"]);
		$liquor->children()->create(["name" => "Gin"]);
		$liquor->children()->create(["name" => "Liqueur"]);
		$liquor->children()->create(["name" => "Malt Beverages/Bitters"]);
		$liquor->children()->create(["name" => "Rum"]);
		$liquor->children()->create(["name" => "Tequila"]);
		$liquor->children()->create(["name" => "Vodka"]);
		$liquor->children()->create(["name" => "Whiskey"]);
		$extras->children()->create(["name" => "Chasers"]);
		$extras->children()->create(["name" => "Snacks"]);
		$extras->children()->create(["name" => "Miscellaneous"]);
		$tobacco->children()->create(["name" => "Cigarettes"]);
		$tobacco->children()->create(["name" => "Cigarillos"]);
		$tobacco->children()->create(["name" => "Cigars"]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('categories');
	}
}
