<?php
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;

	class CreatePromosTable extends Migration {

		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up() {
			Schema::create('promos',
				function (Blueprint $table) {
					$table->increments('id');
					$table->string("code");
					$table->string("type");
					$table->float("amount");
					$table->boolean("reusable")->default(false);
					$table->date("expiration_date")->nullable();
					$table->timestamps();
				});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down() {
			Schema::drop('promos');
		}
	}
