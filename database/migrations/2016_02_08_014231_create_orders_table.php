<?php
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;

	class CreateOrdersTable extends Migration {

		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up() {
			Schema::create('orders',
				function (Blueprint $table) {
					$table->integer('driver_id')->unsigned()->index();
					$table->integer('store_id')->unsigned()->index();
					$table->integer('customer_id')->unsigned()->index();
					$table->increments('id');
					$table->float("total");
					$table->float("subtotal");
					$table->float("tip");
					$table->float("tax");
					$table->string('status')->default("cart");
					$table->string("address");
					$table->string("name");
					$table->string("phone");
					$table->string("notes");
					$table->string("stripe_id");
					$table->integer('promo_id')->unsigned()->index();
					$table->timestamp('submitted_at')->nullable();;
					$table->timestamp('packed_at')->nullable();;
					$table->timestamp('delivering_at')->nullable();;
					$table->timestamp('delivered_at')->nullable();;
					$table->timestamp('cancelled_at')->nullable();;
					$table->timestamps();
				});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down() {
			Schema::drop('orders');
		}
	}
