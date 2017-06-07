<?php
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;

	class CreateOldShoppingCartsTable extends Migration {

		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up() {
			Schema::create('old_shopping_carts',
				function (Blueprint $table) {
					$table->string('session_id')->primary();
					$table->integer('order_id')->unsigned()->index();
//					$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
					$table->timestamps();
				});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down() {
			Schema::drop('old_shopping_carts');
		}
	}
