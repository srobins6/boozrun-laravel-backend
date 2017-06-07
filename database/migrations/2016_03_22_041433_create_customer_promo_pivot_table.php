<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;

	class CreateCustomerPromoPivotTable extends Migration {

		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up() {
			Schema::create('customer_promo',
				function (Blueprint $table) {
					$table->integer('promo_id')->unsigned()->index();
					$table->foreign('promo_id')->references('id')->on('promos')->onDelete('cascade');
					$table->integer('customer_id')->unsigned()->index();
					$table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
					$table->primary(['promo_id', 'customer_id']);
				});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down() {
			Schema::drop('customer_promo');
		}
	}
