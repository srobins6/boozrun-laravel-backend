<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('products', function (Blueprint $table) {
			$table->increments('id');
			$table->string("name");
			$table->integer('store_id')->unsigned()->index();
			$table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
			$table->float('price');
			$table->integer('stock');
			$table->string('size');
			$table->integer('image_id');
			$table->string('description')->default("");
			//                ->unsigned()->index()->references('id')->on('products')->onDelete('cascade');
			$table->boolean('active')->default(true);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('products');
	}
}
