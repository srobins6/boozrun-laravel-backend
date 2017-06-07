<?php
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	
	class CreateCustomersTable extends Migration {
		
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up() {
			Schema::create('customers',
				function (Blueprint $table) {
					$table->increments('id');
					$table->string('email')->unique();
					$table->string('password', 60)->default(bcrypt("password"));
					$table->string('fb_id', 60);
					
					$table->string('name');
					$table->date('birthday');
					$table->string('phone');
					$table->string('address');
					$table->double('latitude', 8, 6);
					$table->double('longitude', 8, 6);
					$table->string("stripe_id");
					
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
			Schema::drop('customers');
		}
	}
