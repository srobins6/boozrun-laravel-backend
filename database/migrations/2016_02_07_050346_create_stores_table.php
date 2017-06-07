<?php
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	
	class CreateStoresTable extends Migration {
		
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up() {
			Schema::create('stores',
				function (Blueprint $table) {
					$table->increments('id');
					$table->string('email')->unique();
					$table->string('password', 60)->default(bcrypt("password"));
					$table->string('owner_name');
					$table->string('name');
					$table->string('phone');
					$table->string('address');
					$table->string('city');
					$table->double('latitude', 8, 6);
					$table->double('longitude', 8, 6);
					$table->float('taxrate');
					$table->float("delivery")->default(0);
					$table->float("fixed_fee")->default(0);
					$table->float("percent_fee")->default(0);
					$table->string("stripe_id");
					$table->string("stripe_key");
					$table->string("stripe_secret");
					$table->boolean("stripe_verified")->default(false);
					$table->boolean('product_control')->default(false);
					$table->boolean('active')->default(true);
					$table->rememberToken();
					$table->timestamps();
				});
		}
		
		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down() {
			Schema::drop('stores');
		}
	}
