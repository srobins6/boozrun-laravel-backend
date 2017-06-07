<?php
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;

	class CreateDriversTable extends Migration {

		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up() {
			Schema::create('drivers', function (Blueprint $table) {
				$table->increments('id');
				$table->string('email')->unique();
				$table->string('password', 60)->default(bcrypt("password"));
				$table->string('name');
				$table->string('city');
				$table->string('phone');
				$table->boolean('confirmed')->default(false);
				$table->boolean('active')->default(true);
				$table->boolean('crime')->default(false);
				$table->string("crime_details");
				$table->boolean('accidents')->default(false);
				$table->string("accidents_details");
				$table->boolean('violations');
				$table->string('violations_details');
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
			Schema::drop('drivers');
		}
	}
