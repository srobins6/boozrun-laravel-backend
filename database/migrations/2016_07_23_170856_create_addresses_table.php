<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	
	class CreateAddressesTable extends Migration {
		
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up() {
			Schema::create('addresses',
				function (Blueprint $table) {
					$table->string('input');
					$table->string("address");
					$table->double('latitude', 8, 6);
					$table->double('longitude', 8, 6);
					$table->primary('input');
					$table->timestamps();
				});
		}
		
		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down() {
			Schema::drop('addresses');
		}
	}
